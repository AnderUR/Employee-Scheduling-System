<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

abstract class DayInWeek
{
    const Sunday = 0;
    const Monday = 1;
    const Tuesday = 2;
    const Wednesday = 3;
    const Thursday = 4;
    const Friday = 5;
    const Saturday = 6;

}

class Schedule{
    private $id;
    private $startTime;
    private $endTime;
    private $dayOfWeek;
    private $timestamp;
    private $semesterID;
    private $caID;
    private $scheduledDate;
    private $locationID;
    private $isRecursive = false;
    private $recursiveEndDate;
    private $toRemove;

    private $locationROW;
    private $semesterROW;
    private $scheduleROW;
    private $instance;

    private $updateBy;
    private $listView = 1;
    private $recursiveID = 0;

    static $activeSemesterROW;


    static function getWeekSchedule($specifiedDate = false){
        //Returns result_array of all the scheduled shifts for the week of specifiedDate
        $dates = array();

        for ($i = 0; $i < 7; $i++) {
            if ($specifiedDate != false) {
                $dates[$i] = schedule::getDateOfDayInWeek($specifiedDate)[$i]; //ex. $specifiedDate = '2016-07-20';
            } else {
                $dates[$i] = schedule::getDateOfDayInWeek()[$i];
            }
        }

        $me = & get_instance();
        $me->db->select('id, scheduledDate, startTime, endTime,dayOfWeek, caID, scheduledLocation_id');

        foreach ($dates as $date) {
            $me->db->or_where('date(`scheduledDate`)', date($date));
        }

        $me->db->order_by('dayOfWeek asc');
        $query = $me->db->get('ca_schedules.ScheduledShifts');
        return $query->result_array();
    }
    static function getDaySchdule($specifiedDate = false, $caID = false){
        //Return result_array of all scheduled shift for the specifiedDate
        $me = & get_instance();
        $me->db->select('id, caID, startTime, endTime, scheduledLocation_id');


        if ($specifiedDate != false) {
            $me->db->where('date(`scheduledDate`)', date('Y-m-d',strtotime($specifiedDate)));
        } else {
            $me->db->where('date(`scheduledDate`)', date('Y-m-d'));
        }

        if ($caID != false) {
            $me->db->where('caID', $caID);
        }


        $me->db->order_by('startTime desc');
        $query = $me->db->get('ca_schedules.ScheduledShifts');
        return $query->result_array();
    }

    static function getDaySchdule_CA_Distinct($specifiedDate = false) {
        $me = & get_instance();
        $me->db->distinct();
        $me->db->select('caID');
        if ($specifiedDate != false) {
            $me->db->where('date(`scheduledDate`)', date('Y-m-d',strtotime($specifiedDate)));
        } else {
            $me->db->where('date(`scheduledDate`)', date('Y-m-d'));
        }
        $query = $me->db->get('ca_schedules.ScheduledShifts');
        return $query->result_array();
    }

    static function getUserShifts($caID, $locationID = false, $system = false, $specifiedDate = false){

        //Returns all scheduled shift for a specified employee on activeSemester
        //Filters:
        //$locationID = lab area
        //$system = if listView effects outcome

        $me = & get_instance();
        $me->db->select('id, caID, Semester_id, isRecursive, recursiveEndDate, dayOfWeek, scheduledDate, startTime, endTime, scheduledLocation_id, listView, recursiveID, updateBy');

        $me->db->where('caID',$caID);
        $me->db->where('Semester_id', schedule::getActiveSemesterID());


        if ($specifiedDate != false) {
            $me->db->where('date(`scheduledDate`)', date('Y-m-d', $specifiedDate));
        } else {
            $me->db->where('date(`scheduledDate`)', date('Y-m-d'));
        }

        if ($system == false){
            $me->db->where('listView',1);
        }

        if ($locationID != false){
        	$me->db->where('scheduledLocation_id',$locationID);
        }
        $me->db->order_by('scheduledDate asc');
        $query = $me->db->get('ca_schedules.ScheduledShifts');
        return $query->result_array();
    }

    function __construct($scheduleID = false) {
        /*
         * Constructor:
         * if specific schedule
         *  get scheduleRow
         *  set all schedule columns
         *  set locationROW & semesterROW
         *  set toRemove = false
         * else
         *  set id = 0
         *  set semesterID = activeSemesterID
         *  set toRemove = false
         */
        $this->instance = & get_instance();
        if ((isset($scheduleID)) && ($scheduleID != 0)){
            $this->scheduleROW = $this->getScheduleROW($scheduleID);
            $this->id = $this->scheduleROW['id'];
            $this->startTime = $this->scheduleROW['startTime'];
            $this->endTime = $this->scheduleROW['endTime'];
            $this->dayOfWeek = $this->scheduleROW['dayOfWeek'];
            $this->semesterID = $this->scheduleROW['Semester_id'];
            $this->timestamp = $this->scheduleROW['timeStamp'];
            $this->caID = $this->scheduleROW['caID'];
            $this->scheduledDate = $this->scheduleROW['scheduledDate'];
            $this->locationID = $this->scheduleROW['scheduledLocation_id'];
            $this->isRecursive = $this->scheduleROW['isRecursive'];
            $this->recursiveEndDate = $this->scheduleROW['recursiveEndDate'];
            $this->listView = $this->scheduleROW['listView'];
            $this->recursiveID = $this->scheduleROW['recursiveID'];
            $this->updateBy = $this->scheduleROW['updateBy'];
            $this->locationROW = $this->getLocationROW();
            $this->semesterROW = $this->getSemesterROW();
            $this->toRemove = false;
        }else{
            $this->id = 0;
            $this->semesterID = schedule::getActiveSemesterID();
            $this->toRemove = false;
        }
    }

    private function getScheduleROW($scheduleID){
        $this->instance->db->select('id, startTime, endTime,dayOfWeek, caID, scheduledDate, timeStamp, Semester_id, scheduledLocation_id, isRecursive, recursiveEndDate, listView, recursiveID, updateBy');
        $this->instance->db->where('id', $scheduleID);
        $this->instance->db->limit(1);
        $query = $this->instance->db->get('ca_schedules.ScheduledShifts');
        return $query->row_array();
    }
    private function getLocationROW(){
        $this->instance->db->select('id, locationText, locationIMG');
        $this->instance->db->where('id', $this->locationID);
        $this->instance->db->limit(1);
        $query = $this->instance->db->get('ca_schedules.scheduledLocation');
        return $query->row_array();
    }
    private function getSemesterROW(){
        $this->instance->db->select('id, desc, calendarLink, startDate, endDate');
        $this->instance->db->where('id', $this->semesterID);
        $this->instance->db->limit(1);
        $query = $this->instance->db->get('ca_schedules.Semester');
        return $query->row_array();
    }

    public function getScheduleID(){
        return $this->id;
    }
    public function getStartTime(){
        return $this->startTime;
    }
    public function getEndTIme(){
        return $this->endTime;
    }
    public function getDayOfWeek(){
        //return dayOfWeek as int for this instantiated obj
        return $this->dayOfWeek;
    }
    public function getSemesterDesc(){
        return $this->semesterROW['desc'];
    }
    public function getSemesterID(){
        return $this->semesterROW['id'];
    }
    public function getTimestamp(){
        return $this->timestamp;
    }
    public function getScheduledDate(){
        return $this->scheduledDate;
    }

    public function getScheduledShift(){
        return $this->scheduleROW;
    }

    public function getCA_Id(){
        return $this->caID;
    }

    public function getSemesterByID($semesterID){
        $this->instance->db->select('id, desc, calendarLink, startDate, endDate');
        $this->instance->db->where('id', $semesterID);
        $this->instance->db->limit(1);
        $query = $this->instance->db->get('ca_schedules.Semester');
        return $query->row_array();

    }

    public function substituteCA($caID, $recursiveSubstitute = false){
        $this->caID = $caID;
        if ($recursiveSubstitute) {
            $updateArr = array(
                'caID' => $this->caID,
            );
            $this->instance->db->where('id', $this->id);
            $this->instance->db->update('ca_schedules.ScheduledShifts', $updateArr);

            if ($this->listView == 1) {
                $recursiveSubstituteUpdates = array(
                    'caID' => $this->caID,
                );
                $this->instance->db->where('recursiveID', $this->id);
                $this->instance->db->update('ca_schedules.ScheduledShifts', $recursiveSubstituteUpdates);
            }
        } else {
            $updateArr = array(
                'caID' => $this->caID,
                'isRecursive' => 'off',
                'listView' => 1
            );
            $this->instance->db->where('id', $this->id);
            $this->instance->db->update('ca_schedules.ScheduledShifts', $updateArr);


            if ($this->isRecursive == 'on' && $this->recursiveID == 0) {
                //set next listView = 0 to listView = 1
                //get id of the shift created by recursive
                //update that records with listView and recursiveID value

                $this->instance->db->select('*');
                $this->instance->db->where('recursiveID', $this->id);
                $this->instance->db->order_by('id asc');
                $this->instance->db->limit(1);
                $query = $this->instance->db->get('ca_schedules.ScheduledShifts');
                $nextShiftID = $query->row_array()['id'];


                $updateNextShift = array(
                    'listView' => 1,
                    'recursiveID' => 0
                );
                $this->instance->db->where('id', $nextShiftID);
                $this->instance->db->update('ca_schedules.ScheduledShifts', $updateNextShift);


                $updateRecursiveShifts = array(
                    'recursiveID' => $nextShiftID
                );
                $this->instance->db->where('recursiveID', $this->id);
                $this->instance->db->update('ca_schedules.ScheduledShifts', $updateRecursiveShifts);
            }
        }
    }
    public function substituteDates($dateToSubstitute, $substitutedDate){
        //get day of week for both date to substitute and substitute date
        //get all scheduled shifts for dateToSubstitute
        //make sure no scheduled shifts are worked
        //
        //db update all scheduled shifts for date to substitute on scheduled date to "substituted"
        //get all scheduled shifts for substitutedDate
        //unset id, modify scheduled date and insert to db
    }

    public function getLocationID(){
        return $this->locationROW['id'];
    }
    public function getLocationText(){
        return $this->locationROW['locationText'];
    }
    public function getLocationIMG(){
        return "<img class='has-tip' title='".$this->locationROW['locationText']."' src='/LibServices/assets/ess_assets/img/".$this->locationROW['locationIMG']."'>";
    }
    public function getRecursiveEndDate(){
        return $this->recursiveEndDate;
    }
    public function ifRecursive(){
        /*
         * Returns boolean if this schedule shift is created recursive
         */
        if ($this->scheduleROW['isRecursive'] == "on"){
            return true;
        }else{
            return false;
        }
    }

    public function getIsRecursive(){
        return $this->isRecursive;
    }

    static function getDateOfDayInWeek($specifiedDate = false) {
        /*
         * Returns an array with dates of the week specified from specified date
         * index from 0 - 6; Sun to Sat
         */
        $dates = array();
        for ($i = DayInWeek::Sunday; $i <= DayInWeek::Saturday; $i++) {
            $curTime = date('Y-m-d');
            if ($specifiedDate != false) {
                $curTime = date('Y-m-d',strtotime($specifiedDate)); //ex. $specifiedDate = '2016-07-20';
            }
            if ($i < date('w', strtotime($curTime))) {
                $diff = date('w', strtotime($curTime)) - $i;
                $dates[$i] = date('Y-m-d', strtotime($curTime."-".$diff." days"));
            } else {
                $diff = date('w', strtotime($curTime)) - $i;
                $dates[$i] = date('Y-m-d', strtotime($curTime."-".$diff." days"));
            }
        }
        return $dates;
    }
    static function getSemesters(){
        $me = & get_instance();
        $me->db->select('id, desc, calendarLink, startDate, endDate');
        $query = $me->db->get('ca_schedules.Semester');
        return $query->result_array();
    }

    public function setScheduleShift($scheduledShift) {
        /*
         * This updates instance of this object of type schedule
         *
         */

        if (strtotime($scheduledShift['endTime']) >= strtotime($scheduledShift['startTime'])) {
            $this->startTime = $scheduledShift['startTime'];
            $this->endTime = $scheduledShift['endTime'];
            $this->dayOfWeek = $scheduledShift['dayOfWeek'];
            $this->semesterID = $scheduledShift['Semester_id'];
            $this->caID = $scheduledShift['caID'];
            $this->scheduledDate = $scheduledShift['scheduledDate'];
            $this->locationID = $scheduledShift['scheduledLocation_id'];
            $this->isRecursive = $scheduledShift['isRecursive'];
            $this->recursiveEndDate = $scheduledShift['recursiveEndDate'];
            $this->updateBy = $scheduledShift['updateBy'];
            return 1;
        }else{
            return 0;
        }
    }

    private function insert() {
        /*
         * This methods insert schedule shifts into db ScheduledShifts table via this object
         * Recursive method if isRecursive is set
         */

        //convert obj to row array
        $row = array(
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
            'dayOfWeek' => $this->dayOfWeek,
            'caID' => $this->caID,
            'Semester_id' => $this->semesterID,
            'scheduledDate' => $this->scheduledDate,
            'scheduledLocation_id' => $this->locationID,
            'isRecursive' => $this->isRecursive,
            'recursiveEndDate' => $this->recursiveEndDate,
            'listView' => $this->listView,
            'recursiveID' => $this->recursiveID,
            'updateBy' => $this->updateBy
        );
        //insert row
        $this->instance->db->insert('ca_schedules.ScheduledShifts', $row);

        if ($this->listView == 1){
            /*
             * If this schedule shift is user entered
             * NOTE: Rows created by recursive flag always have listView = 0
             *
             * This also sets all the recursiveID for recursive shifts via recursive insert of this obj till recursiveEndDate
             */
            $this->recursiveID = $this->instance->db->insert_id();
        }

        $this->listView = 1;
        if (($this->isRecursive == "on") && (strtotime($this->scheduledDate) <= strtotime($this->recursiveEndDate. '-7days'))) {
            /*
             * If schedule shift is recursive and scheduledDate has not past recursiveEndDate
             *  set listView = 0
             *  scheduledDate = generated date
             *  calls recursive insert
             */
            $nextScheduledDate = date('Y-m-d', strtotime($this->getScheduledDate(). '+7days'));
            $this->listView = 0;
            $this->scheduledDate = $nextScheduledDate;
            $this->insert();
        }


    }
    private function modify() {
        /*
         * private update method
         */
        $scheduledArray = $this->toArray();
        if ($this->toRemove == true) {
            /*
             * if this obj is set to be removed
             *  remove from db
             */
            return $this->remove();
        } else {
            /*
             * This updates ScheduledShifts db table via this obj
             *
             * delete from ScheduledShifts all recursiveID = this.id beyond today's date
             *
             * If this obj isRecursive
             *      set recursiveEndDate
             *      set recursiveID
             *      set listView = 0
             *      set scheduledDate
             *      call recursive obj this.insert
             */
            $this->instance->db->where('id', $scheduledArray['id']);
            $this->instance->db->update('ca_schedules.ScheduledShifts', $scheduledArray);

            //if not recursive delete all recursives
            //else delete all recursives in the future
            if ($this->isRecursive == 'on') {
                $this->instance->db->where('recursiveID', $scheduledArray['id']);
                $this->instance->db->where('Date(`scheduledDate`) >=', date('Y-m-d'));
                $this->instance->db->delete('ca_schedules.ScheduledShifts');

                $this->instance->db->where('recursiveID', $scheduledArray['id']);
                $this->instance->db->where('Date(`scheduledDate`) >=', $scheduledArray['recursiveEndDate']);
                $this->instance->db->delete('ca_schedules.ScheduledShifts');
            }else{
                $this->instance->db->where('recursiveID', $scheduledArray['id']);
                $this->instance->db->delete('ca_schedules.ScheduledShifts');
            }

            if ((is_numeric($this->recursiveID)) && ($this->recursiveID != 0)) {
                $this->instance->db->where('recursiveID', $this->recursiveID);
                $this->instance->db->where('Date(`scheduledDate`) >=', date('Y-m-d'));
                $this->instance->db->delete('ca_schedules.ScheduledShifts');
            }

            if (date($this->recursiveEndDate) > date('Y-m-d')) {
                if (($this->isRecursive == "on")) {
                    $this->recursiveEndDate = $scheduledArray['recursiveEndDate'];
                    $this->recursiveID = $scheduledArray['id'];
                    $this->listView = 0;
                    $this->scheduledDate = date('Y-m-d', strtotime($scheduledArray['scheduledDate'] . '+7days'));
                    
                    $this->insert();
                }
            }
        }
    }

    public function update() {
        if ($this->id == 0) {
            $this->insert();
        }else {
            return $this->modify();
        }
    }

    public function updateInstance(){
        /*
         * This method is a quick update on this obj ONLY if obj is NOT set to remove
         * Regardless of recursive
         */
        if ($this->toRemove == false) {
            $this->instance->db->where('id', $this->id);
            $this->instance->db->update('ca_schedules.ScheduledShifts', $this->toArray());
        }else{
            $this->removeInstance();
        }
    }

    public function removeInstance(){
        if ($this->toRemove == true) {
            if ($this->isRecursive && $this->listView == 1){
                $this->instance->db->select('*');
                $this->instance->db->where('recursiveID', $this->id);
                $this->instance->db->order_by('scheduledDate asc');
                $this->instance->db->limit(1);

                $query = $this->instance->db->get('ca_schedules.ScheduledShifts');
                $row = $query->row_array();
                if ($query->num_rows() > 0){
                    $nextRowUpdate = array(
                        'listView' => 1
                    );
                    $this->instance->db->where('id', $row['id']);
                    $this->instance->db->update('ca_schedules.ScheduledShifts', $nextRowUpdate);

                    $recursiveShifts = array(
                        'recursiveID' => $row['id']
                    );

                    $this->instance->db->where('recursiveID', $this->id);
                    $this->instance->db->update('ca_schedules.ScheduledShifts', $recursiveShifts);

                    //update listView = 1 for next shift
                    //update all recursiveShift_id with next shift id
                }

            }

            $this->instance->db->where('id', $this->id);
            $this->instance->db->delete('ca_schedules.ScheduledShifts');
        }
    }

    public function toArray() {
        /*
         * Convert this obj to array
         */
        $thisSchedule = array(
            'id' => $this->id,
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
            'dayOfWeek' => $this->dayOfWeek,
            'caID' => $this->caID,
            'scheduledDate' => $this->scheduledDate,
            'Semester_id' => $this->semesterID,
            'scheduledLocation_id' => $this->locationID,
            'isRecursive' => $this->isRecursive,
            'recursiveEndDate' => $this->recursiveEndDate,
            'updateBy' => $this->updateBy
        );
        return $thisSchedule;
    }

    private function remove(){
        /*
         * This should only be performed on obj with listView = 1;
         *
         * If this specific this.id has NOT been worked
         *      delete where ScheduledShifts.id = this.id
         *
         * Regardless of recursive
         * delete where date beyong today and ScheduledShifts.recursiveID = this.id
         *
         * ATM: no error or success message handling
         */

        $db_debug = $this->instance->db->db_debug;
        $this->instance->db->db_debug = false;

        $this->instance->db->trans_start(); //Queries will be rolled back if set to true, useful for testing.

        $this->instance->db->select("*");
        $this->instance->db->where('ScheduledShifts_id', $this->id);
        $workedShift = $this->instance->db->get('ca_schedules.Shifts');

        //update all recursiveEndDates
        $arr = array(
            'recursiveEndDate' => date('Y-m-d')
        );

        if (strtotime($this->scheduledDate) >= strtotime(date('Y-m-d'))){
            $this->instance->db->where('id', $this->id);
            $this->instance->db->delete('ca_schedules.ScheduledShifts');
        }
        $this->instance->db->where('id', $this->id);
        $this->instance->db->update('ca_schedules.ScheduledShifts',$arr);

        $this->instance->db->where('recursiveID', $this->id);
        $this->instance->db->update('ca_schedules.ScheduledShifts',$arr);

        //if shift has not been worked and is recursive listing then change recursiveEndDate;
        //else delete record not worked
        if (($this->listView == 1 && $this->isRecursive == 'off' && $workedShift->num_rows() == 0) || ($this->listView == 0 && $workedShift->num_rows() == 0)) {
            $this->instance->db->where('id', $this->id);
            $this->instance->db->delete('ca_schedules.ScheduledShifts');
        }

        //if is recursive, delete all future occurences
        //else delete all recursive occurances
        if ($this->isRecursive == 'off') {
            $this->instance->db->where('recursiveID', $this->id);
            $this->instance->db->delete('ca_schedules.ScheduledShifts');
        } else {
            $this->instance->db->where('recursiveID', $this->id);
            $this->instance->db->where('Date(`scheduledDate`) >=', date('Y-m-d'));
            $this->instance->db->delete('ca_schedules.ScheduledShifts');
        }

        $this->instance->db->trans_complete(); //Query will be rolled back if fail

        if ($this->instance->db->trans_status() === false) {
            $trans_status = 0;
        } else {
            $trans_status = 1;
        }

        $this->instance->db->db_debug = $db_debug;

        return $trans_status;

    }

    private function setSemester($semesterID){
        //this method sets the semesterID and ROW for this particular object
        $this->semesterID = $semesterID;
        $this->semesterROW = $this->getSemesterROW();
    }

    static function addSemester($semesterRowData) {
        $me = & get_instance();
        $row = array(
            'desc' => $semesterRowData['desc'],
            'calendarLink' => $semesterRowData['calendarLink'],
            'startDate' => $semesterRowData['startDate'],
            'endDate' => $semesterRowData['endDate']
        );
        $me->db->insert('ca_schedules.Semester', $row);
    }

    static function editSemester($semesterRowData) {
//        print_r($semesterRowData);
        $me = & get_instance();
        $me->db->where('id', $semesterRowData['id']);
        $me->db->update('ca_schedules.Semester', $semesterRowData);
    }

    static function scheduleEmployeePage($caID){
        /*
         * Returns a html view of all scheduled shifts for the week of specified date
         *
         * ATM: not being used
         */
        server::require_login();
        $me = & get_instance();
        $returnStr = "";
        $data['caID'] = $caID;
        $curUser = new webuser();
        if ($curUser->hasPrivilege() || ($data['caID'] == $curUser->getUID())) {
            $data['linkUrl'] = "/timesheet/viewEditSchedule/";
            $data['linkText'] = webuser::view($curUser->getUID(), "Edit");
            $dbQuery = "call ca_schedules.scheduleEmployeeTable(" . schedule::getActiveSemesterID() . "," . $data['caID'] . ");";
            $qry_data = summary::query($dbQuery);
            $tbl_data = array();
            $totalHours = 0;
            $totalMins = 0;
            for ($j = 0; $j < sizeof($qry_data); $j++) {

                //generate new array with only the needed columns
                $schedule = new schedule($qry_data[$j]['id']);
                if($qry_data[$j]['isRecursive']=='on'){
                    $dayOfWeek = "<i>Every ".date("D", strtotime($qry_data[$j]['scheduledDate']))." till ".date("m-d", strtotime($qry_data[$j]['recursiveEndDate']))."</i>";

                    //get total hours scheduled, per week.
                    $startTime = new DateTime($qry_data[$j]['startTime']);
                    $total = $startTime->diff(new DateTime($qry_data[$j]['endTime']));
                    $totalHours += $total->h;
                    $totalMins +=  $total->i;
                }else{
                    $dayOfWeek = date("l", strtotime($qry_data[$j]['scheduledDate']));
                }
                $tbl_data[$j]['id'] = $qry_data[$j]['id'];
                $tbl_data[$j]['scheduledDate'] = date('m/d/y', strtotime($qry_data[$j]['scheduledDate']));
                $tbl_data[$j]['dayOfWeek'] = $dayOfWeek;
                $tbl_data[$j]['scheduledLocation_id'] = $schedule->getLocationIMG();
                $tbl_data[$j]['startTime'] = date("H:i", strtotime($qry_data[$j]['startTime']));
                $tbl_data[$j]['endTime'] = date("H:i", strtotime($qry_data[$j]['endTime']));
            }

            $data['title'] = "Schedule Shifts";
            $data['tblData'] = $tbl_data;

            $totalHours += $totalMins / 60;
            $totalMins %= 60;
            $data['scheduledTotal'] = (int) $totalHours . ":" . sprintf("%02d", (int) $totalMins);

            $returnStr = $me->load->view('timesheet/scheduleEmployee', $data, true);
        } else {
            $returnStr = "<center>You do not have enought privilege to view this Page!<br>".anchor('auth/login', 'LOGIN')."</center>";
        }
        return $returnStr;
    }

    static function setActiveSemester($semesterID){
        //this method sets semesterID into session variable
        //this method should be called everytime user switches to a different semester
        //this method should probably also set the default semester!
        //this method is also open for additional session variables
        $me = & get_instance();
        $newData = array(
            'semesterID' => $semesterID,
        );
        $me->session->set_userdata($newData);
    }

    static function defaultActiveSemester() {
        /*
         * Tries to setActiveSemester via looping through semester date ranges in db
         * if not successful
         *      redirect to Page to set semester manually by user
         */
        $semesters = schedule::getSemesters();
        foreach ($semesters as $semester) {
            if (strtotime(date('Y-m-d')) >= strtotime(date($semester['startDate']))
                    && strtotime(date('Y-m-d')) <= strtotime(date($semester['endDate']))) {
                schedule::setActiveSemester($semester['id']);
            }
        }
        $me = & get_instance();
        if (($me->session->userdata('semesterID')=="")
                ||($me->session->userdata('semesterID')==null)) {
            //set max(id) semester table; set most recent semester as default
            $maxid = 0;
            $row = $me->db->query('SELECT MAX(id) AS `maxid` FROM ca_schedules.Semester')->row();
            if ($row) {
                $maxid = $row->maxid;
            }
            schedule::setActiveSemester($maxid);
        }
    }

    static function setNavigatingUrls($reset = false) {
        $me = & get_instance();
        if (is_null($me->session->userdata('navigatingUrls')) || $reset == true) {
            $newData = array(
                'navigatingUrls' => array()
            );
            $me->session->set_userdata($newData);
        }
    }

    static function setActiveDates($startDate, $endDate = false){
        $me = & get_instance();
        if ($endDate == false){
            if ($me->session->userdata('rangeEnd')){
                $endDate = $me->session->userdata('rangeEnd');
            }else{
                $endDate = $startDate;
            }
        }
        $newData = array(
            'rangeStart' => $startDate,
            'rangeEnd' => $endDate
        );
        $me->session->set_userdata($newData);
    }

    static function getActiveDates(){
        $me = & get_instance();
        $rangeStart = ""; $rangeEnd = "";

        if ($me->session->userdata('rangeStart')){
            $rangeStart = $me->session->userdata('rangeStart');
        }else{
            $rangeStart = date('Y-m-d');
        }

        if ($me->session->userdata('rangeEnd')){
            $rangeEnd = $me->session->userdata('rangeEnd');
        }else{
            $rangeEnd = $rangeStart;
        }

        $range = array(
            'rangeStart' => $rangeStart,
            'rangeEnd' => $rangeEnd
        );
        return $range;
    }

    static function pushNavigatingUrl($url){

        $me = & get_instance();
        $urls = schedule::getNavigatingUrls();

        if (!is_array($urls)){
            schedule::setNavigatingUrls(TRUE);
            $urls = schedule::getNavigatingUrls();
        }

        array_push($urls, $url);
        $newData = array(
            'navigatingUrls' => $urls
        );
        $me->session->set_userdata($newData);
    }

    static function popNavigatingUrl(){
        $me = & get_instance();
        $urls = schedule::getNavigatingUrls();
        $url = array_pop($urls);
        $newData = array(
            'navigatingUrls' => $urls
        );
        $me->session->set_userdata($newData);
        return $url;
    }

    static function getNavigatingUrls(){
        $me = & get_instance();
        return $me->session->userdata('navigatingUrls');
    }

    static function getActiveSemesterID(){
        /*
         * Returns activeSemesterID
         *  if not found
         *      call defaultActiveSemester
         *      if fails
         *          redirect to page for user to manually set semester
         */
        $me = & get_instance();
        if (($me->session->userdata('semesterID')=="")
                ||($me->session->userdata('semesterID')==null)){
            schedule::defaultActiveSemester();
            return $me->session->userdata('semesterID');
        }else{
            return $me->session->userdata('semesterID');
        }
    }

    static function getActiveSemesterROW(){
        $me = & get_instance();
        $me->db->select('id, desc, calendarLink, startDate, endDate');
        $me->db->where('id', schedule::getActiveSemesterID());
        $me->db->limit(1);
        $query = $me->db->get('ca_schedules.Semester');
        return $query->row_array();
    }

    public function setToRemove(){
        /*
         * set recursiveEndDate here
         */
        $this->toRemove = true;
    }

    static function getCA_Name($caID){
        $ca = new webuser($caID);
        return $ca->getUsername();
    }

    static function addWorkedShift($shiftData, $tempShiftScheduleEndTime = false){
        /*
         * Static Method with parameter array
         *
         * This method is needed for a swiftly insert of new worked shifts by Supervisor
         *
         * All Scheduled shifts can be edited and marked as worked swiftly.
         * This is meant for brand new shifts that are not scheduled,
         * this method will schedule the shift and mark it as worked at the same time, & with hours accumulated to the employee
         *
         * This method inserts to ScheduledShifts & Shifts
         *
         * Returns auto-increment id for shift
         */

        $scheduleEndTime = "";
        $hasScheduledFlag = false;
        $conflictShiftStartTime = "";

        if ($tempShiftScheduleEndTime == false) {
            $scheduleEndTime = $shiftData['endTime'];
        } else {
            $scheduleEndTime = $tempShiftScheduleEndTime;
            $scheduledShifts = schedule::getDaySchdule(strtotime(date('Y-m-d')), $shiftData['caID']);
            foreach ($scheduledShifts as $scheduleShift) {
                $checkTime = $scheduleEndTime;
                if (strtotime($scheduleShift['startTime']) < strtotime($checkTime)) {
                    $hasScheduledFlag = true;
                    $conflictShiftStartTime = $scheduleShift['startTime'];
                    break;
                }
            }
        }

        if ($hasScheduledFlag == false) {
            $me = & get_instance();
            $scheduledRow = array(
                'startTime' => shift::QuarterTime($shiftData['startTime']),
                'endTime' => $scheduleEndTime,
                'dayOfWeek' => date('w', strtotime($shiftData['scheduledDate'])),
                'caID' => $shiftData['caID'],
                'Semester_id' => schedule::getActiveSemesterID(),
                'scheduledDate' => $shiftData['scheduledDate'],
                'scheduledLocation_id' => $shiftData['scheduledLocation_id'],
                'isRecursive' => "off", //this will always be non recursive
                'recursiveEndDate' => schedule::getActiveSemesterROW()['endDate'],
                'listView' => 1, //this will always be user entered
                'recursiveID' => null,
                'updateBy' => $shiftData['updateBy']
            );
            $me->db->insert('ca_schedules.ScheduledShifts', $scheduledRow);


            $shiftEndTime = "";
            $approvedFlag = 1;
            if ($tempShiftScheduleEndTime == false) {
                $shiftEndTime = $shiftData['scheduledDate'] . " " . $scheduledRow['endTime'];
            } else {
                $approvedFlag = 0;
                $shiftEndTime = "*";
            }

            $startTime = shift::QuarterTime($scheduledRow['startTime']); //QuarterTime will return today's date and startTime, must remove today's date to use submitted one.
            $temp = explode(" ", $startTime);
            $shiftStartTime = $shiftData['scheduledDate'] . ' '. $temp[1];

            $scheduledShift_id = $me->db->insert_id();
            $shiftRow = array(
                'startTime' => $shiftStartTime,
                'endTime' => $shiftEndTime,
                'ScheduledShifts_id' => $scheduledShift_id,
                'caID' => $scheduledRow['caID'],
                'note' => $shiftData['note'],
                'approved' => $approvedFlag
            );
            $me->db->insert('ca_schedules.Shifts', $shiftRow);
            $insertedID = $me->db->insert_id();
            $workedShift = new shift($me->db->insert_id());
            $workedShift->updateShift($workedShift->toArray());
            return $insertedID;
        }else{
            return "<b>Overlapping Shift!</b><br><br> This shift must end before ". shift::formatTime($conflictShiftStartTime).". Process cancelled.";
        }

    }

    public function markScheduledWorked($shiftData) {
        /*
         * This method provides a swift transition from scheduled shifts into worked shifts for the scheduled employee
         *
         * This method updates this obj via parameter array with option for
         * scheduledDate, startTime, endTime, and locationID via updateInstance
         *
         * Generates shift row with updated values and this obj
         *
         * Then db->insert into Shifts db table
         *
         * Returns auto-increment shift id from table
         */
        $this->scheduledDate = $shiftData['scheduledDate'];
        $this->startTime = $shiftData['startTime'];
        $this->endTime = $shiftData['endTime'];
        $this->locationID = $shiftData['scheduledLocation_id'];
        $this->updateInstance();

        $supervisor = new webuser();

        $shiftRow = array(
            'startTime' => $this->scheduledDate . " " . $this->startTime,
            'endTime' => $this->scheduledDate . " " . $this->endTime,
            'ScheduledShifts_id' => $this->id,
            'caID' => $this->caID,
            'approvedBy'=>$supervisor->getUID()
        );
        $this->instance->db->insert('ca_schedules.Shifts', $shiftRow);
        $workedShift = new shift($this->instance->db->insert_id());
        $workedShift->updateShift($workedShift->toArray());
        return $this->instance->db->insert_id();
    }

    static function ca_lookup($keyword){
        /*
         * returns result_array on all searchable users
         */
        $me = & get_instance();
        $me->db->select('id, username, email,first_name, last_name, phone');
        $me->db->like('username',$keyword);
        $query = $me->db->get('users');
        return $query->result_array();
    }

    public function setLocation($locationID){
        $this->locationID = $locationID;
    }

    public function ListViewValue(){
        return $this->listView;
    }

    /**
     * Adds new employee with a randomly generated string. 
     * Emails the employee the new barcode.
     * The new barcode can be used to sign in via barcode, or used as password for email/password sign in.
     */
    static function newEmp_byName($firstname, $lastname, $userEmail, $phone = false) {
        $me = & get_instance();
        $newBarcode = random_string('alnum', 6);
        $passwordHash = Authentication::string_get_hash($newBarcode);
        if (webuser::ifEmailExist($userEmail)) {
            return 0;
        } else {
            $newUser = new webuser();
            $newUser->setUserByBarcode($newBarcode);

            while ($newUser->getUID() != 0) {
                $newBarcode = random_string('alnum', 6);
                $newUser->setUserByBarcode($newBarcode);
            }
            $newRecord = array(
                'ip_address' => "na",
                'password' => $passwordHash,
                'created_on' => strtotime(date('Y-m-d')),
                'barcode' => $newBarcode,
                'username' => $lastname . ',' . $firstname,
                'first_name' => $firstname,
                'last_name' => $lastname,
                'active' => 1,
                'email' => $userEmail,
                'phone' => $phone
            );
            $me->db->insert('libservices.users', $newRecord);
            $insertedID = $me->db->insert_id();

            if(is_numeric($insertedID)) {
                $user_group = array(
                    'user_id' => $insertedID,
                    'group_id' => 3
                );

                if( $me->db->insert('libservices.users_groups', $user_group) ) {
                
                $emailData['title'] = "ESS Account Mangement";
                $emailData['titleImgSrc'] = 'C:/wamp64/www/LibServices/assets/ess_assets/img/userIcon.png';
                $emailData['emailBody'] = 'A new account has been created for you.<br><br><b>'.$firstname.' '.$lastname
                        .'</b><br><br>You temporary barcode and password is <br><b>'.$newBarcode
                        .'</b><br><br>Login at http://localhost/LibServices/index.php/auth/login';
    
                $emailData['helpContact'] = $me->load->view('timesheet/emails/supervisorContactInfo', '', true);
                       
                $emailContent = $me->load->view('templates/email/boilerplate', $emailData, true);
                $me->notify->email_user($insertedID, $emailContent, false, "ESS Account Created");
    
                return $insertedID;
                } else {
                    return '';
                }
            } else {
                return '';
            }
            
        }
    }

    static function addAnnouncement($announcement){
        $me = & get_instance();
        $newAnnouncement = array(
            'title' => $announcement['title'],
            'body' => $announcement['body'],
            'uid' => $announcement['uid'],
            'startDate' => $announcement['startDate'],
            'endDate' => $announcement['endDate'],
            'type'=> $announcement['type'],
        );
        $me->db->insert('ca_schedules.announcements', $newAnnouncement);
        return $me->db->insert_id();
    }

    static function addExceptionDate($exceptionRow){
        $me = & get_instance();
        $newExceptionDate = array(
            'onDate' => $exceptionRow['onDate'],
            'swapDate' => $exceptionRow['swapDate'],
            'noWork' => $exceptionRow['noWork'],
            'announcementID' => $exceptionRow['announcementID'],
            'semesterID' => $exceptionRow['semesterID']
        );
        $me->db->insert('ca_schedules.exceptionDates', $newExceptionDate);
        return $me->db->insert_id();
    }

    static function removeExceptionDate($exceptionsID){
        $me = & get_instance();
        $me->db->select('announcementID');
        $me->db->where('id',$exceptionsID);
        $query = $me->db->get("ca_schedules.exceptionDates");
        $announcementID = $query->row_array()['announcementID'];

        $me->db->where('id', $announcementID);
        $me->db->delete('ca_schedules.announcements');

        $me->db->where('id', $exceptionsID);
        $me->db->delete('ca_schedules.exceptionDates');

        $me->db->select('*');
        $me->db->where('id',$exceptionsID);
        $q=  $me->db->get('ca_schedules.exceptionDates');
        if ($q->num_rows() == 0) {
            return 1;
        } else {
            return 0;
        }
    }

    static function removeAnnouncement($announcementID) {
        $me = & get_instance();
        $me->db->where('id', $announcementID);
        $me->db->delete('ca_schedules.announcements');
    }

    static function editAnnouncement($announcement){
        $me = & get_instance();
        $newAnnouncement = array(
            'id' => $announcement['id'],
            'title' => $announcement['title'],
            'body' => $announcement['body'],
            'uid' => $announcement['uid'],
            'startDate' => $announcement['startDate'],
            'endDate' => $announcement['endDate'],
            'type' => $announcement['type'],
        );

        $me->db->where('id', $newAnnouncement['id']);
        $me->db->update('ca_schedules.announcements',$newAnnouncement);
    }

    static function getAnnouncements($specifiedDate = false) {
        $me = & get_instance();
        $me->db->select('*');
        if ($specifiedDate == false) {
            $today = (date('Y-m-d'));
            $me->db->where('startDate <=', $today);
            $me->db->where('endDate >=', $today);
        }else{
            $today = $specifiedDate;
            $me->db->where('startDate <=', $today);
            $me->db->where('endDate >=', $today);
        }
        $me->db->order_by('id desc');
        $q = $me->db->get('ca_schedules.announcements');
        return $q->result_array();
    }

    static function getAnnouncement($announcementID){
        $me = & get_instance();
        $me->db->select('*');
        $me->db->where('id', $announcementID);
        $q = $me->db->get('ca_schedules.announcements');
        return $q->row_array();
    }

    static function getAnnouncementsOnEndDate($specifiedDate = false){
        $me = & get_instance();
        $me->db->select('*');
        if ($specifiedDate == false) {
            $today = (date('Y-m-d'));
            $me->db->where('endDate', $today);
        }else{
            $today = $specifiedDate;
            $me->db->where('endDate', $today);
        }
        $me->db->order_by('id desc');
        $q = $me->db->get('ca_schedules.announcements');
        return $q->result_array();
    }

    static function getAnnouncementsRanges($fromDate, $toDate) {
        $me = & get_instance();
        $me->db->select('*');
        $me->db->where('startDate <=', $toDate);
        $me->db->where('endDate >=', $fromDate);
        $me->db->order_by('id desc');
        $q = $me->db->get('ca_schedules.announcements');
        return $q->result_array();
    }

    static function getExceptionDates($semesterID){
        $me = & get_instance();
        $me->db->select('*');
        $me->db->where('semesterID', $semesterID);
        $q = $me->db->get('ca_schedules.exceptionDates');
        return $q->result_array();
    }

    static function getExceptionDate($exceptionRowID){
        $me = & get_instance();
        $me->db->select('*');
        $me->db->where('id', $exceptionRowID);
        $q = $me->db->get('ca_schedules.exceptionDates');
        return $q->row_array();
    }

    static function getExceptionAnnouncementID($exceptionRowID){
        $me = & get_instance();
        $me->db->select('announcementID');
        $me->db->where('id', $exceptionRowID);
        $q = $me->db->get('ca_schedules.exceptionDates');
        return $q->row_array()['announcementID'];
    }

    static function getDateFilterException($specifiedDate = false, $semesterID = false){
        if ($specifiedDate == false){
            $specifiedDate = date('Y-m-d');
        }
        $returnDate = $specifiedDate;

        if ($semesterID == false){
            $exceptions = schedule::getExceptionDates(schedule::getActiveSemesterID());
        }else{
            $exceptions = schedule::getExceptionDates($semesterID);
        }
        foreach ($exceptions as $exception){
            if ($exception['onDate'] == $specifiedDate){
                $returnDate = $exception['swapDate'];
            }
        }
        return $returnDate;
    }

    function ifSignedIn(){
        $this->instance->db->where('ScheduledShifts_id', $this->id);
        $query = $this->instance->db->get('ca_schedules.Shifts');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
}
