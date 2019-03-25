<?php

require_once dirname(__FILE__).'/../vendor/autoload.php';

class EmailTemplate {
    public $config;
    public $feed;
    public $jobs;
    public $description_is_sanitized;
    private $jobsSection;
    private $updateSection;
    private $footerSection;

    /**
     * Class constructor
     * @param associative array the configuration variable in the config.php file
     */
    function __construct($config) {
        $this->config = $config;
        $this->feed = $this->config['feed'];
        $this->mail_styles = $this->config['mail_styles'];
        $this->jobs = array();
        $this->headerSection = '';
        $this->updateSection = '';
        $this->footerSection = '';
        $this->description_is_sanitized = false;
    }

    /**
     *  gets the template parts
     *
     * @return associative array of strings that have the template parts
     */
    public function getTemplateParts(){
        $email_parts = array(
            'header' => $this->jobsSection,
            'footer' => $this->updateSection,
            'update' => $this->footerSection
        );

        return $email_parts;
    }

    /**
     *  sets the jobs section of template
     *
     * param array of job positions
     */
    public function setJobSection($job_positions = array()){
        if(empty($job_positions)){
            $job_positions = $this->jobs;
        }
        $html = '';
        $map_fields = $this->feed['map_fields'];
        if($this->feed['is_augmented_feed']){ //reading from our augmented feed
            foreach($job_positions as $job_position){
                if($this->description_is_sanitized){
                    $treated_description =  $this->sanitizeDescription($job_position['description']);
                    $html.= $this->generateSingJobPosting($job_position['job_id'], $job_position['title'], $job_position['city'].', '.$job_position['state'], $treated_description, $job_position['job_url']);

                }else{
                    $html.= $this->generateSingJobPosting($job_position['job_id'], $job_position['title'], $job_position['city'].', '.$job_position['state'], $job_position['description'], $job_position['job_url']);

                }
            }
        }else{ //reading directly from the feed
            foreach($job_positions as $job_position){
                if($this->description_is_sanitized){
                    $treated_description = $this->sanitizeDescription($job_position[$map_fields['description']]);
                    $html.= $this->generateSingJobPosting($job_position['job_id'], $job_position[$map_fields['title']], $job_position[$map_fields['city']].', '.$job_position[$map_fields['state']], $treated_description, $job_position[$map_fields['job_url']]);
                }else{
                    $html.= $this->generateSingJobPosting($job_position['job_id'], $job_position[$map_fields['title']], $job_position[$map_fields['city']].', '.$job_position[$map_fields['state']], $job_position[$map_fields['description']], $job_position[$map_fields['job_url']]);
                }
            }
        }

        $this->jobsSection = $html;

    }

    /**
     *  gets the header section of template
     *
     * @return string of the jobs section
     */
    public function getJobSection(){
        return $this->jobsSection;
    }

    /**
     *  sets the footer of the email
     *
     * @param update preference link
     * @param unsubscribe link
     * @param the type of email by name, can be Job Alert or Talent Community
     */
    public function setFooterSection($update_preference_link, $unsubscribe_link, $entity){
        $footerBodyColor = $this->mail_styles['footer_font_color'];
        $this->footerSection = '<span style="color:'.$footerBodyColor.'">Want to change how you receive these emails?</span><br>
               <span style="color:'.$footerBodyColor.'">You can </span><a style="color:'.$footerBodyColor.'; text-decoration: underline" href="'.$update_preference_link.'">update</a><span style="color:'.$footerBodyColor.'"> your preferences or </span><a style="color:'.$footerBodyColor.'; text-decoration: underline" href="'.$unsubscribe_link.'">unsubscribe</a> <span style="color:'.$footerBodyColor.'">' .$entity.'.</span><br>';

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
        $this->updateSection = '<a class="mcnButton " title="Update Preferences" href="'.$update_preference_link.'" target="_blank" style="font-weight: normal;letter-spacing: 2px;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;">Update Preferences</a>';
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
     *  helper function to sanitize the description, will strip html tags
     *
     * @param string the function name that will serve as the callback
     * @return string santized description
     */
    public function sanitizeDescription($job_description){
        return strip_tags($job_description);
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
    public function generateSingJobPosting($job_id, $job_title, $job_location, $job_description, $job_url){
        $primaryColor = $this->mail_styles['primary_color'];
        $bodyColor = $this->mail_styles['body_font_color'];
        return '<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="min-width:100%;">
    <tbody class="mcnTextBlockOuter">
        <tr>
            <td valign="top" class="mcnTextBlockInner" style="padding-top:9px;">
              	<!--[if mso]>
				<table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;">
				<tr>
				<![endif]-->

				<!--[if mso]>
				<td valign="top" width="600" style="width:600px;">
				<![endif]-->
                <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%; min-width:100%;" width="100%" class="mcnTextContentContainer">
                    <tbody><tr>

                        <td valign="top" class="mcnTextContent" style="padding-top:0; padding-right:18px; padding-bottom:9px; padding-left:18px;">

                            <span style="font-size:20px"><span style="color:'.$primaryColor.'"><strong>'.$job_title.'</strong></span></span><br>
<span style="color:'.$bodyColor.'"><strong><span style="font-size:14px">'.$job_location.'</span></strong></span><br>
<br>
<span style="color:'.$bodyColor.'">'.$job_description.'</span><br/><br/>
                        </td>
                    </tr>
                </tbody></table>
				<!--[if mso]>
				</td>
				<![endif]-->

				<!--[if mso]>
				</tr>
				</table>
				<![endif]-->
            </td>
        </tr>
    </tbody>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnButtonBlock" style="min-width:100%;">
    <tbody class="mcnButtonBlockOuter">
        <tr>
            <td style="padding-top:0; padding-right:18px; padding-bottom:18px; padding-left:18px;" valign="top" align="left" class="mcnButtonBlockInner">
                <table border="0" cellpadding="0" cellspacing="0" class="mcnButtonContentContainer" style="border-collapse: separate !important;border-radius: 3px;background-color: #0092A5;">
                    <tbody>
                        <tr>
                            <td align="center" valign="middle" class="mcnButtonContent" style="font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, Verdana, sans-serif; font-size: 14px; padding: 15px;">
                                <a class="mcnButton " title="View Job Description" href="'.( !empty($job_url) ? $job_url : 'https://optioncare.com/posting/?id='.$job_id).'" target="_blank" style="font-weight: normal;letter-spacing: 2px;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;">View Job Description</a>
                            </td>
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
            <td class="mcnDividerBlockInner" style="min-width:100%; padding:18px;">
                <table class="mcnDividerContent" border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 100%;border-top: 2px solid #FFFFFF;">
                    <tbody><tr>
                        <td>
                            <span></span>
                        </td>
                    </tr>
                </tbody></table>
<!--
                <td class="mcnDividerBlockInner" style="padding: 18px;">
                <hr class="mcnDividerContent" style="border-bottom-color:none; border-left-color:none; border-right-color:none; border-bottom-width:0; border-left-width:0; border-right-width:0; margin-top:0; margin-right:0; margin-bottom:0; margin-left:0;" />
-->
            </td>
        </tr>
    </tbody>
</table>';
    }

}