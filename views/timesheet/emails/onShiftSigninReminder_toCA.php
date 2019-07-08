<?php
$schedule = new Schedule($scheduleID);
    $ca = new Webuser($schedule->getCA_Id());
?>

<div class="row large-centered large-12 columns">
    This is a reminder that you have <b>NOT</b> signed in to a scheduled shift<br><br>
    <b>
    <?=Schedule::getCA_Name($schedule->getCA_Id())."<br>Today ".date('l m-d',strtotime($schedule->getScheduledDate()))
        ."<br> From ".(($schedule->getStartTime()))
        ." - "
        .(($schedule->getEndTIme())).'<br>'
        .$schedule->getLocationText();?></b><br><br><b>Please remember to sign in.</b>
</div>


<?php $this->load->view('timesheet/emails/footer');
