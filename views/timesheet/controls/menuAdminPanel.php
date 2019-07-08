<div id="adminPanel" class="timesheetTopBarArea top-bar-right">
    <ul class="menu">
        <li><?=anchor('timesheet/adminPanel', 'Admin Panel'); ?>
            <?php if ((strpos(current_url(), "adminPanel") !== FALSE)) { ?>
                <span id="topBarIndicatorAdminPanel">&#9650; </span>
            <?php } ?>
        </li>
    </ul>
</div>
