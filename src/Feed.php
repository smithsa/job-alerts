<?php
require_once dirname(__FILE__).'/../vendor/autoload.php';

class Feed {
	public 	$config 		=  array();
	public 	$feed_config 	=  array();

	/** 
	* Class constructor
	* @param (associative array) the configuration variable in the config.php file
	*/
	function __construct($config) {
		$this->config = $config;
		$this->feed_config = $this->config['feed'];
    }

    /** 
	* Gets the raw feed data provided by the feed url defined in the configuration file
	* @return (string) jobs feed data
	*/
	public function getXMLFeed() {
		$feed_url = $this->feed_config['url'];
		$feed_username = $this->feed_config['username'];
		$feed_password = $this->feed_config['password'];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $feed_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);

		if(!empty($feed_username) && !empty($feed_password)){
			curl_setopt($ch, CURLOPT_USERPWD, $feed_username.':'.$feed_password);
		}else if(empty($feed_username) && !empty($feed_password)){
			throw new Exception("Empty username provided for the feed in config.php.");
		}else if(!empty($feed_username) && empty($feed_password)){
			throw new Exception("Empty password provided for for the feed in config.php.");
		}
		
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}

  	/** 
	* converts raw XML string data into a php object using simplexml_load_string
	* @return (object) object of class SimpleXMLElement representing the jobs feed data
	*/
	public function convertXMLStringtoPHPObject($xml_string) {
		$php_ob = simplexml_load_string($xml_string);
		return $php_ob->children('wd', true)->Report_Entry;
	}

  	/** 
	* returns a php object representative of an xml string
	* @return (object) object of class SimpleXMLElement representing the jobs feed data
	*/
	public function getXMLFeedObject() {
		$xml_string = $this->getXMLFeed();		
		$simplexml = $this->convertXMLStringtoPHPObject($xml_string);
		return $simplexml;
	}
}