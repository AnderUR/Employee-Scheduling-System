<?php
$schedule = new schedule($scheduleID);
$ca = new webuser($schedule->getCA_Id());
$className;

/**
 * This view uses multiple if statements to filter for the correct form. Either it is the edit schedule form, or the edit schedule signed in form (an empl who has already signed in)
 */
if ($signedInBool == "false") {
    $className = "editFormTitle_schedule";
} else {
    $className = "editFormTitle_schSignedIn";
}

$formEditShift = '<img src="/LibServices/assets/ess_assets/img/helpICO.png" id="editShiftHelp" class="helpMenu" data-what-help="form_editShift" />';
$formEditShiftSignedIn =  '<img src="/LibServices/assets/ess_assets/img/helpICO.png" id="editShiftSignedInHelp" class="helpMenu" data-what-help="form_editShiftSignedIn" />';

$editShiftTxt;

if ($signedInBool == "false") {
    $editShiftTxt = "Edit Shift:";
    echo $formEditShift;
} else {
    $editShiftTxt = "Edit Signed In Shift:";
    echo  $formEditShiftSignedIn;
}
?>

<?php
$attributes = array('data-confirm' => 'Are you sure you want to proceed with this change?');
echo form_open('timesheet/editSchedule', $attributes);
echo form_hidden('id', $scheduleID);
echo form_hidden('caID', $schedule->getCA_Id());
if ($signedInBool == "true") {
    echo (form_hidden('scheduledDate', date('Y-m-d', strtotime($schedule->getScheduledDate()))));
    echo (form_hidden('startTime', date('H:i', strtotime($schedule->getStartTime()))));
}
echo form_hidden('chk_isRecursive', $schedule->getIsRecursive());
echo form_hidden('recursiveEndDate', $schedule->getRecursiveEndDate());
echo form_hidden('updateInstance', 1); //controller will know info regarding recursive updating
?>
<div class="loadActivityICO"><img class="" src="/LibServices/assets/ess_assets/img/activityICO.gif" /></div>
<div id="myTest">

    <?php if ($signedInBool == "false") { ?>

        <div class="row clearfix">
            <div id="daySubstitute" class="editSrchInpt large-4 medium-7 small-12 small-centered large-uncentered columns float-right">
                <label for="substitute">Subsitute</label>
                <input type="text" id="subsitute" placeholder="Find temporary employee" />
            </div>
        </div>

    <?php } ?>

    <div id="editForm" class="row">
        <div class=<?= $className ?>><?= $editShiftTxt ?>
            <span class="shifts_userName"><?= anchor('/timesheet/manageEmployee/' . $schedule->getCA_Id(), $ca->getProperties()['first_name'] . " " . $ca->getProperties()['last_name']); ?></span>
        </div>
        <div class="small-11 columns">
            <br>
            <div class="row">

                <fieldset id="editShiftContainer" class="large-10 medium-7 small-12 small-centered columns shiftFields">
                    <?php if ($signedInBool == "false") { ?>

                        <div class="large-3 columns">
                            <label class="inputLabel" for="dateField">Date</label>
                            <input class="dateField text-center" type="text" name="scheduledDate" value="<?= date('Y-m-d', strtotime($schedule->getScheduledDate())); ?>" />
                        </div>

                        <div class="large-2 columns">
                            <label class="inputLabel" for="startTime">Start</label>
                            <input class="startTime text-center" type="text" name="startTime" value="<?= date('H:i', strtotime($schedule->getStartTime())); ?>" />
                        </div>

                    <?php } else { ?>

                        <div class="large-3 columns">
                            <label class="inputLabel" for="dateField">Date</label>
                            <input class="dateField text-center" type="text" name="scheduledDate" value="<?= date('Y-m-d', strtotime($schedule->getScheduledDate())); ?>" disabled />
                        </div>

                        <div class="large-2 columns">
                            <label class="inputLabel" for="startTime">Start</label>
                            <input class="startTime text-center" type="text" name="startTime" value="<?= date('H:i', strtotime($schedule->getStartTime())); ?>" disabled />
                        </div>

                    <?php } ?>
                    <div class="large-2 columns">
                        <label class="inputLabel" for="endTime">End</label>
                        <input class="endTime text-center" name="endTime" value="<?= date('H:i', strtotime($schedule->getEndTIme())); ?>" type="text" />
                    </div>

                    <div class="large-3 columns">
                        <label class="inputLabel" for="location">Location</label>
                        <select class="location" name="scheduledLocation_id">
                            <?php foreach (shift::getLocations() as $location) : ?>
                                <option value="<?= $location['id']; ?>" <?php
                                    if ($location['id'] == $schedule->getLocationID()) {
                                        echo 'selected="selected"';
                                    }
                                    ?>>
                                    <?= $location['locationText']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="moveSubmit large-2 small-12 columns">
                        <button id="save" class="default button addShift" type="submit" name="submit">Save</button>
                    </div>

                    <?php if ($signedInBool == "false") { ?>

                        <div id="editShiftButtonContainer" class="small-12 columns">
                            <a id="markTimesheet" class="default button addShift nowrap">Set in timesheet</a>
                            <button class="delete default button" type="submit" name="delete">Remove</button>
                        </div>

                    <?php } ?>

                </fieldset>
            </div>
        </div>
    </div>
</div>
</form>