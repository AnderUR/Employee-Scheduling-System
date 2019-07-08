<?php

class Webuser {

    private $uid;
    private $properties;
    private $instance;
    private $groupID;

    private function DBgetUser($uid) {
        if ($uid != 0) {
            $this->instance->db->select('id,email,username,first_name,last_name, phone, barcode, emergencyContact, last_login, barcodeLogin');
            $this->instance->db->where('id', $uid);
            $this->instance->db->limit(1);
            $query = $this->instance->db->get('libservices.users');

            return $query->row_array();
        }
    }

    private function DBgetGroup($uid) {
        $this->instance->db->select('id,user_id,group_id');
        $this->instance->db->where('user_id', $uid);
        $this->instance->db->limit(1);
        $query = $this->instance->db->get('libservices.users_groups');
        return $query->row_array();
    }

    public function __construct($uid = false) {
        $this->instance = & get_instance();
        $this->instance->load->database();
        if ($uid == false) {
            try {
                $this->uid = $this->instance->ion_auth->get_user_id();
                if(!is_numeric($this->uid)){
                    $this->uid =0;
                }
            } catch (Exception $ex) {
                $this->uid = 0;
            }
        } else {
            $this->uid = $uid;
        }
        $this->properties = $this->DBgetUser($this->uid);
        $groupROW = $this->DBgetGroup($this->uid);
        if ($this->uid != 0){
            $this->groupID = $groupROW['group_id'];
        }
    }

    public function setUser($uid){
        $this->uid = $uid;
        $this->properties = $this->DBgetUser($this->uid);
        $groupROW = $this->DBgetGroup($this->uid);
        if ($this->uid != 0){
            $this->groupID = $groupROW['group_id'];
        }
    }

    public function disableBarcodeLogin(){
        $user = array(
            'barcodeLogin' => 0
        );
        $this->instance->db->where('id', $this->uid);
        if( ! $this->instance->db->update('libservices.users', $user) ) {
            return 0;
        } else {
            return 1;
        }
    }

    public function enableBarcodeLogin(){
        $user = array(
            'barcodeLogin' => 1
        );
        $this->instance->db->where('id', $this->uid);
        if( ! $this->instance->db->update('libservices.users',$user) ) {
            return 0;
        } else {
            return 1;
        }
    }

    public function setUserByBarcode($barcode) {
        $this->instance->db->select('id');
        $this->instance->db->where('barcode', $barcode);
        $query = $this->instance->db->get('libservices.users');
        if ($query->num_rows() > 0) {
            $user = $query->row_array();
            $this->setUser($user['id']);
        } else {
            $this->uid = 0;
        }
    }

    public function setUserByEmail($email) {
        $this->instance->db->select('id');
        $this->instance->db->where('email', $email);
        $query = $this->instance->db->get('libservices.users');
        if ($query->num_rows() > 0) {
            $user = $query->row_array();
            $this->setUser($user['id']);
        } else {
            $this->uid = 0;
        }
    }

    static function is_valid_account($userlogin, $passwd) {
        $this->instance->db->where('username', $userlogin);
        $this->instance->db->where('password', md5($passwd));
        $query = $this->instance->db->get('tbluser');
        if ($query->num_rows() == 1)
            return true;
        else
            return false;
    }

    static function getAllUsers() {
        $me = & get_instance();
        $me->db->select('id, username, first_name, last_name, email, phone, barcode');
        $me->db->order_by('username desc');
        $query = $me->db->get('libservices.users');
        return $query->result_array();
    }

    static function displayInForm($array) {
        //removes index (first) dimension
        //ex. $arr[$i]['id']['name'] => $arr['id']['name'];
        $newArray = array();
        for ($i = 0; $i < sizeof($array); $i++) {
            if (isset($array[$i]['username'])) {
                $newArray[$array[$i]['id']] = $array[$i]['username'];
            } else {
                $newArray = $array[$i]['id'];
            }
        }
        return $newArray;
    }

    static function getUser($uid){
        $me = & get_instance();
        $me->db->select('id,email,username,first_name,last_name, phone, barcode');
        $me->db->where('id', $uid);
        $me->db->limit(1);
        $query = $me->db->get('libservices.users');
        return $query->row_array();
    }

    /**
     * Method applies to the ess employee page
     */
    static function userPage($uid) {
        server::require_login();
        $caID = $uid;
        $me = & get_instance();
        $returnStr = "";
        $curUser = new webuser();
        if ($curUser->hasPrivilege() || ($caID == $curUser->getUID())) {
            $data['title'] = "User Detail";
            $data['caID'] = $caID;
            $returnStr= $me->load->view('timesheet/userInfo', $data,true);
        } else {
            $returnStr = "<center>You do not have enought privilege to view this Page!<br>".anchor('auth/login', 'LOGIN')."</center>";
        }
        return $returnStr;
    }

    static function getLoggedInUid(){
        $user = new webuser();
        return $user->getUID();
    }

    function is_admin() {
        if ($this->groupID == 1) {
            return true;
        } else {
            return false;
        }
    }

    function is_supervisor() {
        if ($this->groupID <= 2) {
            return true;
        } else {
            return false;
        }
    }

    function is_user() {
        if ($this->groupID == 3) {
            return true;
        } else {
            return false;
        }
    }

    function hasPrivilege() {
        if (($this->groupID < 3) && ($this->groupID != 0) && ($this->uid != 0)){
            return true;
        } else {
            return false;
        }
    }

    function getGroupID(){
        if ($this->uid != 0){
            return $this->groupID;
        }else{
            return 99;
        }
    }

    function hasBarcode() {
        if (($this->getProperties()['barcode'] == "") ||
                (!isset($this->getProperties()['barcode'])) ||
                ($this->getProperties()['barcode'] == 0) || (!is_numeric($this->getProperties()['barcode']))) {
            return false;
        } else {
            return true;
        }
    }

    function hasEmail(){
        if (($this->getProperties()['email'] == '') ||
                (!isset($this->getProperties()['barcode'])) ||
                ($this->getProperties()['barcode'] == 0) ||
                (!is_numeric($this->getProperties()['barcode']))) {
            return false;
        } else {
            return true;
        }
    }

    function getProperties() {
        return $this->properties;
    }

    function getUID() {
        return $this->uid;
    }

    function getBarcode(){
        return $this->properties['barcode'];
    }

    function getUsername(){
        if ($this->uid != 0){
            return ucwords(strtolower($this->getProperties()['last_name']).', '.ucwords(strtolower($this->getProperties()['first_name'])));
        }else{
            return "Guest";
        }
    }

    static function getGuestUsers(){
        $me = & get_instance();

        $row = $me->db->query('SELECT users.id, users.username, users.first_name, users.last_name, email, phone, barcode
FROM libservices.users, libservices.users_groups
where libservices.users.id = libservices.users_groups.user_id;');
        return ($row->result_array());
    }

    function updateBarcode($barcode) {
        if ($this->uid == 0) {
            return 0;
        } else {
        $updateRow = array(
            'barcode' => $barcode
        );
        $this->instance->db->where('id', $this->uid);
            if( ! $this->instance->db->update('libservices.users', $updateRow) ) {
                return 0;
            } else {
                return 1;
            }
        }
    }

    function updateEmail($email) {
        if ($this->uid == 0) {
            return 0;
        } else {
            $updateRow = array(
                'email' => $email
            );
            $this->instance->db->where('id', $this->uid);
            if( ! $this->instance->db->update('libservices.users', $updateRow) ) {
                return 0;
            } else {
                return 1;
            }
        }
    }

    static function ifEmailExist($email){
        $me = & get_instance();
        $me->db->select('*');
        $me->db->where('email',$email);
        $q = $me->db->get('libservices.users');
        if ($q->num_rows() == 0){
            return false;
        }else{
            return true;
        }
    }

    static function ifBarcodeExist($barcode){
        $me = & get_instance();
        $me->db->select('*');
        $me->db->where('barcode',$barcode);
        $q=  $me->db->get('libservices.users');
        if ($q->num_rows() == 0){
            return false;
        }else{
            return true;
        }
    }

    static function ifUidExist($uid){
        $me = & get_instance();
        $me->db->select('*');
        $me->db->where('id',$uid);
        $q = $me->db->get('libservices.users');
        if ($q->num_rows() == 0){
            return false;
        }else{
            return true;
        }
}

    /**
     * For loading ess views according to privilege 
     */
    static function view($uid, $adminView, $userView = false){
        $user = new webuser($uid);
        if ($user->hasPrivilege()){
            return $adminView;
        }else{
            if ($userView != false){
                return $userView;
            }
        }
    }

}
