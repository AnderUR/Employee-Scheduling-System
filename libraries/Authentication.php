<?php
class Authentication
{

    private $instance;
    private $barcode;
    private $firstname;
    private $lastname;
    private $email;
    private $phone;
    private $emergencyContact;
    private $db_user;
    private $db_uid;

    function __construct($barcode = false)
    {
        $this->instance = &get_instance();
        if ($barcode != false) {
            $this->init($barcode);
        } else {
            $this->barcode = 0;
        }
        $this->get_db_user();
    }

    private function init($barcode)
    {
        $this->barcode = $barcode;
        $this->email = '';
        $this->phone = '';
        $this->emergencyContact = '';
    }

    private function get_db_user()
    {
        $me = &get_instance();
        $me->db->select('*');
        $me->db->where('barcode', $this->barcode);
        $me->db->limit(1);
        $q = $me->db->get('libservices.users');
        if ($q->num_rows() == 1) {
            $this->db_uid = $q->row_array()['id'];
            $this->db_user = new Webuser($this->db_uid);
        } else {
            $this->db_uid = 0;
        }
    }

    function get_db_uid()
    {
        return $this->db_uid;
    }

    static function bool_has_DB_Record($barcode)
    {
        $me = &get_instance();
        $me->db->select('*');
        $me->db->where('barcode', $barcode);
        $me->db->limit(1);
        $q = $me->db->get('libservices.users');
        return $q->num_rows() == 1;
    }

    static function bool_has_DB_Record_byEmail($email)
    {
        $me = &get_instance();
        $me->db->select('*');
        $me->db->where('email', $email);
        $me->db->limit(1);
        $q = $me->db->get('libservices.users');
        return $q->num_rows() == 1;
    }

    function create_DB_User()
    {
        $passwordHash = Authentication::string_get_hash($this->barcode);

        $userRow = array(
            'email' => $this->email,
            'password' => $passwordHash,
            'first_name' => $this->firstname,
            'last_name' => $this->lastname,
            'barcode' => $this->barcode,
            'username' => $this->lastname . ", " . $this->firstname
        );
        $this->instance->db->insert('libservices.users', $userRow);
        $new_uid = $this->instance->db->insert_id();

        $userGroup = array(
            'user_id' => $new_uid,
            'group_id' => 4
        );
        $this->instance->db->insert('libservices.users_groups', $userGroup);
        return $new_uid;
    }

    function array_authenticate_return_user($password)
    {
        if (Authentication::boolint_authenticate_dbUser($this->db_uid, $password)) {
            $user = new Webuser($this->db_uid);
            return $user->getProperties();
        } else {
            return array('id' => 0);
        }
    }

    static function boolint_authenticate_dbUser($db_uid, $password)
    {
        $me = &get_instance();
        if ($me->ion_auth->hash_password_db($db_uid, $password)) {
            return 1;
        } else {
            return 0;
        }
    }

    private static function setWebSession($db_uid)
    {
        $user = new Webuser($db_uid);
        $session = array( 
            'identity' => $user->getProperties()['email'],
            'email' => $user->getProperties()['email'],
            'user_id' => $db_uid,
            'old_last_login' => $user->getProperties()['last_login'] 
        );
        foreach ($session as $key => $value) {
            server::newSessionVariable($key, $value);
        }

        $barcode = $user->getBarcode();
        $auth = new Authentication($barcode);

    }

    function boolint_set_web_session_byUid($password = false)
    {
        if ($this->db_uid == 0) {
            return 0;
        } else {
            if ($password != false) {
                if (Authentication::boolint_authenticate_dbUser($this->db_uid, $password) == 1) {
                    Authentication::setWebSession($this->db_uid);
                    return 1;
                } else {
                    return 0;
                }
            } else {
                Authentication::setWebSession($this->db_uid);
                return 1;
            }
        }
    }

    static function boolint_set_web_session_byBarcode($barcode, $password = false)
    {
        $user = new Webuser();
        $user->setUserByBarcode($barcode);

        $authentication = new Authentication($barcode);

        if ($password == false) {
            return $authentication->boolint_set_web_session_byUid();
        } else {
            return $authentication->boolint_set_web_session_byUid($password);
        }
    }
    static function boolint_set_web_session_byEmail($email, $password = false)
    {
        $user = new Webuser();
        $user->setUserByEmail($email);
        $authentication = new Authentication($user->getBarcode());

        if ($password == false) {
            return $authentication->boolint_set_web_session_byUid();
        } else {
            return $authentication->boolint_set_web_session_byUid($password);
        }
    }

    static function close_web_session()
    {
        $session = array();
        $_SESSION = $session;
    }

    static function string_get_hash($input)
    {
        $hash = $input;
        $salt = substr(md5(uniqid(rand(), true)), 0, 10);
        $hash = $salt . substr(sha1($salt . $hash), 0, -10);
        return $hash;
    }

    static function boolint_authenticate_hash($inputString, $inputHash)
    {
        $salt = substr($inputHash, 0, 10);
        $db_password =  $salt . substr(sha1($salt . $inputString), 0, -10);
        if ($inputHash == $db_password) {
            return true;
        } else {
            return false;
        }
    }

}
