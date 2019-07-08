<div class="row collapse">
  <div class="medium-2 columns">

    <?php
    $thisUser = new webuser();

    $emplSearchName = "";
    if ($thisUser->is_supervisor()) {
      $emplSearchName = '<img src="/LibServices/assets/ess_assets/img/helpICO.png" style="display:none; class="helpMenu" data-what-help="empl_search_name" />';
    }

    echo $emplSearchName;

    echo webuser::view($thisUser->getUID(), $this->load->view('timesheet/controls/searchEmployee', '', TRUE));
    ?>

    <ul class="tabs vertical" id="manageEmployeeTabs" data-tabs>
      <li class="tabs-title is-active"><a href="#panel1v" id="schedule_tab1" aria-selected="true">Scheduled Shifts</a></li>
      <li class="tabs-title"><a href="#panel2v" id="timesheet_tab2">Worked Shifts</a></li>
      <li class="tabs-title"><a href="#panel3v" id="profile_tab3">Profile</a></li>
    </ul>
  </div>
  <div class="medium-10 columns">

    <div class="tabs-content vertical" data-tabs-content="manageEmployeeTabs">

      <div class="customTabs tabs-panel is-active" id="panel1v">
        <?php echo $scheduleEmployeePage; ?>
      </div>
      <div class="customTabs tabs-panel" id="panel2v">
        <?php echo $timesheetTmployeePage; ?>
      </div>
      <div class="customTabs tabs-panel" id="panel3v">
        <?php echo $employeeInfoPage; ?>
      </div>

    </div>
  </div>
</div>