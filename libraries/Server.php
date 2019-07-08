<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Server {

    public function __construct($log = false) {
        
    }
    
    static function sysinfo() {
        redirect('/proc/sysinfo/sysinfo.php?disp=dynamic');
    }
    static function phpinfo() {
        phpinfo();
    }

    static function require_login($controller = false) {
        $me = & get_instance();
        if (!$me->ion_auth->logged_in()) {
            if ($controller != false){
                server::setController($controller);
            }else{
                server::setRouteURL(current_url());
            }
//            redirect('housekeeper/login');
            redirect('auth/login');
        }
    }

    static function setController($controller){
        server::newSessionVariable('controller', $controller);
    }
    
    static function setRouteURL($url){
        server::newSessionVariable('routeURL', $url);
    }
    
    static function getController(){
        $me = & get_instance();
        return $me->session->userdata('controller');
    }
    
    static function getRouteURL(){
        $me = & get_instance();
        return $me->session->userdata('routeURL');
    }
    
    static function newSessionVariable($name, $data) {
        $me = & get_instance();
        $newData = $_SESSION;
        $newData[$name]= $data;
        $me->session->set_userdata($newData);
    }
    static function getSessionVariable($name){
        $me = & get_instance();
        return $me->session->userdata($name);        
    }
    static function router($method = false) {
        if ($method == false) {
            redirect('/router');
        } else {
            redirect('/router/' . $method);
        }
    }

    static function current_url(){
        return current_url();
    }

}
