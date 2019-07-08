<div class=" row large-8 medium-8 small-12 columns">
    <div id="" style="overflow-y: auto; overflow-x:hidden; height:50vh;width: 119%;">
        <?php
        $schedule = new schedule();
        $data['announcements'] = $schedule->getAnnouncements(Schedule::getDateFilterException());

        $this->load->view('timesheet/viewAnnouncements', $data);
        ?>    
    </div>
</div>