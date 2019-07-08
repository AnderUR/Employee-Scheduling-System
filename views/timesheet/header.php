<?php 
/**
 * This header is used as part of the web model getResponse_ess function, which loads most of the ess views
 */
?>

<?php
$user = new webuser();
$userFullname = $user->getUsername();
$isAdmin = '<span id="isAdmin" class="hide">false</span>';
$navHelp = "";
if ($user->is_supervisor()) {
    $navHelp = '<img src="/LibServices/assets/ess_assets/img/helpICO.png" style="display:none;" class="helpMenu float-center" data-what-help="general_topBar" />';
    $isAdmin = '<span id="isAdmin" class="hide">true</span>';
}
?>

<!DOCTYPE HTML>

<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="icon" type="image/png" href="/LibServices/assets/ess_assets/img/clockLogoFav.ico">

    <link rel="stylesheet" href="/LibServices/assets/ess_assets/jquery_components/jquery-ui/themes/smoothness/jquery-ui.min.css">
    <link rel="stylesheet" href="/LibServices/assets/ess_assets/jquery_components/jqueryui-timepicker-addon/dist/jquery-ui-timepicker-addon.min.css">

    <link rel="stylesheet" href="/LibServices/assets/ess_assets/foundation6/css/foundation.css" />
    <link rel="stylesheet" href="/LibServices/assets/ess_assets/css/app.css">

    <title>Employee Scheduling System</title>
</head>

<body>

    <div id="headline">
        <?= $navHelp ?>
        <?= $isAdmin ?>
        <ul class="dropdown menu float-right moveCurrentUser" data-dropdown-menu>
            <li>
                <?php
                if ($user->getUID() == 0) {
                    $userFullname = "Not Logged In";
                }
                ?>
                <a class="ess_orange" href="#"><?= $userFullname; ?></a>
                <ul class="menu vertical">

                    <li><?= anchor('timesheet/showannouncements', 'Announcements'); ?></li>
                    <?php
                    if ($user->getUID() == 0) {
                        ?><li><a href="<?php echo site_url('/housekeeper/login'); ?>">Log in</a></li><?php
                    } else { ?>
                        <li><a href="<?php echo site_url('/housekeeper/logout'); ?>">Sign out</a></li>
                    <?php
                    }
                    ?>
                    <li><a id="iniHelp" href="javascript:void(0)"><img src="/LibServices/assets/ess_assets/img/helpICO.png" />Show help</a></li>

                </ul>
            </li>
        </ul>

        <div class="container">
            <img src="/LibServices/assets/ess_assets/img/clockLogo.png" />
        </div>

    </div>

    <?php
    if ($user->getUID() != 0) {
        ?>
        <div>
            <div style="border-bottom: 4px solid #606060;">
                <div id="timesheetTopBar" class="top-bar">
                    <div id="responsiveTopBar" class="title-bar" data-responsive-toggle="responsive-menu" data-hide-for="medium">

                        <button class="menu-icon dark" type="button" data-toggle></button>

                    </div>
                    <div id="responsive-menu" class="timesheetResponsiveMenu">

                        <?php
                        echo webuser::view($user->getUID(), $this->load->view('timesheet/controls/menuAdminPanel', '', TRUE));
                        ?>

                        <div class="menu-centered">
                            <ul class="menuCenterTopBar menu">

                                <li id="topBarStatusIndex"><?= anchor('timesheet/statusIndex', 'Status'); ?>
                                    <?php if ((strpos(current_url(), "statusIndex") !== FALSE)) { ?>
                                        <div><span id="topBarIndicator">&#9650; </span></div>
                                    <?php } ?>
                                </li>

                                <li id="topBarSchIndex"><?= anchor('timesheet/scheduleLabIndex', 'Schedule'); ?>
                                    <?php if ((strpos(current_url(), "scheduleIndex") !== FALSE) || (strpos(current_url(), "scheduleLabIndex") !== FALSE)) { ?>
                                        <span id="topBarIndicator">&#9650; </span>
                                    <?php } ?>
                                </li>

                                <?php
                                $selector = "";
                                if ((strpos(current_url(), "timesheetIndex") !== FALSE) || (strpos(current_url(), "/timesheetLabIndex") !== FALSE)) {
                                    $selector = '<span id="topBarIndicator">&#9650; </span>';
                                }
                                echo webuser::view(webuser::getLoggedInUid(), '<li id="topBarTsheetIndex">' . anchor('timesheet/timesheetLabIndex', 'Timesheet') . $selector . '</li>');
                                ?>

                                <li id="topBarManageEmpl"> <?= anchor('timesheet/manageEmployee', 'Employee'); ?>
                                    <?php if ((strpos(current_url(), "manageEmployee") !== FALSE) || (strpos(current_url(), "/manageEmploye") !== FALSE)) { ?>
                                        <span id="topBarIndicator">&#9650; </span>
                                    <?php } ?>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    <?php
}