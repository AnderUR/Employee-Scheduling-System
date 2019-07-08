<?php

class notify extends CI_Model
{
    /**
     * Emails existing user.
     * This templates current ussage does not cover every situation. For example, if ion_auth is used to create a new user, ion_auth's email settings are used. This could be changed, however...
     */
    function email_user($uid, $senderMsg, $subject = false)
    {
        $user = webuser::getUser($uid);

        if (isset($user)) {
            $this->email->clear();
            $this->email->from($this->config->item('admin_email', 'ion_auth'), 'ESS');
            $this->email->to($user['email']);
            $msg = str_replace('%20', ' ', $senderMsg);
            $message = $msg;
            if ($subject == false) {
                $this->email->subject("ESS Account Information");
            } else {
                $this->email->subject($subject);
            }
            $this->email->message($message);
            $this->email->send();
            set_time_limit(0);
        } else {
            echo "No such user exists. Email not sent.";
        }
    }
}
