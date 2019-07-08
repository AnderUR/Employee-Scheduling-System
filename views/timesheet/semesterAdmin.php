<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<?php

$data['header'] = array();
$data['header'][0] = array(0, 'Edit');
$data['header'][1] = array(0, 'Semester');
$data['header'][2] = array(0, 'Start');
$data['header'][3] = array(0, 'End');

$data['revealBoolean'] = 'semesterReveal';

$data['postUrl'] = 'timesheet/addSemester';
$data['semesters'] = schedule::getSemesters();

$aPanelSemesterTable = '<img src="/LibServices/assets/ess_assets/img/helpICO.png" style="display:none;" class="helpMenu" data-what-help="aPanel_semesterTable" />';

?>

<div class="adminTableFormContainer boxShadow row">
	<?= $aPanelSemesterTable; ?>
	<div class="innerContainer small-12 columns">
		<div class="table-wrapper">
			<?= $this->load->view('timesheet/tableTemplate', $data, true); ?>
		</div>
	</div>

	<?= $this->load->view('timesheet/addSemester', $data, true); ?>

</div>

<!--Shift form header for reveal -->

<div class="small reveal" id="semesterReveal" aria-labelledby="addSemesterReveal" data-reveal data-close-on-click="false">

	<button class="close-button closeForm" data-close aria-label="Close modal" type="button">
		<span aria-hidden="true">&times;</span>
	</button>
	<div id="editSemesterReveal">

	</div>
</div>

<!--Shift form header for reveal -->

<div class="large reveal" id="editExcpAnnReveal" aria-labelledby="editExcpAnnReveal" data-reveal data-close-on-click="false">

	<button class="close-button closeForm" data-close aria-label="Close modal" type="button">
		<span aria-hidden="true">&times;</span>
	</button>
	<div id="editExcpAnnView">

	</div>

</div>