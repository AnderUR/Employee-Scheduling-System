<?php
$aPanelAddSemester = '<img src="/LibServices/assets/ess_assets/img/helpICO.png" style="display:none;" class="helpMenu" data-what-help="aPanel_addSemester" />';

$attributes = array(
	'data-confirm' => 'Are you sure you want to add this semester?'
);
echo form_open($postUrl, $attributes);
?>

<div class="medium-9 medium-centered columns">
	<div class="row">
		<fieldset id="addSmContainer" class="small-12 columns">
			<div class="editFormTitle_addSemester">Add Semester <?= $aPanelAddSemester; ?></div>

			<div class="columns">
				<label class="inputLabel" for="semesterCalendarURL">Calendar URL</label>
				<input type="text" id="calendarLink" name="calendarURL" value="Ex: http://www.citytech.cuny.edu/registrar/docs/fallcal_2017" />
			</div>

			<div class="row">
				<div class="medium-9 medium-centered columns">
					<div class="row">

						<div class="large-4 columns">
							<label class="inputLabel" for="semesterName">Semester</label> <select class="semesterName" name="semester_name">
								<option value="Spring">Spring</option>
								<option value="SummerI">Summer I</option>
								<option value="SummerII">Summer II</option>
								<option value="Fall">Fall</option>
							</select>
						</div>

						<div class="large-4 columns">
							<label class="inputLabel" for="semesterStartDate">Start</label>
							<input class="dateField semesterStartDate text-center" type="text" name="startDate" value="yy-mm-dd" />
						</div>

						<div class="large-4 columns">
							<label class="inputLabel" for="semesterEndDate">End</label> <input class="dateField semesterEndDate text-center" type="text" name="endDate" value="yy-mm-dd" />
						</div>

					</div>
				</div>
			</div>
			<div class="columns">

				<div class="text-center">
					<button id="save" class="addShift default button" type="submit" name="submit" value="submitSemester">Save</button>
				</div>

			</div>
			<br>
		</fieldset>
	</div>

</div>
</form>