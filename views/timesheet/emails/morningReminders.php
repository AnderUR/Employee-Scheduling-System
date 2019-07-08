<div class="row large-centered large-12 columns">
    <?php
    $this->load->view('timesheet/viewAnnouncements', $announcements);
    ?>
</div>
<?php $this->load->view('timesheet/emails/footer');
