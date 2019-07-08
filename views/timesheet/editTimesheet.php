<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<?php

$shift = new shift($shiftID);
$hasSignedOut = $shift->getEndTime();
$ca = new webuser($shift->getCA_Id());
$scheduleID = $shift->getScheduleID();
$schedule = new schedule($scheduleID);

$formEditTimesheet = '<img src="/LibServices/assets/ess_assets/img/helpICO.png" class="helpMenu" data-what-help="form_editTimesheet" />';
echo $formEditTimesheet;
?>

<?php
$attributes = array('data-confirm' => 'Are you sure you want to proceed with this change?');
echo form_open('timesheet/editTimesheet', $attributes);
echo form_hidden('id', $shiftID);
echo form_hidden('caID', $shift->getCA_Id());
echo form_hidden('scheduleID', $shift->getScheduleID());
?>

<div id="editWorkedForm" class="row">
    <div class="editFormTitle_timesheet">Edit Timesheet:
        <span class="shifts_userName">
            <?= anchor('/timesheet/manageEmployee/' . $shift->getCA_Id(), $ca->getProperties()['first_name'] . " " . $ca->getProperties()['last_name']); ?>
        </span>
    </div>
    <br><br>

    <?php if (isset($isStatusIndex) && $hasSignedOut == '*') { ?>
        <a class="revealEditSignedInSch" id="schShiftRefTxt" data-open="revealEditSignedInSch">
            <?= "Shift: " . (date('Y-m-d', strtotime($schedule->getScheduledDate())) . " " . date('H:i', strtotime($schedule->getStartTIme())) . "-" . date('H:i', strtotime($schedule->getEndTIme())) . " " . $schedule->getLocationText()); ?>
        </a>
    <?php } ?>
    <div class="small-12 small-centered columns">

        <fieldset class="large-12 medium-8 small-12 small-centered columns shiftFields editWorkedShifts">

            <div class="large-3 columns">
                <label class="inputLabel" for="dateField">Date</label>
                <input readonly="true" class="dateField text-center" type="text" name="scheduledDate" value="<?= date('Y-m-d', strtotime($shift->getScheduledDate())); ?>" />
            </div>

            <div class="large-3 columns">
                <label class="inputLabel" for="startTime">Start</label>
                <input readonly="true" class="startTime text-center" type="text" name="startTime" value="<?= shift::formatTime($shift->getStartTime()); ?>" />
            </div>

            <div class="large-3 columns">
                <label class="inputLabel" for="endTime">End</label>
                <input readonly="true" class="endTime text-center" name="endTime" value="<?= shift::formatTime($shift->getEndTime()); ?>" type="text" />
            </div>

            <div class="large-3 columns">
                <label class="inputLabel" for="location">Location</label>
                <select class="location" name="scheduledLocation_id">
                    <?php foreach (shift::getLocations() as $location) : ?>
                        <option value="<?= $location['id']; ?>" <?php
                            if ($location['id'] == $shift->getLocationID()) {
                                echo 'selected="selected"';
                            }
                        ?>>
                            <?= $location['locationText']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="small-12 columns">
                <textarea id="shiftNote" name="note" placeholder="Add notes"><?= $shift->getNote(); ?></textarea>
            </div>

        </fieldset>

        <div class="columns">
            <div class="small-2 columns">
                <button class="delete default button" type="submit" name="delete">Remove</button>
            </div>
            <div class="small-2 clearfix columns">
                <button id="save" class="default button float-right addShift" type="submit" name="submit">Save</button>
            </div>

        </div>

    </div>
</div>
</form>

</div>