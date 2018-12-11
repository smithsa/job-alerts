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

    /**
    * turn array of data into an xmlstring
    *
    * @param array of objects for the subscriber
    * @param string that represents the email's subject
     *
    * @return array of associative array of requests that were sent an alert
    */
    public function sendAlerts($subscribers, $email_subject){

        $alerts_sent = array();
        foreach($subscribers as $subscriber){

            if($subscriber->status !== 'subscribed'){
                continue;
            }

            //Getting User's Name
            $user_names = array();
            $current_first_name = $subscriber->merge_fields->FNAME;
            array_push($user_names, $current_first_name);
            $current_last_name = '';
            if(property_exists($subscriber->merge_fields, 'LNAME')){
                $current_last_name = $subscriber->merge_fields->LNAME;
                array_push($user_names, $current_last_name);
            }

            $user_name = implode(' ', $user_names);

            //Getting User's Email
            $current_email = $subscriber->email_address;

            //Getting User's Alert Preference (Weekly or Daily)
            $current_alert_type = $subscriber->merge_fields->ALERTTYPE;

            //Getting User's Categories of Interest
            $current_categories = array();
            $current_category_1 = $subscriber->merge_fields->CAT1;
            if(!empty($current_category_1)) array_push($current_categories, $current_category_1);
            $current_category_2 = $subscriber->merge_fields->CAT2;
            if(!empty($current_category_2)) array_push($current_categories, $current_category_2);

            //Getting User's State
            $current_states = array();
            $current_state = $subscriber->merge_fields->STATE;
            if(empty($current_state)){
                array_push($current_states, "*");
            }else{
                array_push($current_states, $current_state);
            }

            $weekly_search_date = date('Y-m-d H:i:s', strtotime('Today - 1 week'));
            if($current_alert_type === 'Daily' || ($current_alert_type === 'Weekly' && date("l") === 'Tuesday')){
                //searching for jobs for user
                $feedDb = new FeedDatabase($this->config);
                $jobsFound = ($current_alert_type === 'Daily') ? $feedDb->searchFeedDatabase($current_states, $current_categories) : $feedDb->searchFeedDatabase($current_states, $current_categories, $weekly_search_date);

                //check if its daily alerts $current_alert_type
                if(!empty($jobsFound)){
                    //Creating the Email Template Content
                    $emailTemplate = new EmailTemplate($this->config);
                    $emailTemplate->setHeaderSection($jobsFound);
                    $emailTemplate->setFooterSection($this->mail_config['update_preference_link'], $this->mail_config['unsubscribe_link'], $this->mail_config['entity']);
                    $emailTemplate->setUpdateSection($this->mail_config['update_preference_link']);

                    $template_content = array(
                        array(
                            'name' => 'header',
                            'content' => $emailTemplate->getHeaderSection()
                        ),
                        array(
                            'name' => 'id',
                            'content' => $emailTemplate->getFooterSection()
                        ),
                        array(
                            'name' => 'update',
                            'content' => $emailTemplate->getUpdateSection()
                        )
                    );

                    //adding email to return array
                    //sending the Email!
                    $emailObject = new Email($this->config);
                    $emailResponse = $emailObject->sendTemplate($template_content, $email_subject, $current_email, $current_first_name);
                    array_push($alerts_sent, $emailResponse);
                }
            }

        }
        return $alerts_sent;
    }



}