<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Email
| -------------------------------------------------------------------------
| This file lets you define parameters for sending emails.
| Please see the user guide for info:
|
| https://www.codeigniter.com/user_guide/libraries/email.html
|
*/

$config['protocol']='smtp';
$config['smtp_host']='ssl://smtp.googlemail.com'; //(SMTP server)
$config['smtp_port']='465'; //(SMTP port)
$config['smtp_timeout']='30';
$config['smtp_user']='---@gmail.com'; //(user@gmail.com)
$config['smtp_pass']='---'; // (gmail password)
$config['mailtype'] = 'html';
$config['charset'] = 'utf-8';
$config['crlf'] = "\r\n";
$config['newline'] = "\r\n";
