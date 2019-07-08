<?php
$data['results'] = $tbl_data;
?>

<div class="row collapse">
    <br>
    <div class="medium-2 columns">

        <ul class="tabs vertical" id="adminPanelTabs" data-tabs>
            <li class="tabs-title is-active"><a href="#panel1v" id="announcements_tab1" aria-selected="true">Announcements</a></li>
            <li class="tabs-title"><a href="#panel2v" id="addNewEmployee_tab2">Add Employee</a></li>
            <li class="tabs-title"><a href="#panel3v" id="semester_tab3">Semester</a></li>
        </ul>
    </div>
    <div class="medium-10 columns">

        <div class="tabs-content vertical" data-tabs-content="adminPanelTabs">

            <div class="customTabs tabs-panel is-active" id="panel1v">
                <?php $this->load->view('timesheet/announcement'); ?>
            </div>
            <div class="customTabs tabs-panel" id="panel2v">
                <?php $this->load->view('timesheet/addEmployee');  ?>
            </div>

            <div class="customTabs tabs-panel" id="panel3v">
                <?php $this->load->view('timesheet/semesterAdmin', $data); ?>
            </div>
        </div>
    </div>
</div>