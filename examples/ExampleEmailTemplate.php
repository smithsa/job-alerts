<?php
require_once dirname(__FILE__).'/../vendor/autoload.php';

class ExampleEmailTemplate extends EmailTemplate
{
    /**
     * Class constructor
     * child class of EmailTemplate
     * @param associative array the configuration variable in the config.php file
     */
    public function __construct($config) {
        parent::__construct($config);
    }

    /**
     *  helper function to sanitize the description, will strip html tags
     *
     * @param string the function name that will serve as the callback
     * @return string santized description
     */
    public function sanitizeDescription($job_description){
        $split_description =  explode('Job Description Summary:', $job_description);
        $split_description2 = explode('Job Description', $split_description[1]);
        $job_description = strip_tags($split_description2[0]);
        return $job_description;
    }

}