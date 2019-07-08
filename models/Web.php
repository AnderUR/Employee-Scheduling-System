<?php

class web extends CI_Model
{
    /**
     * Employee Scheduling System template that is used for all main pages in the timesheet controller
     */
    public function getResponse_ess($source, $data = false)
    {
        if ($source !== FALSE) {
            $this->load->view('timesheet/header');
            $this->load->view($source, $data);
            $this->load->view('timesheet/footer.php');
        } else {
            echo "Source Error";
        }
    }
}
