<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$this->load->view('timesheet/header');
$week = schedule::getDateOfDayInWeek();
?>
<?= form_open('timesheet/viewTimesheetEmployeePeriod'); ?>
<?= form_hidden('caID', $caID); ?>
    <div class="row small-centered small-10 medium-6 large-4 columns">
        <input id="fromDate" name="fromDate" value="<?= $week[DayInWeek::Sunday]; ?>" type="text" placeholder="From Date" />
        <input id="endDate" name="endDate" value="<?= $week[DayInWeek::Saturday]; ?>" type="text" placeholder="To Date" />
        <input id="btnSubmit_addShift" type="submit" class="button" value="View Timesheet" />
    </div>
</form>