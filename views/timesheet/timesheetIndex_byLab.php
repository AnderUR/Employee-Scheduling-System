<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$lastWeekDate = date_sub(new DateTime(date('Y-m-d', strtotime($week[DayInWeek::Sunday]))), date_interval_create_from_date_string('1 days'));
$nextWeekDate = date_add(new DateTime(date('Y-m-d', strtotime($week[DayInWeek::Saturday]))), date_interval_create_from_date_string('1 days'));

$data['header'] = array();
$data['header'][0] = array(0, 'i');
$data['header'][1] = array(0, 'Area');
$data['header'][2] = array(250, date('D m-d', strtotime($week[DayInWeek::Sunday])));
$data['header'][3] = array(250, date('D m-d', strtotime($week[DayInWeek::Monday])));
$data['header'][4] = array(250, date('D m-d', strtotime($week[DayInWeek::Tuesday])));
$data['header'][5] = array(250, date('D m-d', strtotime($week[DayInWeek::Wednesday])));
$data['header'][6] = array(250, date('D m-d', strtotime($week[DayInWeek::Thursday])));
$data['header'][7] = array(250, date('D m-d', strtotime($week[DayInWeek::Friday])));
$data['header'][8] = array(250, date('D m-d', strtotime($week[DayInWeek::Saturday])));

$data['results'] = $tblData;
$data['revealBoolean'] = 'noReveal';

$thisUser = new webuser();

$tSheetHelp = "";
$tSheetNavHelp = "";
if ($thisUser->is_supervisor()) {
    $tSheetHelp = '<img src="/LibServices/assets/ess_assets/img/helpICO.png" style="display:none;" class="helpMenu" data-what-help="tsheet_table" />';
    $tSheetNavHelp = '<img src="/LibServices/assets/ess_assets/img/helpICO.png" style="display:none;" class="helpMenu" data-what-help="tsheet_upperNav" />';
}
?>
<div class="row">
    <div id="tableMenuWrapperOne" class="medium-6 medium-centered"> <?= $tSheetNavHelp ?>
        <div class="row">
            <div class="large-10 medium-12 small-9 small-centered medium-centered columns">
                <div class="row">

                    <div id="srchWeekContainer" class="large-5 small-4 columns">
                        <input readonly="true" id="srchWeekDate" type="search" value="Show Week Of..."></input>
                    </div>

                    <div id="schSortContainer" class="small-4 columns">
                        <select onchange="if (this.value)
                                                                            window.location.href = this.value">
                            <option value="">Sort By...</option>
                            <option value="<?= site_url('/timesheet/timesheetIndex/'.$week[DayInWeek::Sunday]) ?> ">Employee</option>
                        </select>
                    </div>

                    <div id="schTodayContainer" class="nowrap large-3 small-4 columns">
                      <?= anchor('/timesheet/timesheetLabIndex/'.date('Y-m-d'), 'This Week', 'id="todayButton" class="button"'); ?>
                    </div>

                </div><!-- row -->
            </div><!-- medium-10 -->
        </div><!-- row -->
    </div><!-- tableMenuWrapperOne -->
</div><!-- row -->

<div id="outerContainer">

    <div class="row">
        <div id="tableMenuWrapperTwo" class="medium-6 medium-centered columns">
            <div class="row">

                <div class="large-7 medium-12 small-12 large-centered medium-centered columns">

                    <div id="prevContainer" class="small-1 columns">
                        <a href="<?= site_url('/timesheet/timesheetLabIndex/'. $lastWeekDate->format('Y-m-d')); ?>">
                            <img src="/LibServices/assets/ess_assets/img/arrowLeft.png" />
                        </a>
                    </div>

                    <div class="small-5 columns">
                        <input type="text" id="minDateInterval" name="minDateInterval" value="<?= date('Y-m-d', strtotime($week[DayInWeek::Sunday])) ?>" />
                    </div>

                    <div class="small-5 columns">
                        <input type="text" id="maxDateInterval" name="maxDateInterval" value="<?= date('Y-m-d', strtotime($week[DayInWeek::Saturday])) ?>" />
                    </div>

                    <div id="nextContainer" class="small-1 columns">
                        <a href="<?= site_url('/timesheet/timesheetLabIndex/'.$nextWeekDate->format('Y-m-d')); ?>">
                            <img src="/LibServices/assets/ess_assets/img/arrowRight.png" />
                        </a>
                    </div>

                </div><!-- large-medium-small -->
            </div><!-- row -->
        </div> <!-- tableMenuWrapperTwo -->

        <div class="row">
            <div class="small-12 columns">	<?= $tSheetHelp ?>
                <div class="table-scroll">

<?php $this->load->view('timesheet/tableTemplate', $data); ?>

                </div><!-- table-scroll -->
            </div><!-- small-12 -->
        </div><!-- row -->
    </div><!-- row -->
</div><!-- outterContainer -->