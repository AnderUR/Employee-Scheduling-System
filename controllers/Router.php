<?php
/**
 * Works with the Server class to handle redirects
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Router extends CI_Controller {
    function index(){
        $url = server::getSessionVariable('routeURL');
        if ($url != ''){
            redirect($url);
        }else{
            redirect(server::getController());
        }
    }

    function url($url){
        server::setRouteURL($url);
        server::router();
    }
    
    function controller($controller){
        server::setController($controller);
        server::router();
    }
}