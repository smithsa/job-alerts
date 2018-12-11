<?php

require_once dirname(__FILE__).'/../vendor/autoload.php';

class EmailTemplate {
    public $config;
    public $feed;
    public $jobs;
    private $headerSection;
    private $updateSection;
    private $footerSection;
    /**
     * Class constructor
     * @param associative array the configuration variable in the config.php file
     */
    function __construct($config) {
        $this->config = $config;
        $this->feed = $this->config['feed'];
        $this->email_styles = $this->config['mail_styles'];
        $this->jobs = array();
        $this->headerSection = '';
        $this->updateSection = '';
        $this->footerSection = '';
    }

    /**
     *  gets the template parts
     *
     * @return associative array of strings that have the template parts
     */
    public function getTemplateParts(){
        $email_parts = array(
            'header' => $this->headerSection,
            'footer' => $this->updateSection,
            'update' => $this->footerSection
        );

        return $email_parts;
    }

    /**
     *  sets the header section of template
     *
     * param array of job positions
     */
    public function setHeaderSection($job_positions = array()){
        if(empty($job_positions)){
            $job_positions = $this->jobs;
        }
        $html = '';
        $map_fields = $this->feed['map_fields'];
        if($this->feed['is_augmented_feed']){ //reading from our augmented feed
            foreach($job_positions as $job_position){
                $html.= $this->generateSingJobPosting($job_position['job_id'], $job_position['city'].', '.$job_position['state'], $job_position['description'], $job_position['apply_url']);
            }
        }else{ //reading directly from the feed
            foreach($job_positions as $job_position){
                $html.= $this->generateSingJobPosting($job_position[$map_fields['job_id']], $job_position[$map_fields['city']].', '.$job_position[$map_fields['state']], $job_position[$map_fields['description']], $job_position[$map_fields['apply_url']]);
            }
        }

        $this->headerSection = $html;

    }

    /**
     *  gets the header section of template
     *
     * @return string of the jobs section
     */
    public function getHeaderSection(){
        return $this->headerSection;
    }

    /**
     *  sets the footer of the email
     *
     * @param update preference link
     * @param unsubscribe link
     * @param the type of email by name, can be Job Alert or Talent Community
     */
    public function setFooterSection($update_preference_link, $unsubscribe_link, $entity){
        $this->footerSection = 'You can <a href="'.$update_preference_link.'">update your preferences</a> or <a href="'.$unsubscribe_link.'">remove yourself from the '.$entity.'.</a>';
    }

    /**
     *  gets the footer of the email
     *
     * @return string of the footer section
     */
    public function getFooterSection(){
        return $this->footerSection;
    }

    /**
     *  sets the update section of of the email
     *
     * @param update preference link
     */
    public function setUpdateSection($update_preference_link){
        $this->updateSection = '<a class="mcnButton " title="Update Preferences" href="'.$update_preference_link.'" target="_blank" style="font-weight: bold;letter-spacing: normal;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;">Update Preferences</a>';
    }

    /**
     *  gets the update section of of the email
     *
     * @return string of the footer section
     */
    public function getUpdateSection(){
        return $this->updateSection;
    }


    /**
     *  generates a positing in the email
     *
     * param string representative of the job title
     * param string representative of the job location
     * param string representative of the job description
     * param string representative of the job apply url
     * @return string of the featured job
     */
    public function generateSingJobPosting($job_title, $job_location, $job_description, $job_apply_url){
        return '<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="min-width:100%;">
            <tbody class="mcnTextBlockOuter">
                <tr>
                    <td valign="top" class="mcnTextBlockInner" style="padding-top:9px;">
                        <!--[if mso]><table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;"><tr> <![endif]-->
                        <!--[if mso]><td valign="top" width="600" style="width:600px;"> <![endif]-->
                        <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%; min-width:100%;" width="100%" class="mcnTextContentContainer">
                            <tbody>
                                <tr>
                                    <td valign="top" class="mcnTextContent" style="padding-top:0; padding-right:18px; padding-bottom:9px; padding-left:18px;"><span style="font-family:open sans,helvetica neue,helvetica,arial,sans-serif"><strong><span style="font-size:24px">'.$job_title.'</span></strong> <br> <strong><span style="color:#E37C00"><span style="font-size:17px">'.$job_location.'</span></span></strong></span>
                                        <p><span style="font-family:open sans,helvetica neue,helvetica,arial,sans-serif"><span style="font-size:14px">'.substr(strip_tags($job_description), 0, strpos(strip_tags($job_description), ' ', 165)).'...&nbsp;</span></span>
                                        </p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!--[if mso]></td> <![endif]-->
                        <!--[if mso]></tr></table> <![endif]-->
                    </td>
                </tr>
            </tbody>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnButtonBlock" style="min-width:100%;">
            <tbody class="mcnButtonBlockOuter">
                <tr>
                    <td style="padding-top:0; padding-right:18px; padding-bottom:9px; padding-left:18px;" valign="top" align="left" class="mcnButtonBlockInner">
                        <table border="0" cellpadding="0" cellspacing="0" class="mcnButtonContentContainer" style="border-collapse: separate !important;border-radius: 3px;background-color: #00355F;">
                            <tbody>
                                <tr>
                                    <td align="center" valign="middle" class="mcnButtonContent" style="font-family: Arial; font-size: 16px; padding: 14px;"> <a class="mcnButton " title="View Job Description" href="'.$job_apply_url.'" target="_blank" style="font-weight: bold;letter-spacing: normal;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;">View Job Description</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnDividerBlock" style="min-width:100%;">
            <tbody class="mcnDividerBlockOuter">
                <tr>
                    <td class="mcnDividerBlockInner" style="min-width:100%; padding:8px;">
                        <table class="mcnDividerContent" border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 100%;border-top: 2px solid #EAEAEA;">
                            <tbody>
                                <tr>
                                    <td> <span></span></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>';
    }

}