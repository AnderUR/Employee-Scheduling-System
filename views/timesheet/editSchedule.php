<?php
$schedule = new schedule($scheduleID);
$ca = new webuser($schedule->getCA_Id());

$formEditShift = '<img src="/LibServices/assets/ess_assets/img/helpICO.png" class="helpMenu" data-what-help="form_editSemesterSch" />';  
echo $formEditShift;
?>

<?php

$attributes = array('data-confirm' => 'Are you sure you want to proceed with this change?');
echo form_open('timesheet/editSchedule', $attributes);
echo form_hidden('id', $scheduleID);
echo form_hidden('caID', $schedule->getCA_Id());
?>

<div class="loadActivityICO"><img class="" src="/LibServices/assets/ess_assets/img/activityICO.gif" /></div>
<div class="row clearfix">
    <div id="semesterSubstitute" class="editSrchInpt large-4 medium-7 small-12 small-centered large-uncentered columns float-right"> 
        <label for="substitute">Subsitute</label>
        <input type="text" id="subsitute" placeholder="Find temporary employee" />
    </div>
</div>  

<div id="editForm"  class="row">
    <div class="editFormTitle_schedule">Edit Semester Schedule: 
    	<span class="shifts_userName"><?= $ca->getProperties()['first_name'] . " " . $ca->getProperties()['last_name'] ?></span>
    </div>
    <div class="small-12 small-centered columns">
        <br>     
        <fieldset id="editShiftContainer" class="large-12 medium-7 small-12 small-centered columns shiftFields">
            <div class="large-2 columns">
                <label class="inputLabel" for="dateField">Date</label> 
                <input readonly="true" class="dateField text-center" type="text" name="scheduledDate" value="<?= date('Y-m-d', strtotime($schedule->getScheduledDate())); ?>" />
            </div>

            <div class="large-2 columns">
                <label class="inputLabel" for="startTime">Start</label> 
                <input readonly="true" class="startTime text-center" type="text" name="startTime" value="<?= date('H:i', strtotime($schedule->getStartTime())); ?>" />
            </div>

            <div class="large-2 columns">
                <label class="inputLabel" for="endTime">End</label> 
                <input readonly="true" class="endTime text-center" name="endTime" value="<?= date('H:i', strtotime($schedule->getEndTIme())); ?>" type="text" />
            </div>

            <div class="large-2 columns">
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

            <fieldset class="large-3 columns">
                <label class="inputLabel nowrap" for="recursiveDateField">
                    Repeat weekly until 
                    <input type="checkbox" name="chk_isRecursive" class="isRecursiveCheck"
                    <?php
                    $checkedBool = false;
                    if ($schedule->ifRecursive() == true) {
                    	$checkedBool = true;
                        echo 'Checked';
                    }
                    ?> />
                </label>
                 
                <input readonly="true" type="text" name="recursiveEndDate"
                       class="recursiveDateField text-center" value="<?= $schedule->getRecursiveEndDate(); ?>" 
                       <?php if($checkedBool == false) { echo("disabled"); }?> />
            </fieldset>

            <div class="moveSubmit large-1 columns">
                <button id="save" class="default button addShift" type="submit" name="submit">Save</button>
            </div>

        </fieldset>

        <div id="editShiftButtonContainer" class="column">

            <div class="large-2 large-push-0 medium-2 medium-push-3 columns">
                <button class="delete default button" type="submit" name="delete">Remove</button>
            </div>
            <!--<div class="large-10 medium-6 columns">
                <button id="markTimesheet" class="default button editSchMarkTimesheet addShift" type="submit" name="markTimesheet">Set in timesheet</button>
            </div>-->

        </div>


    </div>
</div>
</form>