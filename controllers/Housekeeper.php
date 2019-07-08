<?php
/**
 * Housekeeper class handles barcode login and user log out. It works together with ion_auth to check current login status.
 * It is in charge for updating email and barcode and toggling barcodeLogin in the login page.
 */
defined('BASEPATH') or exit('No direct script access allowed');

class Housekeeper extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        //$this->load->library('authentication');
    }

    function index()
    {
        if ($this->ion_auth->logged_in()) {
            redirect('timesheet/index');
        } else {
            redirect('auth/login');
        }
    }

    /**
     * Authenticate user, including checking barcodeLogin state.
     * Renew cookie expiration date if login suceeds or redirect to login page if not. 
     */
    function login()
    {
        $data = $this->input->post();
        if (isset($data['submit']) && $data['barcode'] != '' && Authentication::bool_has_DB_Record($data['barcode'])) {
            $barcode = $data['barcode'];

            $selfService = new Webuser();
            $selfService->setUserByBarcode($barcode);
            $barcodeLogin = $selfService->getProperties()['barcodeLogin'];

            if ($barcodeLogin != "1") {
                $this->session->set_flashdata('message', "You have disabled barcode login. Use email and password to login.");
                redirect("/auth/login");
            } else if ($barcodeLogin == "1" && Authentication::boolint_set_web_session_byBarcode($barcode) == 1) {
                $cookie = $this->input->cookie('ci_session'); // we get the cookie
                $this->input->set_cookie('ci_session', $cookie, '35580000'); // and add one year to it's expiration

                server::router();
            }
        } else {
            $this->session->set_flashdata('message', "Incorrect login.");
            redirect("/auth/login");
        }
    }

    /**
     * Logs out user, irregardless of the original login method.
     * Redirects to login page.
     */
    function logout()
    {
        Authentication::close_web_session();

        redirect('auth/login');
    }

    /**
     * Requires barcode and password to authenticate user, and new email to use. 
     * No email is sent to the user for confirmation.
     * In success or failure, sets flashdata and redirect to login page.
     */
    function updateEmail()
    {
        $barcode = $this->input->post('barcode');
        $email = $this->input->post('updatedEmail');
        $email_confirm = $this->input->post('updatedEmail_confirm');
        $password = $this->input->post('password');

        if ($email === $email_confirm) {
            $thisUser = new webuser();
            $thisUser->setUserByBarcode($barcode);

            if (($thisUser->getUID() != 0) && (trim($email) != "") && (webuser::ifEmailExist($email) == false) && Authentication::boolint_authenticate_dbUser($thisUser->getUID(), $password) == 1) {
                if (!$thisUser->updateEmail($email)) {
                    $this->session->set_flashdata('message', 'Failed to update the email. Please try again.');
                    redirect('auth/login', 'refresh');
                } else {
                    $this->session->set_flashdata('message', 'Email changed successfully.');
                    redirect('auth/login', 'refresh');
                }
            } else {
                if ($thisUser->getUID() == 0) {
                    $this->session->set_flashdata('message', 'Barcode does not exist.');
                    redirect('auth/login', 'refresh');
                } else {
                    $this->session->set_flashdata('message', 'Email alrealy exists OR Password Incorrect.');
                    redirect('auth/login', 'refresh');
                }
            }
        } else {
            $this->session->set_flashdata('message', 'Emails must match.');
            redirect('auth/login', 'refresh');
        }
    }

    /**
     * Requires email and password to authenticate user, and new barcode to use.
     * No email is sent to the user for confirmation.
     * In success or failure, sets flashdata and redirect to login page.
     */
    function updateBarcode()
    {
        $barcode = $this->input->post('updatedBarcode');
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $thisUser = new webuser();
        $thisUser->setUserByEmail($email);

        if (($thisUser->getUID() != 0) && (trim($barcode) != "") && (webuser::ifBarcodeExist($barcode) == false) && Authentication::boolint_authenticate_dbUser($thisUser->getUID(), $password) == 1) {
            if (!$thisUser->updateBarcode($barcode)) {
                $this->session->set_flashdata('message', 'Failed to update the barcode. Please try again.');
                redirect('auth/login', 'refresh');
            } else {
                $this->session->set_flashdata('message', 'Barcode changed successfully.');
                redirect('auth/login', 'refresh');
            }
        } else {
            if ($thisUser->getUID() == 0) {
                $this->session->set_flashdata('message', 'Email does not exist');
                redirect('auth/login', 'refresh');
            } else {
                $this->session->set_flashdata('message', 'Barcode alrealy exists OR Password Incorrect');
                redirect('auth/login', 'refresh');
            }
        }
    }

    /**
     * If successful, the user will no longer be able to login with barcode, but with email and password.
     * Calls on disableBarcodeLogin method from Webuser class.
     * In success or failure, sets flashdata and redirect to login page.
     */
    private function disableBarcodeLogin($userObj)
    {
        if ($userObj->getProperties()['barcodeLogin'] == '1') {

            if ($userObj->disableBarcodeLogin()) {
                $this->session->set_flashdata('message', 'Barcode login for ' . $userObj->getProperties()['email'] .  ' has been disabled successfully.');
                redirect('auth/login', 'refresh');
            } else {
                $this->session->set_flashdata('message', 'Failed to disable barcode login.');
                redirect('auth/login', 'refresh');
            }
        } else {
            $this->session->set_flashdata('message', 'You have already disabled barcode login.');
            redirect('auth/login', 'refresh');
        }
    }

    /**
     * If successful, the user will be able to login with barcode or with email and password.
     * Calls on enableBarcodeLogin method from Webuser class
     * In success or failure, sets flashdata and redirects to login page.
     */
    private function enableBarcodeLogin($userObj)
    {
        if ($userObj->getProperties()['barcodeLogin'] == '0') {

            if ($userObj->enableBarcodeLogin()) {
                $this->session->set_flashdata('message', 'Barcode login for ' . $userObj->getProperties()['email'] .  ' has been enabled successfully.');
                redirect('auth/login', 'refresh');
            } else {
                $this->session->set_flashdata('message', 'Failed to enable barcode login.');
                redirect('auth/login', 'refresh');
            }
        } else {
            $this->session->set_flashdata('message', 'You have already enabled barcode login.');
            redirect('auth/login', 'refresh');
        }
    }

    /**
     * Receives and processes data to Disable/Enable barcode login for the posted email and password. User is validated here. 
     * In success or failure, sets flashdata and redirects to login page.
     */
    function toggleBarcodeLogin()
    {
        $post = $this->input->post();

        if (!empty($post['password']) && !empty($post['email'])) {
            $email = $this->input->post('email');
            $password = $this->input->post('password');

            $user = new Webuser();
            $user->setUserByEmail($email);
            $uid = $user->getUID();

            if ($uid !== 0) { //if 0, user not set
                if ((Authentication::boolint_authenticate_dbUser($uid, $password) == 1)) {

                    if (isset($post['disable'])) {
                        $this->disableBarcodeLogin($user);
                    } else if (isset($post['enable'])) {
                        $this->enableBarcodeLogin($user);
                    } else {
                        $this->session->set_flashdata('message', 'Enable or disable barcode was not specified.');
                        redirect('auth/login', 'refresh');
                    }
                } else {
                    $this->session->set_flashdata('message', 'Incorrect password.');
                    redirect('auth/login', 'refresh');
                }
            } else {
                $this->session->set_flashdata('message', 'This email does not exist in our records.');
                redirect('auth/login', 'refresh');
            }
        } else {
            $this->session->set_flashdata('message', 'You must provide email and password to disable/enable barcode login.');
            redirect('auth/login', 'refresh');
        }
    }
} //end of file
