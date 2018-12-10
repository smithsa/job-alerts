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
        $this->mailChimp_config = $this->config['mailChimp'];
        $this->mandrill_config = $this->config['mandrill'];
    }

}