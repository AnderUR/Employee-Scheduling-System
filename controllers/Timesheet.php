<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Timesheet extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        /*
         * defaultActiveSemesters sets active semester id which is used by SIE page to list all scheduled for the employee within that semester
         * defaultActiveSemesters uses current date to default this semester to within the semester range defined in semesters table
         * For more infor on defaultActive Semester, please review the doc on its method
         */
        $this->load->library('schedule');
        $this->load->library('shift');
        $this->load->library('summary');
        server::setController('timesheet');
    }

    function index()
    {
        /*
         * (Page: I)
         * Index awaiting to be added. Currently redirect to Day Status page.
         */
        schedule::defaultActiveSemester();
        schedule::setNavigatingUrls(true);
        redirect('timesheet/statusIndex');
    }

    function statusIndex()
    {
        /*
         * (Page: S)
         * Displays Day Status page to everyone
         * Uri Segment defines the date. By default, or if its not set, this will be current date.
         */
        schedule::pushNavigatingUrl((isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . "" . $_SERVER['REQUEST_URI']);
        server::require_login('timesheet/statusIndex');

        if (($this->uri->segment(3))) {
            $data['day'] = date('Y-m-d', strtotime($this->uri->segment(3)));
            $today = schedule::getDateFilterException(date('Y-m-d', strtotime($this->uri->segment(3))));
        } else {
            $range = schedule::getActiveDates();
            $today = schedule::getDateFilterException($range['rangeStart']);
            $data['day'] = $range['rangeStart'];
        }

        schedule::setActiveDates($today);

        $dbQuery = "select scheduleID as id, shiftID, scheduled_caID, signInstatus, scheduled_startTime, startTime, endTime, scheduledLocation_id "
            . "from ca_schedules.timesheetStatus "
            . "where date(`scheduledDate`) = '" . $today . "' order by scheduledLocation_id, scheduled_startTime";
        $tbl_data = summary::query($dbQuery);
        for ($j = 0; $j < sizeof($tbl_data); $j++) {
            $ca = new webuser($tbl_data[$j]['scheduled_caID']);
            $thisSchedule = new schedule($tbl_data[$j]['id']);
            $scheduleDate = explode(" ", $thisSchedule->getScheduledDate());
            $scheduleStartTime = $scheduleDate[0] . " " . $tbl_data[$j]['scheduled_startTime'];
            $signInFlag = 0;

            if ((is_numeric($tbl_data[$j]['shiftID']))) {
                $signInFlag = 1;
                $schedule = new shift($tbl_data[$j]['shiftID']);
                $tbl_data[$j]['id'] = "_" . $schedule->getShiftID();
            } else {
                $schedule = $thisSchedule;
            }
            $tbl_data[$j]['signInstatus'] = shift::getStatus($scheduleStartTime, $tbl_data[$j]['startTime'], $signInFlag);
            $tbl_data[$j]['scheduled_caID'] = $ca->getUsername();
            $tbl_data[$j]['scheduledLocation_id'] = $schedule->getLocationIMG(); //$schedule->getLocationText();
            $tbl_data[$j]['scheduled_startTime'] = shift::formatTime($tbl_data[$j]['scheduled_startTime']);
            $tbl_data[$j]['startTime'] = shift::formatTime($tbl_data[$j]['startTime']);
            $tbl_data[$j]['endTime'] = shift::formatTime($tbl_data[$j]['endTime']);
            unset($tbl_data[$j]['shiftID']);
        }

        $thisUser = new webuser();
        $data['linkUrl'] = webuser::view($thisUser->getUID(), "/timesheet/viewEditDaySchedule/");
        $data['linkText'] = webuser::view($thisUser->getUID(), "Edit");

        $data['title'] = "Timesheet Status";
        $data['tblData'] = $tbl_data;
        $data['header'] = "Announcement";
        $announcementMsg = "";
        $this->web->getResponse_ess('timesheet/statusIndex', $data, $announcementMsg);
    }

    function scheduleLabIndex()
    {
        /*
         * (Page: SLI)
         * Displays Scheduled shifts for the week of specified date
         * Data array sort by Location
         * Uri Segment for date
         */

        server::require_login('timesheet/scheduleLabIndex');

        if (($this->uri->segment(3))) {
            $today = date('Y-m-d', strtotime($this->uri->segment(3)));
        } else {
            $range = schedule::getActiveDates();
            $today = $range['rangeStart'];
        }

        schedule::setActiveDates($today);

        $data['week'] = schedule::getDateOfDayInWeek($today);

        $sunDate = schedule::getDateFilterException($data['week'][DayInWeek::Sunday]);
        $monDate = schedule::getDateFilterException($data['week'][DayInWeek::Monday]);
        $tuesDate = schedule::getDateFilterException($data['week'][DayInWeek::Tuesday]);
        $wednDate = schedule::getDateFilterException($data['week'][DayInWeek::Wednesday]);
        $thursDate = schedule::getDateFilterException($data['week'][DayInWeek::Thursday]);
        $friDate = schedule::getDateFilterException($data['week'][DayInWeek::Friday]);
        $satDate = schedule::getDateFilterException($data['week'][DayInWeek::Saturday]);

        $dbQuery = "call ca_schedules.piv_scheduleTable_byLab('"
            . $sunDate . "','"
            . $monDate . "','"
            . $tuesDate . "','"
            . $wednDate . "','"
            . $thursDate . "','"
            . $friDate . "','"
            . $satDate . "');";
        $qry_data = summary::query($dbQuery);

        $tbl_data = array();
        for ($j = 0; $j < sizeof($qry_data); $j++) {
            //generate new array with only the needed columns
            $ca = new webuser($qry_data[$j]['caID']);
            $schedule = new schedule($qry_data[$j]['id']);
            $tbl_data[$j]['id'] = $qry_data[$j]['locationID'];
            $tbl_data[$j]['locationID'] = $schedule->getLocationIMG();
            for ($dayOfWeek = DayInWeek::Sunday; $dayOfWeek <= DayInWeek::Saturday; $dayOfWeek++) {
                if (isset($qry_data[$j]["_" . str_replace("-", "", schedule::getDateFilterException($data['week'][$dayOfWeek]))])) {
                    $tbl_data[$j]["_" . str_replace("-", "", $data['week'][$dayOfWeek])] = "<b>" . ucwords(strtolower($ca->getProperties()['first_name'])) . "</b><br>" . $qry_data[$j]["_" . str_replace("-", "", schedule::getDateFilterException($data['week'][$dayOfWeek]))];
                } else {
                    $tbl_data[$j]["_" . str_replace("-", "", $data['week'][$dayOfWeek])] = "";
                }
            }
        }
        //Below the logic for consolidating the table columns on the dats
        //$j = rows nums for reconstructed consolidate_tbl
        $j = 0;
        //consolidated table with desired rows
        $consolidated_tbl = array();
        //tempRow is previous row.. this compares against current row
        $tempRow = array('id' => 0, 'locationID' => 0);
        //loops throught $tbl_data (filtered data with the desired columns from qry_table)
        for ($i = 0; $i < sizeof($tbl_data); $i++) {
            //sets current row for comparison with last row
            $curRow = $tbl_data[$i];
            if ((($curRow['id'] == $tempRow['id']) && ($curRow['locationID'] == $tempRow['locationID']))) {
                //if id and location match, the concat all other fields without incrementing $j
                for ($dayOfWeek = DayInWeek::Sunday; $dayOfWeek <= DayInWeek::Saturday; $dayOfWeek++) {
                    $concat = "";
                    //for very first row, this is always false and will not appear in consolidated_tbl array
                    if (($tempRow["_" . str_replace("-", "", $data['week'][$dayOfWeek])] != "") && ($curRow["_" . str_replace("-", "", $data['week'][$dayOfWeek])] != "")) {
                        //if there are value to concat, add a seperator
                        $concat = '<hr>';
                    }
                    $curRow["_" . str_replace("-", "", $data['week'][$dayOfWeek])] = $tempRow["_" . str_replace("-", "", $data['week'][$dayOfWeek])] . $concat . $curRow["_" . str_replace("-", "", $data['week'][$dayOfWeek])];
                }
                //replace the table value with desired value after concat
                $consolidated_tbl[$j] = $curRow;
            } else {
                //if not, this should still be kept if next row is a different CA or location
                if ($tempRow['id'] != 0) {
                    //if not first row
                    $consolidated_tbl[++$j] = $curRow;
                } else {
                    //if $curRow is first row
                    $consolidated_tbl[$j] = $curRow;
                }
            }
            //last row is now this row to compare with next row
            $tempRow = $curRow;
        }

        $data['title'] = "Scheduled Shifts";
        $data['blockClass'] = '<div class="small-centered small-12 medium-12 large-12 columns">';
        $data['tblData'] = $consolidated_tbl;
        $this->web->getResponse_ess('timesheet/scheduleIndex_byLab', $data);
    }

    function scheduleIndex()
    {
        server::require_login('timesheet/scheduleIndex');
        /*
         * Page: SI
         * Displays schedules shift for the week of specifed date
         * Data array sort by Emplpyee Name
         * Uri Segment specifies date
         */
        if (($this->uri->segment(3))) {
            $today = date('Y-m-d', strtotime($this->uri->segment(3)));
        } else {
            $range = schedule::getActiveDates();
            $today = $range['rangeStart'];
        }

        schedule::setActiveDates($today);

        $thisUser = new webuser();
        $data['week'] = schedule::getDateOfDayInWeek($today);
        $data['linkUrl'] = webuser::view($thisUser->getUID(),"/timesheet/manageEmployee/");
        $data['linkText'] = webuser::view($thisUser->getUID(), "View");


        $dbQuery = "call ca_schedules.piv_scheduleTable('"
            . $data['week'][DayInWeek::Sunday] . "','"
            . $data['week'][DayInWeek::Monday] . "','"
            . $data['week'][DayInWeek::Tuesday] . "','"
            . $data['week'][DayInWeek::Wednesday] . "','"
            . $data['week'][DayInWeek::Thursday] . "','"
            . $data['week'][DayInWeek::Friday] . "','"
            . $data['week'][DayInWeek::Saturday] . "');";
        $qry_data = summary::query($dbQuery);
        $tbl_data = array();
        for ($j = 0; $j < sizeof($qry_data); $j++) {
            //generate new array with only the needed columns
            $ca = new webuser($qry_data[$j]['caID']);
            $schedule = new schedule($qry_data[$j]['id']);
            $tbl_data[$j]['id'] = $qry_data[$j]['caID'];
            $tbl_data[$j]['scheduled_caID'] = $ca->getUsername();
            for ($dayOfWeek = DayInWeek::Sunday; $dayOfWeek <= DayInWeek::Saturday; $dayOfWeek++) {
                if (isset($qry_data[$j]["_" . str_replace("-", "", $data['week'][$dayOfWeek])])) {
                    $tbl_data[$j]["_" . str_replace("-", "", $data['week'][$dayOfWeek])] = $schedule->getLocationIMG() . "<br>" . $qry_data[$j]["_" . str_replace("-", "", $data['week'][$dayOfWeek])];
                } else {
                    $tbl_data[$j]["_" . str_replace("-", "", $data['week'][$dayOfWeek])] = "";
                }
            }
        }
        //Below the logic for consolidating the table columns on the dats
        //$j = rows nums for reconstructed consolidate_tbl
        $j = 0;
        $rowCA = "";
        //consolidated table with desired rows
        $consolidated_tbl = array();
        //tempRow is previous row.. this compares against current row
        $tempRow = array('id' => 0);
        //loops throught $tbl_data (filtered data with the desired columns from qry_table)
        for ($i = 0; $i < sizeof($tbl_data); $i++) {
            //sets current row for comparison with last row
            $curRow = $tbl_data[$i];
            if ((($curRow['id'] == $tempRow['id']))) {

                //replace the table value with desired value after concat
                //if id and location match, the concat all other fields without incrementing $j
                for ($dayOfWeek = DayInWeek::Sunday; $dayOfWeek <= DayInWeek::Saturday; $dayOfWeek++) {
                    $concat = "";
                    //for very first row, this is always false and will not appear in consolidated_tbl array
                    if (($tempRow["_" . str_replace("-", "", $data['week'][$dayOfWeek])] != "") && ($curRow["_" . str_replace("-", "", $data['week'][$dayOfWeek])] != "")) {
                        //if there are value to concat, add a seperator
                        $concat = '<hr>';
                    }
                    $curRow["_" . str_replace("-", "", $data['week'][$dayOfWeek])] = $tempRow["_" . str_replace("-", "", $data['week'][$dayOfWeek])] . $concat . $curRow["_" . str_replace("-", "", $data['week'][$dayOfWeek])];
                }
                $consolidated_tbl[$j] = $curRow;
            } else {
                //if not, this should still be kept if next row is a different CA or location
                if ($tempRow['id'] != 0) {
                    //if not first row
                    if ($curRow['scheduled_caID'] == $rowCA) {
                        $curRow['scheduled_caID'] = "";
                    } else {
                        $rowCA = $curRow['scheduled_caID'];
                    }
                    $consolidated_tbl[++$j] = $curRow;
                } else {
                    //if $curRow is first row
                    $consolidated_tbl[$j] = $curRow;
                }
            }
            //last row is now this row to compare with next row
            $tempRow = $curRow;
        }

        $data['title'] = "Scheduled Shifts";
        $data['blockClass'] = '<div class="small-centered small-12 medium-12 large-12 columns">';
        $data['tblData'] = $consolidated_tbl;
        $this->web->getResponse_ess('timesheet/scheduleIndex', $data);
    }

    function timesheetLabIndex()
    {
        /*
         * Page: TLI
         * Displays worked shifts for the week of specified date
         * Data array sort by Location
         * Uri Segment for date
         */

        server::require_login('timesheet/timesheetLabIndex');
        $curUser = new Webuser();

        if($curUser->is_supervisor()) {

            if (($this->uri->segment(3))) {
                $today = date('Y-m-d', strtotime($this->uri->segment(3)));
            } else {
                $range = schedule::getActiveDates();
                $today = $range['rangeStart'];
            }

            schedule::setActiveDates($today);

            $data['week'] = schedule::getDateOfDayInWeek($today);

            $dbQuery = "call ca_schedules.piv_timesheetTable_byLab('"
                . $data['week'][DayInWeek::Sunday] . "','"
                . $data['week'][DayInWeek::Monday] . "','"
                . $data['week'][DayInWeek::Tuesday] . "','"
                . $data['week'][DayInWeek::Wednesday] . "','"
                . $data['week'][DayInWeek::Thursday] . "','"
                . $data['week'][DayInWeek::Friday] . "','"
                . $data['week'][DayInWeek::Saturday] . "');";
            $qry_data = summary::query($dbQuery);

            $tbl_data = array();
            for ($j = 0; $j < sizeof($qry_data); $j++) {
                //generate new array with only the needed columns
                $ca = new webuser($qry_data[$j]['caID']);
                $schedule = new schedule($qry_data[$j]['scheduleID']);
                $tbl_data[$j]['id'] = $qry_data[$j]['locationID'];
                $tbl_data[$j]['locationID'] = $schedule->getLocationIMG();
                for ($dayOfWeek = DayInWeek::Sunday; $dayOfWeek <= DayInWeek::Saturday; $dayOfWeek++) {
                    if (isset($qry_data[$j]["_" . str_replace("-", "", $data['week'][$dayOfWeek])])) {
                        $tbl_data[$j]["_" . str_replace("-", "", $data['week'][$dayOfWeek])] = "<b>" . ucwords(strtolower($ca->getProperties()['first_name'])) . "</b><br>" . $qry_data[$j]["_" . str_replace("-", "", $data['week'][$dayOfWeek])];
                    } else {
                        $tbl_data[$j]["_" . str_replace("-", "", $data['week'][$dayOfWeek])] = "";
                    }
                }
            }

            //Below the logic for consolidating the table columns on the data
            //$j = rows nums for reconstructed consolidate_tbl
            $j = 0;
            //consolidated table with desired rows
            $consolidated_tbl = array();
            //tempRow is previous row.. this compares against current row
            $tempRow = array('id' => 0, 'locationID' => 0);
            //loops throught $tbl_data (filtered data with the desired columns from qry_table)
            for ($i = 0; $i < sizeof($tbl_data); $i++) {
                //sets current row for comparison with last row
                $curRow = $tbl_data[$i];
                if ((($curRow['id'] == $tempRow['id']) && ($curRow['locationID'] == $tempRow['locationID']))) {
                    //if id and location match, the concat all other fields without incrementing $j
                    for ($dayOfWeek = DayInWeek::Sunday; $dayOfWeek <= DayInWeek::Saturday; $dayOfWeek++) {
                        $concat = "";
                        //for very first row, this is always false and will not appear in consolidated_tbl array
                        if (($tempRow["_" . str_replace("-", "", $data['week'][$dayOfWeek])] != "") && ($curRow["_" . str_replace("-", "", $data['week'][$dayOfWeek])] != "")) {
                            //if there are value to concat, add a seperator
                            $concat = '<hr>';
                        }
                        $curRow["_" . str_replace("-", "", $data['week'][$dayOfWeek])] = $tempRow["_" . str_replace("-", "", $data['week'][$dayOfWeek])] . $concat . $curRow["_" . str_replace("-", "", $data['week'][$dayOfWeek])];
                    }
                    //replace the table value with desired value after concat
                    $consolidated_tbl[$j] = $curRow;
                } else {
                    //if not, this should still be kept if next row is a different CA or location
                    if ($tempRow['id'] != 0) {
                        //if not first row
                        $consolidated_tbl[++$j] = $curRow;
                    } else {
                        //if $curRow is first row
                        $consolidated_tbl[$j] = $curRow;
                    }
                }
                //last row is now this row to compare with next row
                $tempRow = $curRow;
                //print_r($tbl_data[$i]); echo "<br><br>";
            }

            $data['title'] = "View Timesheets";
            $data['blockClass'] = '<div class="small-centered small-12 medium-12 large-12 columns">';
            $data['tblData'] = $consolidated_tbl;
            $this->web->getResponse_ess('timesheet/timesheetIndex_byLab', $data);
        } else {
            redirect('timesheet/manageEmployee');
        }
    }

    function timesheetIndex()
    {
        /*
         * Page: TI
         * Displays worked shift for the week of specified date
         * Data array sort by Employee Name
         * Uri Segment for date
         */

        server::require_login('timesheet/timesheetIndex'); //send to manage employee to view hours etc
        $curUser = new Webuser();

        if($curUser->is_supervisor()) {

            if (($this->uri->segment(3))) {
                $today = date('Y-m-d', strtotime($this->uri->segment(3)));
            } else {
                $range = schedule::getActiveDates();
                $today = $range['rangeStart'];
            }

            schedule::setActiveDates($today);

            $thisUser = new webuser();
            $data['week'] = schedule::getDateOfDayInWeek($today);
            $data['linkUrl'] = webuser::view($thisUser->getUID(), "/timesheet/manageEmployee/");
            $data['linkText'] = webuser::view($thisUser->getUID(), 'View');

            $dbQuery = "call ca_schedules.piv_timesheetTable('"
                . $data['week'][DayInWeek::Sunday] . "','"
                . $data['week'][DayInWeek::Monday] . "','"
                . $data['week'][DayInWeek::Tuesday] . "','"
                . $data['week'][DayInWeek::Wednesday] . "','"
                . $data['week'][DayInWeek::Thursday] . "','"
                . $data['week'][DayInWeek::Friday] . "','"
                . $data['week'][DayInWeek::Saturday] . "');";
            $qry_data = summary::query($dbQuery);

            $tbl_data = array();
            for ($j = 0; $j < sizeof($qry_data); $j++) {
                //generate new array with only the needed columns
                $ca = new webuser($qry_data[$j]['caID']);
                $schedule = new schedule($qry_data[$j]['scheduleID']);
                $tbl_data[$j]['id'] = $qry_data[$j]['caID'];
                $tbl_data[$j]['scheduled_caID'] = $ca->getUsername();

                for ($dayOfWeek = DayInWeek::Sunday; $dayOfWeek <= DayInWeek::Saturday; $dayOfWeek++) {
                    if (isset($qry_data[$j]["_" . str_replace("-", "", $data['week'][$dayOfWeek])])) {
                        $tbl_data[$j]["_" . str_replace("-", "", $data['week'][$dayOfWeek])] = $schedule->getLocationIMG() . "<br>" . $qry_data[$j]["_" . str_replace("-", "", $data['week'][$dayOfWeek])];
                    } else {
                        $tbl_data[$j]["_" . str_replace("-", "", $data['week'][$dayOfWeek])] = "";
                    }
                }
            }

            //Below the logic for consolidating the table columns on the dats
            //$j = rows nums for reconstructed consolidate_tbl
            $j = 0;
            $rowCA = "";
            //consolidated table with desired rows
            $consolidated_tbl = array();
            //tempRow is previous row.. this compares against current row
            $tempRow = array('id' => 0);
            //loops throught $tbl_data (filtered data with the desired columns from qry_table)
            for ($i = 0; $i < sizeof($tbl_data); $i++) {
                //sets current row for comparison with last row
                $curRow = $tbl_data[$i];
                if ((($curRow['id'] == $tempRow['id']))) {
                    //if id and location match, the concat all other fields without incrementing $j
                    for ($dayOfWeek = DayInWeek::Sunday; $dayOfWeek <= DayInWeek::Saturday; $dayOfWeek++) {
                        $concat = "";
                        //for very first row, this is always false and will not appear in consolidated_tbl array
                        if (($tempRow["_" . str_replace("-", "", $data['week'][$dayOfWeek])] != "") && ($curRow["_" . str_replace("-", "", $data['week'][$dayOfWeek])] != "")) {
                            //if there are value to concat, add a seperator
                            $concat = '<hr>';
                        }
                        $curRow["_" . str_replace("-", "", $data['week'][$dayOfWeek])] = $tempRow["_" . str_replace("-", "", $data['week'][$dayOfWeek])] . $concat . $curRow["_" . str_replace("-", "", $data['week'][$dayOfWeek])];
                    }
                    //replace the table value with desired value after concat
                    $consolidated_tbl[$j] = $curRow;
                } else {
                    //if not, this should still be kept if next row is a different CA or location
                    if ($tempRow['id'] != 0) {
                        //if not first row
                        if ($curRow['scheduled_caID'] == $tempRow['scheduled_caID']) {
                            $curRow['scheduled_caID'] = "";
                        } else {
                            $rowCA = $curRow['scheduled_caID'];
                        }
                        $consolidated_tbl[++$j] = $curRow;
                    } else {
                        //if $curRow is first row
                        $consolidated_tbl[$j] = $curRow;
                    }
                }
                //last row is now this row to compare with next row
                $tempRow = $curRow;
                //print_r($tbl_data[$i]); echo "<br><br>";
            }

            $data['title'] = "View Timesheets";
            $data['blockClass'] = '<div class="small-centered small-12 medium-12 large-12 columns">';
            $data['tblData'] = $consolidated_tbl;
            $this->web->getResponse_ess('timesheet/timesheetIndex', $data);
        } else {
            redirect('timesheet/manageEmployee');
        }
    }

    function viewEditSchedule()
    {
        /*
         * Page: SIEE
         * Displays info on a specified scheduled shift for Edit
         * Only displays for Supervisors
         */
        server::require_login('timesheet/manageEmployee');
        $curUser = new webuser();

        if ($curUser->is_supervisor()) {
            $data['scheduleID'] = $this->input->post('emplScheduledID');
            if (is_numeric($data['scheduleID'])) {
                $data['title'] = "Edit Scheduled Shift";
                $this->load->view('timesheet/editSchedule', $data);
            } else {
                $data['title'] = "Edit Worked Shift";
                $data['shiftID'] = str_replace("_", "", $data['scheduleID']);
                $this->load->view('timesheet/editTimesheet', $data);
            }
        } else {
            echo "<center>You do not have enough privilege to view this Page!<br>".anchor('auth/login', 'LOGIN')."</center>";
        }
    }

    function viewEditDaySchedule()
    {
        /*
         * Page: SIEE
         * Displays info on a specified scheduled shift for Edit
         * Only displays for Supervisors
         * Uri Segment for scheduleID
         */
        server::require_login('timesheet/statusIndex');
        $curUser = new webuser();

        if ($curUser->is_supervisor()) {
            $daySchData = $this->input->post('emplDaySchData');
            $data['scheduleID'] = $daySchData['scheduledID'];
            $data['signedInBool'] = $daySchData['signedInBool'];
            $data['isStatusIndex'] = $this->uri->segment(2);

            if (is_numeric($data['scheduleID']) && ($data['signedInBool'] == "false")) {
                $data['title'] = "Edit Scheduled Shift";
                $this->load->view('timesheet/editDaySchedule', $data);
            } else if (is_numeric($data['scheduleID'])) {
                $data['title'] = "Edit Signed In Scheduled Shift";
                $this->load->view('timesheet/editDaySchedule', $data);
            } else {
                $data['title'] = "Edit Worked Shift";
                $data['shiftID'] = str_replace("_", "", $data['scheduleID']);
                $this->load->view('timesheet/editTimesheet', $data);
            }
        } else {
            echo "<center>You do not have enough privilege to view this Page!<br>".anchor('auth/login', 'LOGIN')."</center>";
        }
    }

    function viewEditTimesheet()
    {
        /*
         * Page: TIEE
         * Display specified work shift for Edit
         * Only displays for Supervisors
         */
        server::require_login('timesheet/manageEmployee');
        $curUser = new webuser();

        if ($curUser->is_supervisor()) {
            $data['shiftID'] = $this->input->post('shiftWorkedID');
            if (is_numeric($data['shiftID'])) {
                $data['title'] = "Edit Worked Shift";
                $this->load->view('timesheet/editTimesheet', $data);
            } else {
                $data['title'] = "Edit Scheduled Shift";
                $data['scheduleID'] = str_replace("_", "", $data['shiftID']);
                $this->load->view('timesheet/setInTimesheet', $data);
            }
        } else {
            echo "<center>You do not have enough privilege to view this Page!<br>".anchor('auth/login', 'LOGIN')."</center>";
        }
    }

    function addSchedule()
    {
        /*
         * Function: addSchedule
         * Only accessible to Supervisors
         * Request from Page SIE
         * Action via schedule library method
         * redirect to Page SIE
         */
        server::require_login('timesheet/manageEmployee');
        $curUser = new webuser();
        
        if ($curUser->hasPrivilege()) {
            $data = $this->input->post();
            if (!isset($data['chk_isRecursive'])) {
                $data['chk_isRecursive'] = 'off';
            }
            $shift = array(
                'caID' => $data['caID'],
                'scheduledDate' => schedule::getDateFilterException($data['scheduledDate']),
                'startTime' => ($data['scheduledDate'] . " " . $data['startTime']),
                'endTime' => ($data['scheduledDate'] . " " . $data['endTime']),
                'scheduledLocation_id' => $data['scheduledLocation_id'],
                'dayOfWeek' => date('w', strtotime($data['scheduledDate'])),
                'isRecursive' => $data['chk_isRecursive'],
                'Semester_id' => schedule::getActiveSemesterID(),
                'recursiveEndDate' => $data['recursiveEndDate'],
                'updateBy' => $curUser->getUID()
            );

            $thisSchedule = new schedule();
            $success = $thisSchedule->setScheduleShift($shift);
            if ($success == 1) {
                /*should not add shifts for past dates*/
                if(date($data['scheduledDate']) < date('Y-m-d')) {
                    echo "<center>You cannot add shifts for past dates. Use the add worked shift form instead. <br>".anchor(schedule::popNavigatingUrl(), 'Go back')."</center>";
                } else {
                    $thisSchedule->update();
                    redirect(schedule::popNavigatingUrl());
                }
            } else {
                echo "<center>End time must be after start time!<br>".anchor(schedule::popNavigatingUrl(), 'Try again')."</center>";
            }
        } else {
            echo "<center>You do not have enough privilege to view this Page!<br>".anchor('auth/login', 'LOGIN')."</center>";
        }
    }

    function addTimesheet()
    {
        /*
         * Only accessible to Supervisors
         * Request from Page TIE
         * Action via schedule library static method
         * redirect to Page TIE
         */
        server::require_login('timesheet/manageEmployee');
        $curUser = new webuser();

        if ($curUser->is_supervisor()) {
            $data = $this->input->post();

            $note = (isset($data['note'])) ? $data['note'] : '';
            $workedShift = array(
                'caID' => $data['caID'],
                'scheduledDate' => date('Y-m-d', strtotime($data['scheduledDate'])),
                'startTime' => ($data['startTime']),
                'endTime' => ($data['endTime']),
                'scheduledLocation_id' => $data['scheduledLocation_id'],
                'note' => $note,
                'updateBy' => $curUser->getUID()
            );

            if ($data['endTime'] >= $data['startTime'] && $data['endTime'] != $data['startTime']) {
                //date must be today or before
                if(date('Y-m-d', strtotime($data['scheduledDate'])) <= date('Y-m-d')) {
                    $enteredShiftID = schedule::addWorkedShift($workedShift);
                    $shift = new shift($enteredShiftID);
                    $data['enteredShift'] = $shift->toArray();
                    $data['shiftLocationText'] = $shift->getLocationText();
                    redirect(schedule::popNavigatingUrl());
                } else {
                    echo "<center>You cannot add a worked shift in the future.<br>".anchor(schedule::popNavigatingUrl(), 'Try again')."</center>";
                }
            } else {
                echo "<center>End time must be after start time!<br>".anchor(schedule::popNavigatingUrl(), 'Try again')."</center>";
            }
        } else {
            echo "<center>You do not have enough privilege to view this Page!<br>".anchor('auth/login', 'LOGIN')."</center>";
        }
    }

    function editSchedule()
    {
        /*
         * Only accessible to Supervisor
         * Request from Page SIEE
         * Action via schedule library method
         * redirect to Page S on the edited date
         */
        server::require_login('timesheet/manageEmployee');
        $curUser = new webuser();
        if ($curUser->is_supervisor()) {
            $data = $this->input->post();
            if (isset($data['submit'])) {
                if (!isset($data['chk_isRecursive'])) {
                    $data['chk_isRecursive'] = 'off';
                }
                $old_shift = new schedule($data['id']);
                if ((!isset($data['recursiveEndDate'])) || ($data['recursiveEndDate'] == 'null')) {
                    $data['recursiveEndDate'] = $old_shift->getRecursiveEndDate();
                }
                $shift = array(
                    'id' => $data['id'],
                    'caID' => $data['caID'],
                    'scheduledDate' => schedule::getDateFilterException($data['scheduledDate']),
                    'startTime' => ($data['startTime']),
                    'endTime' => ($data['endTime']),
                    'dayOfWeek' => date('w', strtotime($data['scheduledDate'])),
                    'scheduledLocation_id' => $data['scheduledLocation_id'],
                    'recursiveEndDate' => $data['recursiveEndDate'],
                    'isRecursive' => $data['chk_isRecursive'],
                    'Semester_id' => $old_shift->getSemesterID(),
                    'updateBy' => $curUser->getUID()
                );

                $thisSchedule = new schedule($shift['id']);
                $success = $thisSchedule->setScheduleShift($shift);
                if ($success == 1) {
                    if (isset($data['updateInstance']) && ($data['updateInstance'] == 1)) {
                        $thisSchedule->updateInstance();
                        redirect(schedule::popNavigatingUrl());
                    } else {
                        $thisSchedule->update();
                        redirect(schedule::popNavigatingUrl());
                    }
                } else {
                    echo "<center>End time must be after start time!<br>".anchor(schedule::popNavigatingUrl(), 'Try again')."</center>";
                }
            } elseif (isset($data['markTimesheet'])) {
                $schedule = new schedule($data['id']);
                
                if($data['endTime'] >= $data['startTime'] && $data['endTime'] != $data['startTime']) {
                    $schedule->markScheduledWorked($data);
                    redirect(schedule::popNavigatingUrl());
                } else {
                    echo "<center>End time must be after start time!<br>".anchor(schedule::popNavigatingUrl(), 'Try again')."</center>";
                }

            } elseif (isset($data['supervisorSignin'])) {
                $shift = new shift();
                $signInStatus = $shift->signin($data['caID'], false, true, $data['id'], true);
                //                if (is_numeric($signInStatus)) {
                //                    echo "Sign in Successful!";
                //                } else {
                //                    echo $signInStatus;
                //                }
                redirect(schedule::popNavigatingUrl());
            } else {
                
                $shiftStatus = Shift::getShiftStatus($data['id']);
                if(isset($shiftStatus)) {
                    echo "<center>This shift cannot be removed until it is removed from the worked shifts first. <br>".anchor(schedule::popNavigatingUrl(), 'Go back')."</center>";
                } else {  
                    $schedule = new schedule($data['id']);
                    $schedule->setToRemove();
                    if (isset($data['updateInstance']) && ($data['updateInstance'] == 1)) {
                        $schedule->updateInstance();
                        redirect(schedule::popNavigatingUrl());
                    } else {
                       if ( $schedule->update()) {
                        redirect(schedule::popNavigatingUrl());
                       } else {
                        echo "<center>Error. Make sure this shift has not been worked or signed into. Otherwise, contact your administrator. <br>".anchor(schedule::popNavigatingUrl(), 'Go back')."</center>";
                       }
                    }
                }                
            }
        } else {
            echo "<center>You do not have enough privilege to view this Page!<br>".anchor('auth/login', 'LOGIN')."</center>";
        }
    }

    function editTimesheet()
    {
        /*
         * Only accessible to Supervisors
         * Request from TIEE
         * Action via shift library method
         * redirect to TIE on the the week of edited date
         */
        server::require_login('timesheet/manageEmployee');
        $curUser = new webuser();
        
        if ($curUser->is_supervisor()) {
            $data = $this->input->post();
            if (isset($data['submit'])) {
                $schedule = new schedule($data['scheduleID']);
                $schedule->setLocation($data['scheduledLocation_id']);
                $schedule->updateInstance();
                $endTime = $data['endTime'];
                if ($endTime == "-") {
                    $endTime = "*";
                } else {
                    $endTime = ($data['scheduledDate'] . " " . $data['endTime']);
                }
                $shift = array(
                    'id' => $data['id'],
                    'caID' => $data['caID'],
                    'startTime' => ($data['scheduledDate'] . " " . $data['startTime']),
                    'endTime' => $endTime,
                    'note' => $data['note'],
                    'approvedBy' => $curUser->getUID()
                );

                $thisShift = new shift($shift['id']);
                $success = $thisShift->updateShift($shift);
                if ($success == 1) {
                    redirect(schedule::popNavigatingUrl());
                } else {
                    echo "<center>End time must be after start time!<br>".anchor(schedule::popNavigatingUrl(), 'Try again')."</center>";
                }
            } else {
                $thisShift = new shift($this->input->post('id'));
                $thisShift->delete();
                redirect(schedule::popNavigatingUrl());
            }
        } else {
            echo "<center>You do not have enough privilege to view this Page!<br>".anchor('auth/login', 'LOGIN')."</center>";
        }
    }

    /**
     * Changes the semester for the specified semester id passed through uri. 
     */
    function setSemester()
    {
        server::require_login('timesheet/manageEmployee');
        $thisUser = new Webuser();

        if($thisUser->is_supervisor()) {
            $semesterID = $this->uri->segment(3);
            schedule::setActiveSemester($semesterID);
            redirect(schedule::popNavigatingUrl());
        } else {
            echo "<center>You do not have enough privilege to view this Page!<br>".anchor('auth/login', 'LOGIN')."</center>";
        }
        
    }

    /**
     * For setting, adding, and editing semesters. Also has section for implementing future delete semester.
     * Note: Best to separate functionality in future improvements of this system.
     */
    function addSemester() 
    {
        /*
          $postUrl		the url of where this form will post to set semester
          $semesters    details on all the semester currently ($id, $name, $startDate, $endDate)
         */
        server::require_login('timesheet/adminPanel');
        $curUser = new Webuser(); 

        if($curUser->is_supervisor()) {

            $postData = $this->input->post();  

            if (isset($postData['submit'])) {
                if (!isset($postData['semester_name'])) {
                    $data['postUrl'] = 'timesheet/addSemester';
                    $data['semesters'] = schedule::getSemesters();
                    $this->load->view('timesheet/semester', $data);
                } else {
                    if(date('Y-m-d', strtotime($postData['startDate'])) >= date('Y-m-d', strtotime($postData['endDate']))) {
                        echo "<center>The start date must be before the end date.<br>".anchor(schedule::popNavigatingUrl(), 'Try again')."</center>";
                    } else {
                        $semesterRow = array(
                            'calendarLink' => $postData['calendarURL'],
                            'desc' => $postData['semester_name'] . " " . date('Y', strtotime($postData['startDate'])),
                            'startDate' => $postData['startDate'],
                            'endDate' => $postData['endDate']
                        );

                        schedule::addSemester($semesterRow);
                        redirect('timesheet/adminPanel');
                    }
                }
            } elseif (isset($postData['edit'])) {
                if(date('Y-m-d', strtotime($postData['startDate'])) >= date('Y-m-d', strtotime($postData['endDate']))) {
                    echo "<center>The start date must be before the end date.<br>".anchor(schedule::popNavigatingUrl(), 'Try again')."</center>";
                } else {
                    $data = $this->input->post();
                    $semesterRow = array(
                        'id' => $data['$semesterID'],
                        'calendarLink' => $data['calendarURL'],
                        'desc' => $data['semester_name'],
                        'startDate' => $data['startDate'],
                        'endDate' => $data['endDate']
                    );
                    Schedule::editSemester($semesterRow);
                    redirect('/timesheet/adminPanel');
                }
            } elseif (isset($postData['delete'])) {
                /**
                 * Requires implementation
                 */
                echo "delete";
                print_r($postData);
            }
        } else {
            echo "<center>You do not have enough privilege to view this Page!<br>".anchor('auth/login', 'LOGIN')."</center>";
        }
    }

    /**
     * Edit semester in the edit semester form from the admin panel table
     */
    function viewEditSemester()
    {
        server::require_login('timesheet/adminPanel');
        $curUser = new webuser();

        if ($curUser->is_supervisor()) {
            $semesterPost = $this->input->post();
            
            $semesterID = $semesterPost['semesterID'];
            $schedule = new schedule();
            $semesterData = $schedule->getSemesterByID($semesterID);
            $data['semesterData'] = $semesterData;
            $data['postUrl'] = 'timesheet/addSemester';
            $this->load->view('timesheet/editSemester', $data);
        } else {
            echo "<center>You do not have enough privilege to view this Page!<br>".anchor('auth/login', 'LOGIN')."</center>";
        }
    }

    function viewEmployee()
    {
        /*
         * Page: U
         *
         * Display detail user info for specific user
         * Only display to Supervisor or specified user
         *
         * Actions via db->update libservices.users
         *
         * Provides Routing to pages (SIE, TIE, U)
         */
        server::require_login('timesheet/manageEmployee');

        $data = $this->input->post();
        $caID = $data['caID'];
        $curUser = new webuser();

        if ($curUser->hasPrivilege() || ($caID == $curUser->getUID())) {
            if (isset($data['goSchedule'])) {
                redirect('timesheet/scheduleEmployee/' . $data['caID']);
            } elseif (isset($data['goTimesheet'])) {
                redirect('timesheet/timesheetEmployee/' . date('Y-m-d') . '/' . $data['caID']);
            } elseif (isset($data['editEmployee'])) {
                $updateArray = array(
                    'id' => $data['caID'],
                    'emergencyContact' => $data['emergencyContact'],
                    //'first_name' => $data['firstname'],
                    //'last_name' => $data['lastname'],
                    'phone' => $data['phone']
                );
                $this->db->where('id', $updateArray['id']);
                $this->db->update('libservices.users', $updateArray);

                redirect(schedule::popNavigatingUrl());
            } else {
                $data['title'] = "User Detail";
                $data['caID'] = $caID;
                $this->web->getResponse_ess('timesheet/userInfo', $data);
            }
        } else {
            echo "<center>You do not have enough privilege to view this Page!<br>".anchor('auth/login', 'LOGIN')."</center>";
        }
    }

    function viewTimesheetEmployeePeriod()
    {
        /*
         * Page: TIEP
         *
         * Display page to set range for specified user
         * Only displays to Supervisors or specified user
         *
         * Routing to TIE with date range for specified user
         */
        server::require_login('timesheet/manageEmployee');

        schedule::pushNavigatingUrl((isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . "" . $_SERVER['REQUEST_URI']);

        $data = $this->input->post();
        $curUser = new webuser();

        if ($curUser->hasPrivilege() ||  ( (isset($data['caID'])) && ($data['caID'] == $curUser->getUID())) ) {
            if (isset($data['fromDate']) && (isset($data['endDate'])) && (isset($data['caID']))) {
                $fromDate = $data['fromDate'];
                $endDate = $data['endDate'];
                if (strtotime($endDate) < (strtotime($fromDate))) {
                    $endDate = $fromDate;
                }
                redirect('/timesheet/manageEmployee/' . $data['caID'] . '/' . $fromDate . '/' . $endDate);
            } else {
                redirect('timesheet/manageEmployee');
            }
        } else {
            echo "<center>You do not have enough privilege to view this Page!<br>".anchor('auth/login', 'LOGIN')."</center>";
        }
    }

    function punchInTempShift()
    {
        /*
         * Function: IN
         * Signin in to a scheduled shift
         * Action via shift library method
         * redirect to Page S else shows a page with error
         */
        $data = $this->input->post();

        $curUser = new webuser();
        $curUser->setUserByBarcode($data['barcode']);
        $returnString = "";
        if (isset($data['btnSignInTemp'])) {
            //validate barcode to be valid
            $thisUser = new webuser();
            $thisUser->setUserByBarcode($data['barcode']);
            if ($thisUser->getUID() != 0) {
                $schedule = array(
                    'caID' => $thisUser->getUID(),
                    'scheduledDate' => date('Y-m-d'),
                    'startTime' => shift::QuarterTime(date('Y-m-d H:i:s')),
                    'endTime' => date('Y-m-d') . ' ' . $data['endTime'],
                    'dayOfWeek' => date('w', strtotime(date('Y-m-d'))),
                    'Semester_id' => schedule::getActiveSemesterID(),
                    'scheduledLocation_id' => $data['scheduledLocation_id'],
                    'isRecursive' => 'off',
                    'recursiveEndDate' => schedule::getActiveSemesterROW()['endDate'],
                    'updateBy' => $thisUser->getUID()
                );
                $thisSchedule = new schedule();
                $success = $thisSchedule->setScheduleShift($schedule);
                if ($success == 1) {
                    $thisSchedule->update();
                    $dataSignature['mode'] = "signin";
                    $dataSignature['barcode'] = $data['barcode'];
                    $dataSignature['tempShift'] = 1;
                    $this->load->view('timesheet/sign', $dataSignature);
                    //signin here
                } else {
                    $returnString2 = "Error code:" . $success . ' endTime must be after startTime';
                    $returnString2 = $returnString2 . '<br><a href="' . site_url('/timesheet/ipadPage') . '"> <button type="button" class="default button cancelShift">Go Back</button></a>';
                    $returnMessage2['message'] = $returnString2;
                    $this->web->getResponse_ess('timesheet/controls/returnMessage', $returnMessage2);
                }
            } else {
                $returnString = "Invalid barcode";
                $returnString = "<center>" . $returnString . '<br><a href="' . site_url('/timesheet/ipadPage') . '"> <button type="button" class="default button cancelShift">Go Back</button></a>';
            }
        } elseif (isset($data['btnSignInCancel'])) {
            redirect('timesheet/ipadPage');
        } else {
            $returnString = "Invalid barcode";
            $returnString = "<center>" . $returnString . '<br><a href="' . site_url('/timesheet/ipadPage') . '"> <button type="button" class="default button cancelShift">Go Back</button></a>';
        }
        echo $returnString;
    }

    function punchOut()
    {
        /*
         * Function: OUT
         * Signout to a scheduled and signed-in shift
         * Action via shift library method
         * redirect to page S else shows a page with error
         */

        $data = $this->input->post();
        $shift = new shift();
        $returnString = "";

        if(isset($data['barcode'])) {
            $signOutStatus = $shift->signoff($data['barcode']);
            if (is_numeric($signOutStatus)) {
                $returnString = "Sign out Successful.";
            } else {
                $returnString = "Error: " . $signOutStatus;
            }
            $returnString = "<center>" . $returnString . "<br>";
            echo $returnString;
        }
    }

    function scheduleSubstitute()
    {
        /*
         * Function: scheduleSubstitute
         * Only accessible to Supervisors
         * Request from SIEE
         *
         * change the scheduled employee of a scheduled shift to another
         *
         * redirect to SIEE
         */
        server::require_login('timesheet/statusIndex');
        $curUser = new webuser();

        if ($curUser->is_supervisor()) {
            $post = $this->input->post();
            if(isset($post)) {

                $ids = $post['emplID_schID'];
                $scheduleID = $ids['id'];
                $caID = $ids['schedule_substitute_employee_id'];

                $thisSchedule = new schedule($scheduleID);

                if (isset($ids['substituteID']) && $ids['substituteID'] == "semesterSubstitute") {
                    //substituteID for semesterSubstitute or daySubstitute
                    //differenciating between single substitute vs recursive substitute
                    $thisSchedule->substituteCA($caID, true);
                } else {
                    $thisSchedule->substituteCA($caID);
                }

                echo date('Y-m-d', strtotime($thisSchedule->getScheduledDate()));
            }
        } else {
            echo "<center>You do not have enough privilege to view this Page!<br>".anchor('auth/login', 'LOGIN')."</center>";
        }
    }

    /**
     * Gets the necessary data from the Schedule class to display the correct employee and time ranges for the worked shifts tab in the employee page.
     * Uses uris to obtain the data. 
     * privileged content viewing is determined in the view.
     */
    function manageEmployee()
    {
        schedule::pushNavigatingUrl((isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . "" . $_SERVER['REQUEST_URI']);

        $caID = $this->uri->segment(3);

        if (!is_numeric($caID)) {
            $thisUser = new webuser();
            $caID = $thisUser->getUID();
        }

        $range = schedule::getActiveDates();

        if ($this->uri->segment(4)) {
            $timesheetFromDate = $this->uri->segment(4);
        } else {
            $timesheetFromDate = $range['rangeStart'];
        }

        if ($this->uri->segment(5)) {
            $timesheetToDate = $this->uri->segment(5);
        } else {
            $timesheetToDate = $range['rangeEnd'];
        }

        $data['header'] = "header";
        $data['scheduleEmployeePage'] = schedule::scheduleEmployeePage($caID);
        $data['timesheetTmployeePage'] = shift::timesheetEmployeePage($caID, $timesheetFromDate, $timesheetToDate);
        $data['employeeInfoPage'] = webuser::userPage($caID);
        $this->web->getResponse_ess('timesheet/manageEmployee', $data);
    }

    /**
     * Gets all users and prints them as json data for user search in autocomplete.
     * Requires admin or supervisor privilege (1 or 2 in db).
     */
    function allUsers()
    {
        $user = new Webuser();

        if ($user->is_supervisor()) {
            $returnArr = array();
            $users = Webuser::getGuestUsers();
            for ($i = 0; $i < sizeof($users); $i++) {
                $arr = array(
                    ucwords(strtolower($users[$i]['username'])) . " (" . $users[$i]['email'] . "), " . $users[$i]['id']
                );
                $returnArr = array_merge($returnArr, $arr);
            }
            print_r(json_encode($returnArr, JSON_UNESCAPED_SLASHES));
        } else {
            redirect('timesheet');
        }
    }

    /**
     * Prints the currently logged in user as json data. 
     */
    /*function currentUser()
    {
        $user = new Webuser();
        $privilege = $user->hasPrivilege();
        $curData = array(
            'uid' => $user->getUID(),
            'user_group' => $user->getGroupID(),
            'privilege' => $privilege
        );
        print_r(json_encode($curData, JSON_UNESCAPED_SLASHES));
    }*/

    /**
     * Sign in/out page for employees. 
     */
    function ipadPage()
    {
        $this->web->getResponse_ess('timesheet/signIn_signOut');
    }

    /**
     * Convenience page for sign in/out for specific employee. 
     * The user data is set in the view based on the barcode passed in the uri segment 3
     */
    function ipadPageCA()
    {
        $data['barcode'] = $this->uri->segment(3);
        if (isset($data['barcode'])) {
            $this->web->getResponse_ess('timesheet/signIn_signOutCA', $data);
        } else {
            redirect('timesheet/ipadPage');
        }
    }

    /**
     * Receives data from admin panel's add employee tab.
     * Sets semester table data and edit form.
     */
    function adminPanel()
    {
        server::require_login();
        $curUser = new webuser();

        if ($curUser->is_supervisor()) {
            schedule::pushNavigatingUrl((isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . "" . $_SERVER['REQUEST_URI']);

            $data = $this->input->post();

            if (isset($_POST['newEmployeeByName'])) {
                $firstname = $data['firstname'];
                $lastname = $data['lastname'];
                $email = $data['email'];
                $phone = $data['phone'];
                $newEmpID = schedule::newEmp_byName($firstname, $lastname, $email, $phone);
                if (is_numeric($newEmpID) && $newEmpID != 0) {
                    //success
                    $newUser = new webuser($newEmpID);
                    echo $newUser->getBarcode();
                    redirect(schedule::popNavigatingUrl());
                } else {
                    echo "Error occured. (Email already exist?)";
                }
            } else {
                $semestersObj = schedule::getSemesters();

                for ($j = 0; $j < sizeOf($semestersObj); $j++) {
                    $semester[$j]['id'] = $semestersObj[$j]['id'];
                    $semester[$j]['desc'] = $semestersObj[$j]['desc'];
                    $semester[$j]['startDate'] = $semestersObj[$j]['startDate'];
                    $semester[$j]['endDate'] = $semestersObj[$j]['endDate'];
                }

                $semesterData['linkUrl'] = "/timesheet/addSemester/";
                $semesterData['linkText'] = "Edit";

                $semesterData['tbl_data'] = $semester;
                $this->web->getResponse_ess('timesheet/adminPanel', $semesterData);
            }
        } else {
            echo "<center>You do not have enough privilege to view this Page!<br>".anchor('auth/login', 'LOGIN')."</center>";
        }
    }

    /**
     * Signs in/out employees after the signature is submitted.
     * If the employee is not scheduled, shows temporary shift form.
     */
    function signatureSubmit()
    {
        $signature = $this->input->post('signature');
        $mode = $this->input->post('mode');
        $barcode = $this->input->post('barcode');
        $tempShift = $this->uri->segment(3);

        if (isset($barcode)) {
            if ($mode == 'signin') {
                $shift = new shift();
                $signInStatus = $shift->signin($barcode, $signature);
                if (is_numeric($signInStatus)) {

                    if ($tempShift == 1) {
                        Shift::markAsTempShift($signInStatus);
                    }

                    $user = new webuser();
                    $user->setUserByBarcode($barcode);
                    echo "Welcome, " . $user->getProperties()['first_name'] . '! Successfully signed in!';
                } else {
                    $info['signInStatus'] = $signInStatus;
                    $this->load->view('timesheet/controls/tempShift', $info);
                }
            } elseif ($mode == 'signout') {
                $returnString = '';
                $shift = new shift();
                $signOutStatus = $shift->signoff($barcode, $signature);
                if (is_numeric($signOutStatus)) {
                    $returnString = "Sign out Successful.";
                } else {
                    $returnString = "Error: " . $signOutStatus;
                }
                echo $returnString;
            }
        } else {
            redirect('timesheet/ipadPage');
        }
    }

    /**
     * Handles viewing employee signatures in the worked shifts table from the employee page, worked shifts tab.
     */
    function signatureView()
    {
        $curUser = new webuser();
        $shiftID = $this->uri->segment(3);
        $mode = $this->uri->segment(4);
        $shift = new shift($shiftID);

        $shiftCA = new webuser($shift->getCA_Id());

        if ($curUser->is_supervisor() || $curUser->getUID() == $shiftCA->getUID()) {
            $data['encoded_img'] = $shift->getSignature($mode);
            $this->load->view('timesheet/signature/enlarge', $data);
        } else {
            redirect('timesheet');
        }
    }

    function loginCheck()
    {
        $data = $this->input->post();
        $curUser = new webuser();

        if(isset($data['barcode'])) {
            $curUser->setUserByBarcode($data['barcode']);
            $returnString = "";
            if ($curUser->getUID() != 0) {
                if (isset($_POST['btnSignIn'])) {
                    /*
                    * Function: IN
                    * Signin in to a scheduled shift
                    * Action via shift library method
                    * redirect to Page S else shows a page with error
                    */
                    $shift = new shift();
                    $signInStatus = $shift->checkSignin($data['barcode']);

                    if (($signInStatus == "VALID")) {
                        $dataSignature['mode'] = "signin";
                        $dataSignature['barcode'] = $data['barcode'];
                        $dataSignature['tempShift'] = 0;
                        $this->load->view('timesheet/sign', $dataSignature);
                        //$returnString = "Sign in Successful!"; //redirect('timesheet/statusIndex');
                    } else {
                        $info['signInStatus'] = $signInStatus;
                        $info['barcode'] = $data['barcode'];
                        $this->load->view('timesheet/controls/tempShift', $info);
                    }
                } elseif (isset($_POST['btnSignOut'])) {
                    /*
                    * Function: OUT
                    * Signout to a scheduled and signed-in shift
                    * Action via shift library method
                    * redirect to page S else shows a page with error
                    */

                    $data = $this->input->post();
                    $dataSignature['mode'] = "signout";
                    $dataSignature['barcode'] = $data['barcode'];
                    $dataSignature['tempShift'] = 0;

                    $this->load->view('timesheet/sign', $dataSignature);
                }
            } elseif (isset($_POST['goback'])) {
                redirect('timesheet/ipadPage');
            } else {
                $returnString = "Invalid barcode";
                $returnString = $returnString . '<br><a href="' . site_url('/timesheet/ipadPage') . '"> <button type="button" class="default button cancelShift">Go Back</button></a>';
                $returnMessage['message'] = $returnString;
                $this->web->getResponse_ess('timesheet/controls/returnMessage', $returnMessage);
            }
        } else {
        echo "<center>Failed to get barcode.<br>".anchor('timesheet/ipadPage', 'Try again')."</center>";
        }
    }

    /**
     * Handles announcement actions for add, edit and remove.
     */
    function announcements()
    {
        server::require_login('timesheet/adminPanel');
        $curUser = new Webuser();

        if($curUser->is_supervisor()) {
            $data = $this->input->post();
            $announcement = array(
                'title' => $data['title'],
                'startDate' => $data['startDate'],
                'endDate' => $data['endDate'],
                'body' => $data['body'],
                'uid' => $data['uid'],
                'type' => 'manual'
            );
            if (isset($data['addAnnouncement'])) {
                $announcementID = schedule::addAnnouncement($announcement);
                if (is_numeric($announcementID)) {
                    redirect('timesheet/adminPanel');
                } else {
                    echo "failed to add announcement!";
                }
            } elseif (isset($data['editAnnouncement'])) {
                $announcement['id'] = $data['id'];
                schedule::editAnnouncement($announcement);
                redirect('timesheet/adminPanel');
            } elseif (isset($data['removeAnnouncement'])) {
                schedule::removeAnnouncement($data['id']);
                redirect('timesheet/adminPanel');
            } else {
                redirect('timesheet');
            }
        } else {
            echo "<center>You do not have enough privilege to view this Page!<br>".anchor('auth/login', 'LOGIN')."</center>";
        }
    }

    /**
     * Displays exceptions added to a semester. 
     * This is used in the Edit Semester form accessed from the admin panel > semester tab > semester table > edit
     */
    function viewExceptionAnnouncement()
    {
        server::require_login('timesheet/adminPanel');
        $curUser = new Webuser();

        if($curUser->is_supervisor()) {
            $exceptionID = $this->input->post('exceptionID');
            if (isset($exceptionID)) {
                $schObj = new schedule();
                $announcementID = $schObj->getExceptionAnnouncementID($exceptionID);
                $exceptionAnnouncement = $schObj->getAnnouncement($announcementID);
                if (isset($announcementID) && isset($exceptionAnnouncement)) {
                    echo $this->load->view('timesheet/editExceptionAnnouncement', $exceptionAnnouncement, true);
                } else {
                    echo "Announcement for this exception was not found. Please contact your administrator.";
                }
            } else {
                redirect('timesheet');
            }
        } else {
            echo "<center>You do not have enough privilege to view this Page!<br>".anchor('auth/login', 'LOGIN')."</center>";
        }
    }

    /**
     * Prints the set in timesheet form for the given employee.
     * Found in the status page > edit > edit shifts form
     */
    function viewSetInTimesheet()
    {
        server::require_login('timesheet/statusIndex');
        $curUser = new Webuser();
        
        if($curUser->is_supervisor()) {   
            $ids = $this->input->post('emplID_schID');
            if (isset($ids)) {
                $scheduleID['scheduleID'] = $ids['id'];
                $data = $this->load->view('timesheet/setInTimesheet', $scheduleID, true);
                echo ($data);
            } else {
                redirect('timesheet/statusIndex');
            }
        } else {
            echo "<center>You do not have enough privilege to view this Page!<br>".anchor('auth/login', 'LOGIN')."</center>";
        }
    }

    /**
     * Gets the help view specified by helpIdentifier and prints it for js to handle.
     */
    function helpMenu()
    {
        $helpIdentifier = $this->input->post('helpIdentifier');
        if (isset($helpIdentifier)) {
            echo ($this->load->view('/timesheet/help/' . $helpIdentifier, "", true));
        } else {
            redirect('timesheet');
        }
    }

    function showAnnouncements()
    {
        server::require_login('timesheet/showAnnouncements');

        $schedule = new schedule();
        $data['announcements'] = $schedule->getAnnouncements(Schedule::getDateFilterException());
        $this->web->getResponse_ess('timesheet/viewAnnouncements', $data);
    }

    /**
     * Handles post data for adding an exception and its corresponding announcement.
     * Prints out the added exception id
     */
    function addExceptionDate()
    {
        server::require_login('timesheet/adminPanel');
        $curUser = new Webuser();

        if($curUser->is_supervisor()) {
            $postData = $this->input->post();

            if (isset($postData)) {
                $data = array();
                foreach ($postData as $fields) {
                    for ($i = 0; $i < sizeof($fields); $i++) {
                        foreach ($fields as $key => $value) {
                            $data[$value['name']] = $value['value'];
                        }
                    }
                }

                $semesterid = $data['semesterID'];
                $exceptionOnDate = $data['onDate'];
                if (isset($data['swapDate']) && ($data['swapDate'] != '0000-00-00')) {
                    $exceptionSwapDate = $data['swapDate'];
                    $noWork = 0;
                } else {
                    $exceptionSwapDate = "-";
                    $noWork = 1;
                }
                $uid = $data['uid'];
                $body = "";

                if (isset($data['swapDate'])) {
                    $body = date('l m-d', strtotime($exceptionOnDate)) . " follows " . date('l m-d', strtotime($exceptionSwapDate)) . " schedule!";
                } else {
                    $body = "No Work on " . date('l m-d', strtotime($exceptionOnDate));
                }

                $announcement = array(
                    'title' => "Work Exception Schedule",
                    'startDate' => date('Y-m-d', strtotime($exceptionOnDate . '-7 days')),
                    'endDate' => $exceptionOnDate,
                    'body' => $body,
                    'uid' => $uid,
                    'type' => 'exceptionDate'
                );
                $announcementID = schedule::addAnnouncement($announcement);

                $exceptionRow = array(
                    'onDate' => $exceptionOnDate,
                    'swapDate' => $exceptionSwapDate,
                    'noWork' => $noWork,
                    'announcementID' => $announcementID,
                    'semesterID' => $semesterid
                );
                $exceptionRowID = schedule::addExceptionDate($exceptionRow);

                if (is_numeric($exceptionRowID)) {
                    echo ($exceptionRowID);
                } else {
                    echo "<center>Failed to add exception.<br>".anchor(Schedule::popNavigatingUrl(), 'Try again')."</center>";
                }
            } else {
                redirect('timesheet');
            }
        } else {
            echo "<center>You do not have enough privilege to view this Page!<br>".anchor('auth/login', 'LOGIN')."</center>";
        }
    }

    function removeExceptionDate()
    {
        server::require_login('timesheet/adminPanel');
        $curUser = new Webuser();

        if($curUser->is_supervisor()) {
            $exceptionID = $this->input->post('exceptionID');
            if (isset($exceptionID)) {
                $success = schedule::removeExceptionDate($exceptionID);
                if ($success == 1) {
                    echo "Exception Date Removed!";
                } else {
                    echo "Fail to remove Exception Date!";
                }
            }
        } else {
            echo "<center>You do not have enough privilege to view this Page!<br>".anchor('auth/login', 'LOGIN')."</center>";
        }
    }

    /**
     * Sends email notifications to employees if there is an exception for today.
     * This function requires an automated script to be run everyday. 
     * Edit the $emailData to modify the email content.
     */
    function sendMorningReminder()
    {
        server::require_login();
        $curUser = new Webuser();

        if($curUser->is_admin()) {
            if (date('Y-m-d') != Schedule::getDateFilterException()) {
                $announcements = Schedule::getAnnouncementsOnEndDate(date('Y-m-d'));
                if (sizeof($announcements) > 0) {
                    $data['announcements'] = $announcements;
                    $emailData['title'] = "ESS Notice";
                    $emailData['titleImgSrc'] = 'C:/wamp64/www/LibServices/assets/ess_assets/img/userIcon.png';
                    $emailData['emailBody'] = $this->load->view('timesheet/emails/morningReminders', $data, true);
                    $emailData['helpContact'] = $this->load->view('timesheet/emails/supervisorContactInfo', '', true);

                    $emailContent = $this->load->view('templates/email/boilerplate', $emailData, true);
                    $CAs = Schedule::getDaySchdule_CA_Distinct();
                    foreach ($CAs as $ca) {
                        $this->notify->email_user($ca['caID'], $emailContent, false, "CA Reminders");
                    }
                    $this->notify->email_user(3, $emailContent, false, "Announcement reminder");
                }
            }
            $announcements = Schedule::getAnnouncementsOnEndDate(Schedule::getDateFilterException());
        
            if (sizeof($announcements) > 0) {
                $data['announcements'] = $announcements;
                $emailData['title'] = "ESS Notice";
                $emailData['titleImgSrc'] = 'C:/wamp64/www/LibServices/assets/ess_assets/img/userIcon.png';
                $emailData['emailBody'] = $this->load->view('timesheet/emails/morningReminders', $data, true);
                $emailData['helpContact'] = $this->load->view('timesheet/emails/supervisorContactInfo', '', true);

                $emailContent = $this->load->view('templates/email/boilerplate', $emailData, true);
                $CAs = Schedule::getDaySchdule_CA_Distinct();
                foreach ($CAs as $ca) {
                    $this->notify->email_user($ca['caID'], $emailContent, false, "CA Reminders");
                }
                $this->notify->email_user(3, $emailContent, false, "Announcement reminder");
            }
        } else {
            echo "<center>You have to have admin privilege privilege to view this Page!<br>".anchor('auth/login', 'LOGIN')."</center>"; 
        }
    }

    /**
     * This function needs editing, specially the email section. 
     * Please refer to the sendMorningReminder function in this controller for an example.
     */
    //    function sendLateCASigninReminder() {
    //        $daySchedules = Schedule::getDaySchdule(Schedule::getDateFilterException());
    //        foreach ($daySchedules as $scheduleROW) {
    //            $schedule = new Schedule($scheduleROW['id']);
    //            $scheduledTime = strtotime($schedule->getStartTime());
    //            if (($schedule->ifSignedIn() == false )
    //                    && (strtotime(date('H:i')) >= $scheduledTime )
    //                    && (strtotime(date('H:i')) < strtotime('+5 minutes',$scheduledTime))) {
    //                $data['scheduleID'] = $scheduleROW['id'];
    //                $emailBody['content'] = $this->load->view('timesheet/emails/onShiftSigninReminder_toCA', $data, true);
    //                $emailContent = $this->load->view('templates/email/boilerplate', $emailBody, true);
    //                $this->notify->email_user($schedule->getCA_Id(), $emailContent, false, "Shift signin reminder");
    ////                $this->notify->email_user(3, $emailContent, false, "CA Reminders");
    //            }
    //        }
    //    }
}