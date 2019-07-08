<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$ca = new webuser($caID);

$data['header'] = array();
$data['header'][0] = array(0, webuser::view($ca->getUID(), 'Shifts', '#'));
$data['header'][1] = array(0, 'Date');
$data['header'][2] = array(0, 'Day');
$data['header'][3] = array(0, 'Lab');
$data['header'][4] = array(0, 'Start');
$data['header'][5] = array(0, 'End');

$data['results'] = $tblData;
$data['revealBoolean'] = 'revealSchEditForm';

$thisUser = new webuser();
$emplName = ucwords(strtolower($ca->getProperties()['first_name']." ". $ca->getProperties()['last_name']));

$emplSchTableSemester = "";
if ($thisUser->is_supervisor()) {
    $emplSchTableSemester = '<img src="/LibServices/assets/ess_assets/img/helpICO.png" style="display:none;" class="helpMenu" data-what-help="empl_schTable_Semester" />';
}
?>

<div class="tableMenuWrapper boxShadow row">
    <h3 class="shifts_userName"><?= $emplName ?></h3>
     <span id="hoursWorked">Weekly scheduled total hours (only counts recursive shifts): <?= $scheduledTotal; ?></span>
</div>

<div id="emplShiftsOuterContainer" class="boxShadow row"><?= $emplSchTableSemester ?>

    <div class="innerContainer small-12 columns">
        <div class="row">

<?php
$data['postUrl'] = 'timesheet/addSemester';
$data['semesters'] = schedule::getSemesters();
$this->load->view('timesheet/semester', $data);
?>

            <input id="emplID" type="hidden" value="<?= $caID ?>" />
        </div>
        <div class="table-wrapper">

<?php $this->load->view('timesheet/tableTemplate', $data); ?>


        </div><!-- table-wrapper -->
    </div><!-- innerContainer -->

<?= webuser::view(webuser::getLoggedInUid(), $this->load->view('timesheet/addSchedule', "", true)); ?>

</div>

<!--Shift form header for reveal -->

<div class="large reveal" id="revealSchEditForm" aria-labelledby="revealSchEditForm"
     data-reveal data-close-on-click="false">

    <button class="close-button closeForm" data-close
            aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
    <div id="editSchReveal">

    </div>

</div>