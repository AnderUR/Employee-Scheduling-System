<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$lastWeekDate = date_sub(new DateTime(date('Y-m-d', strtotime($week[DayInWeek::Sunday]))), date_interval_create_from_date_string('1 days'));
$nextWeekDate = date_add(new DateTime(date('Y-m-d', strtotime($periodEndWeek[DayInWeek::Saturday]))), date_interval_create_from_date_string('1 days'));

$thisUser = new webuser();

$emplWShiftsUpperNav = "";
$emplWShiftsTable = "";
if ($thisUser->is_supervisor()) {
    $emplWShiftsUpperNav = '<img src="/LibServices/assets/ess_assets/img/helpICO.png" style="display:none;" class="helpMenu" data-what-help="empl_wShifts_upperNav" />';
    $emplWShiftsTable = '<img src="/LibServices/assets/ess_assets/img/helpICO.png" style="display:none;" class="helpMenu" data-what-help="empl_wShifts_table" />';
}

$ca = new webuser($caID);

$data['header'] = array();
$data['header'][0] = array(0, webuser::view($ca->getUID(), 'Shifts', '#'));
$data['header'][1] = array(0, 'Date');
$data['header'][2] = array(0, 'Lab');
$data['header'][3] = array(0, 'Status');
$data['header'][4] = array(0, 'Time In-Out');
$data['header'][5] = array(0, 'In-stamp');
$data['header'][6] = array(0, 'Out-stamp');
$data['header'][7] = array(0, 'Total');
$data['header'][8] = array(0, 'Temp');

//print_r(Shift::timesheetEmployeePage(128, '2018-06-03', '2018-06-07') );

$data['results'] = $tblData;

$data['revealBoolean'] = 'revealTSheetForm';

$emplName = ucwords(strtolower($ca->getProperties()['first_name'] . " " . $ca->getProperties()['last_name']));

?>

<div class="tableMenuWrapper boxShadow row">
    <h3 class="shifts_userName"><?= $emplName . ' ' . $emplWShiftsUpperNav; ?></h3>
    <span id="hoursWorked">Total hours worked this period: <?= $totalWorkedHrs; ?></span>
</div>


<div id="emplWorkedShiftsOuterContainer" class="boxShadow row">

    <?= form_open('timesheet/viewTimesheetEmployeePeriod'); ?>
    <?= form_hidden('caID', $caID); ?>


    <div id="emplWorkedShiftsInnerContainer" class="row">

        <div class="large-7 medium-9 small-12 medium-centered columns">

            <div class="small-4 columns">
                <input readonly="true" class="dateField text-center" type="text" name="fromDate" value="<?= date('Y-m-d', strtotime($week[DayInWeek::Sunday])) ?>" />
            </div>

            <div class="small-4 columns">
                <input readonly="true" class="dateField text-center" type="text" name="endDate" value="<?= date('Y-m-d', strtotime($periodEndWeek[DayInWeek::Saturday])) ?>" />
            </div>


            <div class="small-4 columns">
                <button type="submit" class="nowrap addShift default button buttonTopMargin">View period</button>
            </div>

        </div>


    </div>
    </form>

    <div class="innerContainer small-12 columns"><?= $emplWShiftsTable ?>
        <div class="table-wrapper">
            <?php $this->load->view('timesheet/tableTemplate', $data); ?>
        </div>
    </div>

    <?= webuser::view(webuser::getLoggedInUid(), $this->load->view('timesheet/addTimesheet', "", true)); ?>

</div><!-- emplWorkedShiftsOuterContainer -->


<!--timesheet form header for reveal -->

<div class="small reveal" id="revealTSheetForm" aria-labelledby="revealTSheetForm" data-reveal data-close-on-click="false">

    <button class="close-button closeForm" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
    <div id="editTSheetReveal">

    </div>

</div>
