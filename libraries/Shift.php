<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Shift {

    private $id;
    private $caID;
    private $startTime;
    private $endTime;
    private $scheduledShiftsID;
    private $note;
    private $shiftROW;
    private $scheduledShiftRow;
    private $instance;

    static function getDayShifts($specifiedDate = false) {
        /*
         * Returns result_array of all worked shifts of the specified date
         */
        $me = & get_instance();
        $me->db->select('id, caID, startTime, endTime, scheduledShifts_id');
        if ($specifiedDate != false) {
            $me->db->where('date(`startTime`)', (date('Y-m-d', $specifiedDate)));
        } else {
            $me->db->where('date(`startTime`)', date('Y-m-d'));
        }
        $me->db->order_by('startTime desc');
        $query = $me->db->get('ca_schedules.Shifts');
        return $query->result_array();
    }

    static function getShiftStatus($scheduleShiftID) {
        /*
         * Returns row_array of the specified shift id
         */
        $me = & get_instance();
        $me->db->select('id, caID, startTime, endTime, scheduledShifts_id');
        $me->db->where('scheduledShifts_id', $scheduleShiftID);
        $me->db->order_by('startTime desc');
        $query = $me->db->get('ca_schedules.Shifts');
        return $query->row_array();
    }

    function __construct($shiftID = false) {
        /*
         * Constructor
         *
         * if specified shift id
         *  set variables by setShift
         * else
         *  set id = 0
         *  set scheduledShiftsID = 0;
         */
        $this->instance = & get_instance();
        if ((isset($shiftID)) && ($shiftID != 0)) {
            $this->setShift($this->getShiftROW($shiftID));
        } else {
            $this->id = 0;
            $this->scheduledShiftsID = 0;
        }
    }

    private function setShift($shiftROW) {
        /*
         * sets this obj
         *  shiftRow
         *  id
         *  caID
         *  startTime
         *  endTime
         *  note
         *  scheduledShiftsID
         */
        $this->shiftROW = $shiftROW;
        $this->id = $shiftROW['id'];
        $this->caID = $shiftROW['caID'];
        $this->startTime = $shiftROW['startTime'];
        $this->endTime = $shiftROW['endTime'];
        $this->note = $shiftROW['note'];
        $this->scheduledShiftsID = $shiftROW['ScheduledShifts_id'];
    }

    private function getShiftROW($shiftID) {
        /*
         * returns row_array from Shifts db table for specified shift id
         * This method is used in constructor
         */
        $this->instance->db->select('id, caID, ScheduledShifts_id, startTime, endTime, note, signatureIn, signatureOut');
        $this->instance->db->where('id', $shiftID);
        $this->instance->db->limit(1);
        $query = $this->instance->db->get('ca_schedules.Shifts');
        return $query->row_array();
    }

    public function getShiftID() {
        return $this->id;
    }

    public function getCA_Id() {
        return $this->caID;
    }

    public function getEndTime() {
        return $this->endTime;
    }

    static function getLocations() {
        $me = & get_instance();
        $me->db->select('id, locationText, locationIMG');
        $query = $me->db->get('ca_schedules.scheduledLocation');
        return $query->result_array();
    }

    static function timesheetEmployeePage($caID, $fromDate, $toDate) {
        /*
         * Returns a html view of all worked shifts for the week of specified date
         */
        server::require_login();
        schedule::setActiveDates($fromDate, $toDate);
        
        $data['caID'] = $caID;
        $me = & get_instance();
        $returnStr = "";
        $curUser = new webuser();
        if ($curUser->hasPrivilege() || ($data['caID'] == $curUser->getUID())) {
            $data['linkUrl'] = "/timesheet/viewEditTimesheet/";
            $data['linkText'] = webuser::view($curUser->getUID(), "Edit");

            $today = $fromDate;
            $periodEndDate = $toDate;

            $data['week'] = schedule::getDateOfDayInWeek($today); 
            $data['periodEndWeek'] = schedule::getDateOfDayInWeek($periodEndDate);

            $dbQuery = "call ca_schedules.timesheetEmployeeTable("
                    . $data['caID'] . ", '"
                    . $data['week'][DayInWeek::Sunday] . "', '"
                    . $data['periodEndWeek'][DayInWeek::Saturday] . "');";

            $qry_data = summary::query($dbQuery);
            
            $totalMin = 0;
            $totalHrs = 0;

            foreach ($qry_data as $perShift) {
                //Problem: currently special shifts do not calculate total hours; the following check is needed $perShift['timeDiff'] != "")
                if ((is_numeric($perShift['shiftID'])) && ($perShift['endTime'] != "*" && !is_null($perShift['timeDiff']))) {// && ($perShift['approved'] == 1) ) {
                    $hours = (string) $perShift['timeDiff'];
                    $time_segment = explode(":", $hours);
                    $hr = (int)$time_segment[0];
                    $min = (int)$time_segment[1];
                    $totalMin += $min;
                    $totalHrs += $hr;
                }
            }

            $totalHrs += $totalMin / 60;
            $totalMin %= 60;
            $data['totalWorkedHrs'] = (int) $totalHrs . ":" . sprintf("%02d", (int) $totalMin);

            $tbl_data = array();
            for ($j = 0; $j < sizeof($qry_data); $j++) {
                //generate new array with only the needed columns
                $tbl_data[$j]['scheduledDate'] = date('D m/d/y', strtotime($qry_data[$j]['scheduledDate']));
                if ($qry_data[$j]['note'] != "") {
                    $shiftNote = webuser::view(webuser::getLoggedInUid(), '*');
                } else {
                    $shiftNote = '';
                }
                $approved = '';
                if ($qry_data[$j]['approved'] != 1) {
                    $approved = '<span class="approvalICO has-tip" title="This employee signed in to an unscheduled shift. Make sure the details of this shift are correct because the hours count towards this employee’s total time.">!</span>';
                }
                $approved .= $shiftNote;

                if (isset($qry_data[$j]['shiftID'])) {
                    $signatureIn['encoded_img'] = $qry_data[$j]['signatureIn'];
                    $signatureIn['url'] = "Libservices/index.php/timesheet/signatureView/" . $qry_data[$j]['shiftID'] . '/' . 'signin';

                    $signatureOut['encoded_img'] = $qry_data[$j]['signatureOut'];
                    $signatureOut['url'] = "Libservices/index.php/timesheet/signatureView/" . $qry_data[$j]['shiftID'] . '/' . 'signout';

                    $thisShift = new shift($qry_data[$j]['shiftID']);
                    $tbl_data[$j]['id'] = $qry_data[$j]['shiftID'];
                    $tbl_data[$j]['scheduledLocation_id'] = $thisShift->getLocationIMG();
                    $tbl_data[$j]['status'] = $qry_data[$j]['signInstatus'];
                    $tbl_data[$j]['Shift'] = date("H:i", strtotime($qry_data[$j]['startTime'])) . "-" . shift::formatTime($qry_data[$j]['endTime']);
                    $tbl_data[$j]['In'] = $me->load->view('timesheet/signature/thumbnail', $signatureIn, true);
                    $tbl_data[$j]['Out'] = $me->load->view('timesheet/signature/thumbnail', $signatureOut, true);
                    $tbl_data[$j]['total'] = $qry_data[$j]['timeDiff'];
                    //$tbl_data[$j]['Notes'] = $shiftNote;
                    $tbl_data[$j]['Approved'] = $approved;
                } else {
                    $thisShift = new schedule($qry_data[$j]['scheduleID']);
                    $tbl_data[$j]['id'] = "_" . $thisShift->getScheduleID();
                    $tbl_data[$j]['scheduledLocation_id'] = $thisShift->getLocationIMG();
                    $tbl_data[$j]['status'] = $qry_data[$j]['signInstatus'];
                    $tbl_data[$j]['Shift'] = "-";
                    $tbl_data[$j]['In'] = "-";
                    $tbl_data[$j]['Out'] = "-";
                    $tbl_data[$j]['total'] = "-";
                    //$tbl_data[$j]['Notes'] = $shiftNote;
                    $tbl_data[$j]['Approved'] = '';
                }
            }

            $data['title'] = "Worked Shifts";
            $data['tblData'] = $tbl_data;
            $data['fromDate'] = $fromDate;
            $data['toDate'] = $toDate;
            $returnStr = $me->load->view('timesheet/timesheetEmployee', $data, true);
        } else {
            $returnStr = "<center>You do not have enought privilege to view this Page!<br>".anchor('auth/login', 'LOGIN')."</center>";
        }
        return $returnStr;
    }

    public function signin($barcode, $signature = false, $supervisor = false, $scheduleID = 0, $useScheduledSigninTime = false) {
        /*
         * This method signin for employee on their scheduled shifts
         *
         * $timeLimit sets the windows of time prior and after the scheduled hours to be allowed to self-signin
         *
         * This method sets scheduleShiftROW via setActiveShift method to identify the scheduledShift
         * to be verified and apply signin process for the approprieate employee and shift
         *  If above fails
         *      returns error message
         *  else
         *      sets startTime
         *      sets caID
         *      sets scheduledShiftsID
         *      sets data array via above
         *      If this scheduledShiftRow is not worked & there are no shifts thats pending signout
         *          allow to signin and insert to Shift db table
         *          return auto-increment Shifts db tabl id
         *      else
         *          returns error message
         *
         */

        //This method SIGNINs for timesheet
        //returns 0 when false
        //If TRUE, returns the signin ID in the SHIFT TABLE
        $timeLimit = 20;
        $ca = new webuser();
        $ca->setUserByBarcode($barcode);

        if ($supervisor == false) {
            $this->setActiveShift($ca->getUID(), 'signin', $timeLimit);

            if (isset($this->scheduledShiftRow['id'])) {
                $this->startTime = $this->getValidSignInTime(date("Y-m-d H:i:s")); 
                $this->caID = $this->scheduledShiftRow['caID'];
                $this->scheduledShiftsID = $this->scheduledShiftRow['id'];

                $signatureData = "";
                if ($signature != false) {
                    $signatureData = $signature;
                }

                $signin = array(
                    'startTime' => $this->startTime,
                    'caID' => $this->caID,
                    'ScheduledShifts_id' => $this->scheduledShiftsID,
                    'approvedBy' => $this->scheduledShiftRow['updateBy'],
                    'signatureIn' => $signatureData
                );
                if ($this->checkSingleLogin() && $this->countUnFinishedShifts($ca->getUID()) == 0) {
                    $this->instance->db->insert('ca_schedules.Shifts', $signin);
                    $shiftID = $this->instance->db->insert_id();
                    return $shiftID;
                } else {
                    if ($this->checkSingleLogin() == FALSE) {
                        return "You have already signin.";
                    } else {
                        return "You have " . $this->countUnFinishedShifts($ca->getUID()) . " shifts not signout! <br>Please bring this to the attention of your supervisor!";
                    }
                }
            } else {
                $hasScheduledFlag = false;
                $scheduledShifts = schedule::getDaySchdule(strtotime(date('Y-m-d')), $ca->getUID());
                foreach ($scheduledShifts as $scheduleShift) {
                    $curTime = date('Y-m-d H:i:s');
                    $scheduledStart = $scheduleShift['startTime'];
                    $scheduledEnd = $scheduleShift['endTime'];
                    if (strtotime($curTime) >= strtotime($scheduledStart) && strtotime($curTime) <= strtotime($scheduledEnd)) {
                        $hasScheduledFlag = true;
                    }
                }
                if ($hasScheduledFlag == false) {
                    return "NO SCHEDULED SHIFT";
                } else {
                    return "You have a scheduled shift but have exceeded the time range to signin. <br> Please contact your supervisor.";
                }
            }
        } else {
            $supervisor = new webuser();
            if ($scheduleID != 0) {
                $this->scheduledShiftRow = $this->getScheduleShiftROW($scheduleID);
                $this->startTime = $this->getValidSignInTime(date("Y-m-d H:i:s")); 
                $this->caID = $this->scheduledShiftRow['caID'];
                $this->scheduledShiftsID = $this->scheduledShiftRow['id'];

                if ($useScheduledSigninTime === true) {
                    $this->startTime = $this->scheduledShiftRow['startTime'];
                }

                $signin = array(
                    'startTime' => $this->startTime,
                    'caID' => $this->caID,
                    'ScheduledShifts_id' => $this->scheduledShiftsID,
                    'approvedBy' => $supervisor->getUID()
                );
                if ($this->checkSingleLogin() && $this->countUnFinishedShifts($ca->getUID()) == 0) {
                    $this->instance->db->insert('ca_schedules.Shifts', $signin);
                    return $this->instance->db->insert_id();
                } else {
                    if ($this->checkSingleLogin() == FALSE) {
                        return "You have already signin.";
                    } else {
                        return "You have " . $this->countUnFinishedShifts($ca->getUID()) . " shifts not signout! Please bring this to the attention of your supervisor!";
                    }
                }
            } else {
                return "Schedule id is not passed. Operation cancelled.";
            }
        }
    }

    public function checkSignin($barcode, $supervisor = false, $scheduleID = 0) {
        /*
         * This method CHECKS signin for employee on their scheduled shifts
         *
         * $timeLimit sets the windows of time prior and after the scheduled hours to be allowed to self-signin
         *
         * This method sets scheduleShiftROW via setActiveShift method to identify the scheduledShift
         * to be verified and apply signin process for the approprieate employee and shift
         *  If above fails
         *      returns error message
         *  else
         *      sets startTime
         *      sets caID
         *      sets scheduledShiftsID
         *      sets data array via above
         *      If this scheduledShiftRow is not worked & there are no shifts thats pending signout
         *          allow to signin and insert to Shift db table
         *          return auto-increment Shifts db tabl id
         *      else
         *          returns error message
         *
         */

        //This method SIGNINs for timesheet
        //returns 0 when false
        //If TRUE, returns the signin ID in the SHIFT TABLE
        $timeLimit = 20;
        $ca = new webuser();
        $ca->setUserByBarcode($barcode);

        if ($supervisor == false) {
            $this->setActiveShift($ca->getUID(), 'signin', $timeLimit);

            if (isset($this->scheduledShiftRow['id'])) {
                $this->startTime = $this->getValidSignInTime(date("Y-m-d H:i:s"));
                $this->caID = $this->scheduledShiftRow['caID'];
                $this->scheduledShiftsID = $this->scheduledShiftRow['id'];
                $signin = array(
                    'startTime' => $this->startTime,
                    'caID' => $this->caID,
                    'ScheduledShifts_id' => $this->scheduledShiftsID,
                    'approvedBy' => $this->scheduledShiftRow['updateBy']
                );
                if ($this->checkSingleLogin() && $this->countUnFinishedShifts($ca->getUID()) == 0) {
                    return "VALID";
                } else {
                    if ($this->checkSingleLogin() == FALSE) {
                        return "You have already signin.";
                    } else {
                        return "You have " . $this->countUnFinishedShifts($ca->getUID()) . " shifts not signout! <br>Please bring this to the attention of your supervisor!";
                    }
                }
            } elseif ($this->countUnFinishedShifts($ca->getUID()) > 0) {
                return "You have already signin.";
            } else {
                $hasScheduledFlag = false;
                $scheduledShifts = schedule::getDaySchdule(strtotime(date('Y-m-d')), $ca->getUID());
                foreach ($scheduledShifts as $scheduleShift) {
                    $curTime = date('Y-m-d H:i:s');
                    $scheduledStart = $scheduleShift['startTime'];
                    $scheduledEnd = $scheduleShift['endTime'];
                    if (strtotime($curTime) >= strtotime($scheduledStart) && strtotime($curTime) <= strtotime($scheduledEnd)) {
                        $hasScheduledFlag = true;
                    }
                }
                if ($hasScheduledFlag == false) {
                    return "NO SCHEDULED SHIFT";
                } else {
                    return "You have a scheduled shift but have exceeded the time range to signin. <br> Please contact your supervisor.";
                }
            }
        } else {
            $supervisor = new webuser();
            if ($scheduleID != 0) {
                $this->scheduledShiftRow = $this->getScheduleShiftROW($scheduleID);
                $this->startTime = $this->getValidSignInTime(date("Y-m-d H:i:s")); 
                $this->caID = $this->scheduledShiftRow['caID'];
                $this->scheduledShiftsID = $this->scheduledShiftRow['id'];
                $signin = array(
                    'startTime' => $this->startTime,
                    'caID' => $this->caID,
                    'ScheduledShifts_id' => $this->scheduledShiftsID,
                    'approvedBy' => $supervisor->getUID()
                );
                if ($this->checkSingleLogin() && $this->countUnFinishedShifts($ca->getUID()) == 0) {
                    return "VALID";
                } else {
                    if ($this->checkSingleLogin() == FALSE) {
                        return "You have already signin.";
                    } else {
                        return "You have " . $this->countUnFinishedShifts($ca->getUID()) . " shifts not signout! Please bring this to the attention of your supervisor!";
                    }
                }
            } else {
                return "Schedule id is not passed. Operation cancelled.";
            }
        }
    }

    public function signoff($barcode, $signature = false) {
        /*
         * This methos signout a scheduled and signed-in shift whose open for signout
         *
         * sets endTime via getValidSignOutTime
         *
         * If this obj is set via setActiveShift
         *  allow transaction and update db table Shifts on signout time
         *  returns this obj.id
         * else
         *  error handler
         *  return error messages
         */
        $ca = new webuser();
        $ca->setUserByBarcode($barcode);
        if ($ca->getUID() != 0) {
            $this->setActiveShift($ca->getUID(), 'signout');

            $signatureData = "";
            if ($signature != false) {
                $signatureData = $signature;
            }

            $this->endTime = $this->getValidSignOutTime(date("Y-m-d H:i:s"));
            if ($this->endTime != 0) {
                if ($this->id != 0) {
                    $this->instance->db->set('endTime', $this->endTime);
                    $this->instance->db->set('signatureOut', $signatureData);
                    $this->instance->db->where('id', $this->id);
                    $this->instance->db->update('ca_schedules.Shifts');
                    return $this->id;
                } else if ($this->countUnFinishedShifts($ca->getUID()) == 0) {
                    return "No Active Shift.";
                } else {
                    return "You have " . $this->countUnFinishedShifts($ca->getUID()) . " shifts not signout! Please bring this to your supervisor's attention.";
                }
            } else {
                return "Invalid signout time. Please contact your supervisor.";
            }
        } else {
            return "Invalid Barcode";
        }
    }

    private function setActiveShift($caID, $mode, $timeRangeInMin = false) {
        /*
         * This method defines and sets this obj
         *
         * $caID = sets this obj based on this employee
         * $mode = defines key variables. signin or signout
         * $timeRangeInMin = sets the range of time of the possible scheduled shifts to signin
         *
         * If signin
         *      gets all user date shifts
         *      filters and gets 1 scheduled shift closest to the current time
         *      sets scheduledShiftRow
         * else if signout
         *      if countUnFinishedShifts == 1
         *          sets this obj with the 1 unfinished shift
         *          sets scheduledShiftRow
         */

        $todayShifts = schedule::getUserShifts($caID, $location = false, true, strtotime(schedule::getDateFilterException()));
        $currentShift = "";
        $currentTime = date("H:i:s");
        if ($mode == "signin") {
            $minimum = $timeRangeInMin * 60;
            for ($i = 0; $i < sizeof($todayShifts); $i++) {
                $temp = strtotime($todayShifts[$i]['startTime']) - strtotime($currentTime);
                if (abs($temp) <= $minimum) {
                    $currentShift = $todayShifts[$i];
                    $minimum = abs($temp);
                }
            }
            $this->scheduledShiftRow = $currentShift;
        } else if ($mode == "signout") {
            if ($this->countUnFinishedShifts($caID) == 1) {
                $this->setShift($this->getUnFinishedShift($caID));
                $this->scheduledShiftRow = $this->getScheduleShiftROW($this->scheduledShiftsID);
            }
        }
    }

    private function getScheduleShiftROW($scheduledShiftID) {
        /*
         * returns row_array from db table ScheduledShifts for the specified scheduledShift id
         */
        $this->instance->db->select('*');
        $this->instance->db->where('id', $scheduledShiftID);
        $query = $this->instance->db->get('ca_schedules.ScheduledShifts');
        return $query->row_array();
    }

    private function roundTime($input) {
        /*
         * $input is time, usually in the format of this.startTime, this.endTime
         *
         * This method rounds $inputTime to the nearest quarter of an hour
         * returns rounded time
         */

        $minutes = date('i', strtotime($input));
        $hour = date('H', strtotime($input));
        $diff = $minutes % 15;
        if ($diff >= 7) {
            $minutes = $minutes - ($minutes % 15) + 15;
            if ($minutes == 60) {
                $minutes = "00";
                $hour += 1;
            }
        } else {
            $minutes = $minutes - ($minutes % 15);
            if ($minutes == 0) {
                $minutes = "00";
            }
        }
        return date('Y-m-d', strtotime($input)) . " " . $hour . ":" . $minutes . ":00";
    }

    static function QuarterTime($input) {
        /*
         * $input is time, usually in the format of this.startTime, this.endTime
         *
         * This method rounds $inputTime to the nearest quarter of an hour
         * returns rounded time
         */

        $minutes = date('i', strtotime($input));
        $hour = date('H', strtotime($input));
        $diff = $minutes % 15;
        if ($diff >= 7) {
            $minutes = $minutes - ($minutes % 15) + 15;
            if ($minutes == 60) {
                $minutes = "00";
                $hour += 1;
            }
        } else {
            $minutes = $minutes - ($minutes % 15);
            if ($minutes == 0) {
                $minutes = "00";
            }
        }
        return date('Y-m-d', strtotime($input)) . " " . $hour . ":" . $minutes . ":00";
    }

    public function getValidSignInTime($time) {
        /*
         * This method takes in inputTime then returns a validTime for signinTime
         *
         * This method eliminates problems such as overlapping hours
         * Also wraps roundTime so minutes will be valid for PRAssist
         */
        if (strtotime(($time)) > strtotime($this->scheduledShiftRow['startTime'])) {
            //echo 1;
            return date("Y-m-d H:i:s", strtotime($this->roundTime($time)));
        } else {
            //echo 0;
            return date("Y-m-d H:i:s", strtotime($this->scheduledShiftRow['startTime']));
        }
    }

    public function getStartTime() {
        return $this->startTime;
    }

    public function getValidSignOutTime($time) { /*
     * This method takes in inputTime then returns a validTime for signoutTime
     *
     * This method eliminates problems such as overlapping hours
     * Also wraps roundTime so minutes will be valid for PRAssist
     */

        $scheduleSignoutTime = date("Y-m-d", strtotime(schedule::getDateFilterException($this->scheduledShiftRow['scheduledDate']))) . " " . date('H:i:s', strtotime($this->scheduledShiftRow['endTime']));

        if ((strtotime($this->roundTime($time)) - strtotime($scheduleSignoutTime)) / 60 >= 20) {
            return 0; //out of range
        } elseif (strtotime($this->roundTime($time)) >
                strtotime(date("Y-m-d", strtotime($this->scheduledShiftRow['scheduledDate'])) . " " .
                        $this->scheduledShiftRow['endTime'])) {
            return date("Y-m-d H:i:s", strtotime(date("Y-m-d", strtotime($this->scheduledShiftRow['scheduledDate'])) . " " . $this->scheduledShiftRow['endTime']));
        } else {
            return date("Y-m-d H:i:s", strtotime($this->roundTime($time)));
        }
    }

    private function checkSingleLogin() {
        //ensure for the scheduled shift, is ONLY allowed to punchin ONCE
        $this->instance->db->select('*');
        $this->instance->db->where('ScheduledShifts_id', $this->scheduledShiftsID);
        $query = $this->instance->db->get('ca_schedules.Shifts');
        if ($query->num_rows() == 0) {
            return true;
        } else {
            return false;
        }
    }

    private function countUnFinishedShifts($caID) {
        /*
         * Returns the number of worked shifts thats not signed-out
         *
         * Used to verify and set this obj for signout purpose
         */
        $this->instance->db->select('*');
        $this->instance->db->where('caID', $caID);
        $this->instance->db->where('startTime != ""');
        $this->instance->db->where('endTime', '*');
        $query = $this->instance->db->get('ca_schedules.Shifts');
        return $query->num_rows();
    }

    private function getUnFinishedShift($caID) {
        /*
         * return row_array of specified caID of the worked shift that hasn't been signed-out
         *
         * Used following countUnFinishedShifts == 1 to ensure that this is the only valid worked shift needed to be signed-out
         */
        $this->instance->db->select('*');
        $this->instance->db->where('caID', $caID);
        $this->instance->db->where('startTime != ""');
        $this->instance->db->where('endTime', '*');
        $this->instance->db->limit(1);
        $query = $this->instance->db->get('ca_schedules.Shifts');
        return $query->row_array();
    }

    public function getLocationText() {
        $scheduleShift = new schedule($this->scheduledShiftsID);
        return $scheduleShift->getLocationText();
    }

    public function getLocationID() {
        $scheduleShift = new schedule($this->scheduledShiftsID);
        return $scheduleShift->getLocationID();
    }

    public function getLocationIMG() {
        $scheduleShift = new schedule($this->scheduledShiftsID);
        return $scheduleShift->getLocationIMG();
    }

    public function getDayOfWeek() {
        $scheduleShift = new schedule($this->scheduledShiftsID);
        return $scheduleShift->getDayOfWeek();
    }

    public function getScheduledDate() {
        $scheduleShift = new schedule($this->scheduledShiftsID);
        return $scheduleShift->getScheduledDate();
    }

    public function delete() {
        /*
         * DELETE this obj in db table Shifts of this.id
         */
        $this->instance->db->where('id', $this->id);
        $this->instance->db->delete('ca_schedules.Shifts');
    }

    private function update() {
        /*
         * UPDATE this obj in db table Shifts of this obj
         */
        $this->instance->db->where('id', $this->id);
        $this->instance->db->update('ca_schedules.Shifts', $this->toArray());
    }

    public function getScheduleID() {
        return $this->scheduledShiftsID;
    }

    public function toArray() {
        /*
         * Converts this obj to array
         */
        $thisShift = array(
            'id' => $this->id,
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
            'ScheduledShifts_id' => $this->scheduledShiftsID,
            'caID' => $this->caID,
            'note' => $this->note
        );
        return $thisShift;
    }

    public function updateShift($shiftArray) {
        /*
         * Updates this obj via parameter array
         * On startTime, endTime, caID, note
         * Then update to db table Shifts
         */

        if ($shiftArray['endTime'] == "*" || (strtotime($shiftArray['endTime']) >= strtotime($shiftArray['startTime']))) {
            $this->startTime = $shiftArray['startTime'];
            $this->endTime = $shiftArray['endTime'];
            $this->caID = $shiftArray['caID'];
            $this->note = $shiftArray['note'];
            $this->update();
            return 1;
        } else {
            return 0;
        }
    }

    static function formatTime($time) {
        /*
         * returns formatted inputTime (H:i)
         */

        if (($time != "") && (strpos($time, '*') === false)) {
            return date('H:i', strtotime($time));
        } else {
            return "-";
        }
    }

    function getNote() {
        return $this->note;
    }

    static function getStatus($scheduledTime, $signinTime, $ifSignIn) {
        if ($signinTime == "") {
            $signinTime = date('Y-m-d H:i:s');
        }

        $diff = (strtotime($scheduledTime) - strtotime($signinTime)) / 60;
        $status = "";
        if ($diff == 0) {
            $status = "On-time";
        } elseif (($diff <= -20) && $ifSignIn == 1) {
            $status = "No show";
        } elseif (($diff >= -20) && ($diff >= 0) && ($diff < 20)) {
            $status = "Due";
        } elseif (($diff < 0) && ($diff >= -20)) {
            $status = "Late";
        } else {
            $status = "NA";
        }
       
        return $status;
    }

    public function getSignature($mode) {
        if ($mode == 'signin') {
            return $this->getShiftROW($this->id)['signatureIn'];
        } else {
            return $this->getShiftROW($this->id)['signatureOut'];
        }
    }

    static function markAsTempShift($shiftID) {
        $me = & get_instance();
        $me->db->set('approved', 0);
        $me->db->where('id', $shiftID);
        $me->db->update('ca_schedules.Shifts');
    }

}
