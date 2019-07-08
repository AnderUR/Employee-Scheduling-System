<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$lastDate = date_sub(new DateTime($day), date_interval_create_from_date_string('1 days'));
$nextDate = date_add(new DateTime($day), date_interval_create_from_date_string('1 days'));

$data['header'] = array();
$data['header'][0] = array(125, 'Shifts');
$data['header'][1] = array(125, 'Employee');
$data['header'][2] = array(125, 'Status');
$data['header'][3] = array(125, 'Scheduled');
$data['header'][4] = array(125, 'Start');
$data['header'][5] = array(125, 'End');
$data['header'][6] = array(125, 'Area');

$data['results'] = $tblData;

$data['revealBoolean'] = 'revealDaySchEditForm';

$thisUser = new webuser();

$statusHelp = "";
$statusNavHelp = "";
if ($thisUser->is_supervisor()) {
    $statusHelp = '<img src="/LibServices/assets/ess_assets/img/helpICO.png" style="display:none;" class="helpMenu" data-what-help="status_table" />';
    $statusNavHelp = '<img src="/LibServices/assets/ess_assets/img/helpICO.png" style="display:none;" class="helpMenu" data-what-help="status_upperNav" />';
}
?>

<div id="statusMenuWrapperOneContainer" class="row">
    <div id="tableMenuWrapperOne" class="medium-6 medium-centered">
        <div class="row">
            <h3 class="showTime text-center"></h3>
        </div>
    </div>
</div>

<div id="outerContainer">

    <div class="row">
        <div id="tableMenuWrapperTwo" class="medium-9 small-12 medium-centered columns">
            <div class="row"> <?= $statusNavHelp ?>

                <div class="small-12 medium-centered columns">

                    <div id="statusPrevContainer" class="medium-1 small-2 columns">
                        <a href="<?= site_url('/timesheet/statusIndex/'.$lastDate->format('Y-m-d')); ?>">
                            <img src="/LibServices/assets/ess_assets/img/arrowLeft.png" />
                        </a>
                    </div>

                    <div class="large-4 medium-6 small-8 columns">
                        <input readonly="true" id="srchWeekDate" class="dateField centerInputText" type="search" value="<?= date('l Y-m-d', strtotime($day)) ?>" />
                    </div>

                    <div id="nextContainer" class="medium-1 small-2 columns">
                        <a href="<?= site_url('/timesheet/statusIndex/'.$nextDate->format('Y-m-d')); ?>">
                            <img src="/LibServices/assets/ess_assets/img/arrowRight.png" />
                        </a>
                    </div>

                    <div class="medium-3 small-12 small-centered text-center columns">
                      <?= anchor('/timesheet/statusIndex/'. date('Y-m-d'), 'Refresh', 'id="todayButton" class="button"'); ?>
                    </div>

                </div><!-- large-medium-small -->
            </div><!-- row -->
        </div> <!-- tableMenuWrapperTwo -->

        <div class="row">
            <div class="large-9 medium-12 medium-centered columns"><?= $statusHelp ?>
                <div class="table-scroll">

                    <?php $this->load->view('timesheet/tableTemplate', $data); ?>


                </div><!-- table-scroll -->
            </div><!-- small-6 -->
        </div><!-- row -->
    </div><!-- row -->
</div><!-- outterContainer -->

<!--Shift form header for reveal -->

<div class="large reveal" id="revealDaySchEditForm" aria-labelledby="revealDaySchEditForm"
     data-reveal data-close-on-click="false">

    <button class="close-button closeForm" data-close
            aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
    <div id="editDaySchReveal">

    </div>

</div>

<div class="large reveal" id="revealEditSignedInSch" aria-labelledby="revealEditSignedInSch"
     data-reveal data-close-on-click="false">

    <button class="close-button closeForm" data-close
            aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
    <div id="editSignedInSchReveal">

    </div>

</div>