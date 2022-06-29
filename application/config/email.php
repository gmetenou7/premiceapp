<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*$config = array(
    'protocol' => 'SMTP', // 'mail', 'sendmail', or 'smtp'
    'smtp_host' => 'mail.premicecomputer.com', //ssl://smtp.googlemail.com
    'smtp_port' => 587, //465 587
    'smtp_user' => 'premicecomputer@premicecomputer.com',
    'smtp_pass' => 'premiceinformatique',
    //'smtp_crypto' => 'ssl', //can be 'ssl' or 'tls' for example
    'mailtype' => 'html', //plaintext 'text' mails or 'html'
    'smtp_timeout' => '4', //in seconds
    'charset' => 'UTF-8', //iso-8859-1\r\n
    'wordwrap' => TRUE 
);*/

$config = array(
    'protocol' => 'smtp',
    'smtp_host' => 'mail.premicecomputer.com', //'ssl://smtp.gmail.com';
    'smtp_port' => '587', //465
    'smtp_timeout' => '60',

    'smtp_user' => 'premicecomputer@premicecomputer.com',    //Important
    'smtp_pass' => 'premiceinformatique',  //Important

    'charset' => 'utf-8',
    'newline' => "\r\n",
    'mailtype' => 'html', //plaintext 'text' mails or 'html'
    'validation' => TRUE, // bool whether to validate email or not 
    'wordwrap' => TRUE 
);


