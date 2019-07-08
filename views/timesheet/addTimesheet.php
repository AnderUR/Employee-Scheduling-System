<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$thisUser = new webuser();
$emplAddWShifts = "";
if ($thisUser->is_supervisor()) {
	$emplAddWShifts = '<img src="/LibServices/assets/ess_assets/img/helpICO.png" style="display:none;" class="helpMenu" data-what-help="empl_addWShifts" />';
}
?>

<?php
$attributes = array('data-confirm' => 'Submitting This shift will add to the total hours worked by this employee in the specified period. Do you want to submit this shift to the timesheet?');
echo form_open('timesheet/addTimesheet', $attributes);
echo form_hidden('caID', $caID);
?>

<div class="loadActivityICO"><img src="/LibServices/assets/ess_assets/img/activityICO.gif"></div>

<div class="small-12 columns">
	<fieldset id="workedShiftContainer" class="large-9 medium-6 small-9 small-centered columns workedShiftFields">
		<div class="formTitle">Add Worked Shift <?= $emplAddWShifts ?></div>
		<div class="large-3 columns">
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

		<div class="large-3 columns moveWhereInput">
			<label class="inputLabel" for="location">Where</label>
			<select class="location" name="scheduledLocation_id">
				<?php foreach (shift::getLocations() as $location) : ?>
					<option value="<?= $location['id']; ?>"><?= $location['locationText']; ?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<div class="large-2 columns moveSubmit">
			<button type="submit" class="default button addShift">Submit</button>
		</div>

	</fieldset>
</div>
</form>