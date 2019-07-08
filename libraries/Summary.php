<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Summary
{

    private $instance;

    public function __construct()
    {
        //$this->instance = &get_instance();
    }

    /**
     * Used for working with multiple stored procedure calls
     */
    private static function next_result($result)
    {
        if (is_object($result->conn_id)) {
            if (mysqli_more_results($result->conn_id)) {
                $result = mysqli_next_result($result->conn_id);
            } else {
                $result = false;
            }
        }
    }
    
    static function query($sql, $db = false)
    {
        $me = &get_instance();
        if ($db == false) {
            $result = $me->db->query($sql);
            $data = $result->result_array();
            Summary::next_result($result);
            $result->free_result();
            if (sizeof($data) == 0) {
                $data = array();
            }
            return ($data);
        } else {
            $result = $db->query($sql);
            $data = $result->result_array();
            Summary::next_result($result);
            $result->free_result();
            if (sizeof($data) == 0) {
                $data = array();
            }
            return ($data);
        }
    }
}
