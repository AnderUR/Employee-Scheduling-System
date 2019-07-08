<?php
$schedule = new schedule($scheduleID);
$ca = new webuser($schedule->getCA_Id());

$urls = schedule::getNavigatingUrls();
$index = sizeof($urls) - 1;
$url = schedule::getNavigatingUrls()[$index];

if (strpos($url, 'statusIndex')) {
    $formSetInTimesheetStatus = '<img src="/LibServices/assets/ess_assets/img/helpICO.png" class="helpMenu" data-what-help="form_setInTSheetStatus" />';
    echo $formSetInTimesheetStatus;
} else {
    $formSetInTsheetWShift = '<img src="/LibServices/assets/ess_assets/img/helpICO.png" class="helpMenu" data-what-help="form_setInTSheetWShift" />';
    echo $formSetInTsheetWShift;
}
?>

<?php
$attributes = array('data-confirm' => 'Are you sure you want to proceed with this change?');
echo form_open('timesheet/editSchedule', $attributes);
echo form_hidden('id', $scheduleID);
echo form_hidden('caID', $schedule->getCA_Id());
echo form_hidden('scheduleID', $scheduleID); //necessary for the back button for set in timesheet to edit schedule
?>

<div class="loadActivityICO"><img class="" src="/LibServices/assets/ess_assets/img/activityICO.gif" /></div>

<div id="editForm"  class="row">
    <div class="editFormTitle_setInTimesheet">Set in timesheet: <span class="shifts_userName"><?= $ca->getProperties()['first_name'] . " " . $ca->getProperties()['last_name'] ?></span></div>
    <div class="small-12 small-centered columns">
        <br>
        <fieldset id="editShiftContainer" class="large-12 medium-12 small-12 small-centered columns shiftFields">
            <div class="large-3 columns">
                <label class="inputLabel" for="dateField">Date</label>
                <input class="dateField text-center" type="text" name="scheduledDate" value="<?= date('Y-m-d', strtotime($schedule->getScheduledDate())); ?>" />
            </div>

            <div class="large-3 columns">
                <label class="inputLabel" for="startTime">Start</label>
                <input class="startTime text-center" type="text" name="startTime" value="<?= date('H:i', strtotime($schedule->getStartTime())); ?>" />
            </div>

            <div class="large-3 columns">
                <label class="inputLabel" for="endTime">End</label>
                <input class="endTime text-center" name="endTime" value="<?= date('H:i', strtotime($schedule->getEndTIme())); ?>" type="text" />
            </div>

            <div class="large-3 columns">
                <label class="inputLabel" for="location">Location</label>
                <select class="location" name="scheduledLocation_id">
                    <?php foreach (shift::getLocations() as $location): ?>
                        <option value="<?= $location['id']; ?>"
                        <?php
                        if ($location['id'] == $schedule->getLocationID()) {
                            echo 'selected="selected"';
                        }
                        ?> >
                        <?= $location['locationText']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

        </fieldset>

        <div class="columns">
            <div class="large-4 columns">
                <button class="default button addShift nowrap" type="submit" name="markTimesheet" value="markTimesheet">Mark as worked</button>
            </div>
            <?php if (strpos($url, 'statusIndex')) { ?>
                <div class="large-8 columns">
                    <button id="supervisorSignin" class="default button addShift nowrap" type="submit" name="supervisorSignin" value="supervisorSignin">Mark as signed in</button>
                </div>
            <?php } ?>
        </div>

    </div>
</div>
</form>

<br/><a class="revealDaySchEditForm" id="backToEditSch" data-open="revealDaySchEditForm">Back to Edit Schedule</a>