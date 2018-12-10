<?php

require_once dirname(__FILE__).'/../vendor/autoload.php';

class JobAlert {
    public 	$config 		=  array();
    public 	$mandrill_config 	=  array();
    public 	$mailChimp_config 	=  array();
    /**
     * Class constructor
     * @param associative array the configuration variable in the config.php file
     */
    function __construct($config) {
        $this->config = $config;
        $this->mailChimp_config = $this->config['mail_chimp'];
        $this->mandrill_config = $this->config['mandrill'];
        $this->mail_config = $this->config['mail'];
        $this->mail_styles_config = $this->config['mail_styles'];
    }

    

    // todo send job alerts here
    // logic to determine if to send here

//$template_content = array(
//array(
//'name' => 'header',
//'content' => $content
//),
//array(
//'name' => 'id',
//'content' => $footer
//),
//array(
//'name' => 'update',
//'content' => $update
//)
//);
}