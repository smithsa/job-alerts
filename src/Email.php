<?php

require_once dirname(__FILE__).'/../vendor/autoload.php';

class Email{
    /**
     * Class constructor
     * @param associative array the configuration variable in the config.php file
     */
    function __construct($config) {
        $this->config = $config;
        $this->mail_config = $this->config['mail'];
        $this->mandrill_config = $this->config['mandrill'];
    }

    public function sendTemplate($template_content, $email_subject, $user_email, $user_name, $send_at = ''){
        try {
            $mandrill = new Mandrill($this->mandrill_config['API_key']);
            $template_name = $this->mandrill_config['template_name'];

            $message = array(
                'subject' => $email_subject,
                'from_email' => $this->mail_config['from_email_address'],
                'from_name' => $this->mail_config['from_name'],
                'to' => array(
                    array(
                        'email' => $user_email,
                        'name' => $user_name,
                        'type' => 'to'
                    )
                ),
                'headers' => array('Reply-To' => $this->mail_config['reply_to']),
                'important' => false,
                'track_opens' => null,
                'track_clicks' => null,
                'auto_text' => null,
                'auto_html' => null,
                'inline_css' => null,
                'url_strip_qs' => null,
                'preserve_recipients' => null,
                'view_content_link' => null,
                'bcc_address' => 'message.bcc_address@example.com',
                'tracking_domain' => null,
                'signing_domain' => null,
                'return_path_domain' => null,
                'merge' => true,
                'merge_language' => 'mailchimp',
                'tags' => array('talent'),
                'subaccount' => $this->mail_config['subaccount'],

                'metadata' => array('website' => $this->mail_config['website_url']),
                'recipient_metadata' => array(
                    array(
                        'rcpt' => $user_email,
                        'values' => array('user_id' => 123456)
                    )
                )
            );
            $async = true;
            $ip_pool = 'Main Pool';
            if(empty($send_at)){
                $send_at = '2017-01-01 00:00:00';
            }
            $result = $mandrill->messages->sendTemplate($template_name, $template_content, $message, $async, $ip_pool, $send_at);

            return $result;
        } catch(Mandrill_Error $e) {
            // Mandrill errors are thrown as exceptions
            echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();

            throw $e;
            return $e;
        }
    }

}