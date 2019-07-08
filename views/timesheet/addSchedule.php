<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$thisUser = new webuser();

$emplAddShift = "";
if ($thisUser->is_supervisor()) {
	$emplAddShift = '<img src="/LibServices/assets/ess_assets/img/helpICO.png" style="display:none;" class="helpMenu" data-what-help="empl_addShift" />';
}

?>

<?php
$attributes = array('data-confirm' => 'This shift will be added to the schedule of this employee. If you selected to repeat weekly, this shift will repeat weekly until the specified date. Do you want to submit?');
echo form_open('timesheet/addSchedule', $attributes);
echo form_hidden('caID', $caID);
echo form_hidden('recursiveEndDate', schedule::getActiveSemesterROW()['endDate']);
?>
<div class="loadActivityICO"><img class="" src="/LibServices/assets/ess_assets/img/activityICO.gif" /></div>

<div class="small-12 columns">
	<fieldset id="shiftContainer" class="large-12 medium-6 small-6 small-centered columns shiftFields">
		<div class="formTitle">Add Shift <?= $emplAddShift ?></div>
		<div class="large-2 columns">
			<label class="inputLabel" for="dateField">Date</label>
			<input readonly="true" class="dateField text-center" type="text" name="scheduledDate" value="<?= date('Y-m-d'); ?>" />
		</div>

		<div class="large-2 columns">
			<label class="inputLabel" for="startTime">Start</label>
			<input readonly="true" class="startTime text-center" type="text" name="startTime" value="00:00" />
		</div>

		<div class="large-2 columns">
			<label class="inputLabel" for="endTime">End</label>
			<input readonly="true" class="endTime text-center" name="endTime" value="00:00" type="text" />
		</div>

		<div class="large-2 columns moveWhereInput">
			<label class="inputLabel" for="location">Location</label>
			<select class="location" name="scheduledLocation_id">
				<?php foreach (shift::getLocations() as $location) : ?>
					<option value="<?= $location['id']; ?>"><?= $location['locationText']; ?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<fieldset class="large-2 columns moveRecursiveInput">
			<label class="inputLabel nowrap" for="recursiveDateField">
				Repeat Weekly until... <input type="checkbox" name="chk_isRecursive" class="isRecursiveCheck" />
			</label>
			<input readonly="true" type="text" name="recursiveEndDate" class="recursiveDateField text-center" value="<?= schedule::getActiveSemesterROW()['endDate']; ?>" disabled />
		</fieldset>

		<div class="large-1 columns moveSubmit">
			<button type="submit" class="default button addShift">Submit</button>
		</div>

	</fieldset>
</div>
</form>