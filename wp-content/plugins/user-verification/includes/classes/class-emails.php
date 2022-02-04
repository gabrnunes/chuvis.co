<?php
if ( ! defined('ABSPATH')) exit;  // if direct access

class class_user_verification_emails{
	
	public function __construct(){


		}

    public function send_email($email_data){

        $email_data = apply_filters('user_verification_email_data',$email_data);


        $email_to = isset($email_data['email_to']) ? $email_data['email_to'] : '';
        $email_bcc = isset($email_data['email_bcc']) ? $email_data['email_bcc'] : '';

        $email_from = isset($email_data['email_from']) ? $email_data['email_from'] : get_option('admin_email');
        $email_from_name = isset($email_data['email_from_name']) ? $email_data['email_from_name'] : get_bloginfo('name');

        $reply_to = isset($email_data['reply_to']) ? $email_data['reply_to'] : get_option('admin_email');
        $reply_to_name = isset($email_data['reply_to_name']) ? $email_data['reply_to_name'] : get_bloginfo('name');

        $subject = isset($email_data['subject']) ? $email_data['subject'] : '';
        $email_body = isset($email_data['html']) ? $email_data['html'] : '';
        $attachments = isset($email_data['attachments']) ? $email_data['attachments'] : '';


        $headers = array();
        $headers[] = "From: ".$email_from_name." <".$email_from.">";

        if(!empty($reply_to)){
            $headers[] = "Reply-To: ".$reply_to_name." <".$reply_to.">";
        }

        $headers[] = "MIME-Version: 1.0";
        $headers[] = "Content-Type: text/html; charset=UTF-8";
        if(!empty($email_bcc)){
            $headers[] = "Bcc: ".$email_bcc;
        }

        $headers = apply_filters('user_verification_mail_headers', $headers);





        $status = wp_mail($email_to, $subject, $email_body, $headers, $attachments);


//        if($status){
//            error_log('mail sent');
//        }else{
//            error_log('mail not sent');
//        }

        return $status;

    }


		
		
	public function email_templates_data(){
		
		$templates_data_html = array();
		
		include user_verification_plugin_dir . 'templates/emails/user_registered.php';
		include user_verification_plugin_dir . 'templates/emails/email_confirmed.php';
		include user_verification_plugin_dir . 'templates/emails/email_resend_key.php';
        include user_verification_plugin_dir . 'templates/emails/send_mail_otp.php';

		$templates_data = array(
			'user_registered'=>array(
				'name'=>__('New user registered','user-verification'),
				'description'=>__('Notification email for admin when a new user is registered.','user-verification'),
				'subject'=>__('New user submitted - {site_url}','user-verification'),
				'html'=>$templates_data_html['user_registered'],
				'email_to'=>get_option('admin_email'),
				'email_from'=>get_option('admin_email'),
				'email_from_name'=> get_bloginfo('name'),																		
				'enable'=> 'yes',										
			),
			'email_confirmed'=>array(
				'name'=>__('New user confirmed','user-verification'),
				'description'=>__('Notification email for confirming a new user.','user-verification'),
				'subject'=>__('New user confirmed - {site_url}','user-verification'),
				'html'=>$templates_data_html['email_confirmed'],
				'email_to'=>get_option('admin_email'),
				'email_from'=>get_option('admin_email'),
				'email_from_name'=> get_bloginfo('name'),										
				'enable'=> 'yes',
			),
			'email_resend_key'=>array(
				'name'=>__('Resend activation key','user-verification'),
                 'description'=>__('Notification email for resend activation key.','user-verification'),
                 'subject'=>__('Please verify account - {site_url}','user-verification'),
                 'html'=>$templates_data_html['email_resend_key'],
                 'email_to'=>get_option('admin_email'),
                 'email_from'=>get_option('admin_email'),
                 'email_from_name'=> get_bloginfo('name'),
                 'enable'=> 'yes',
			),

            'send_mail_otp'=>array(
                'name'=>__('Send mail OTP','user-verification'),
                'description'=>__('Notification email for sending mail OTP.','user-verification'),
                'subject'=>__('OTP - {site_url}','user-verification'),
                'html'=>$templates_data_html['send_mail_otp'],
                'email_to'=>get_option('admin_email'),
                'email_from'=>get_option('admin_email'),
                'email_from_name'=> get_bloginfo('name'),
                'enable'=> 'yes',
            ),


		);
		
		$templates_data = apply_filters('user_verification_email_templates_data', $templates_data);
		
		return $templates_data;

		}
		


	public function email_templates_parameters(){

        $parameters['user_registered'] = array(
            '{site_name}' => __('Website title','user-verification'),
            '{site_description}' => __('Website tagline','user-verification'),
            '{site_url}' => __('Website URL','user-verification'),
            '{site_logo_url}' => __('Website logo URL','user-verification'),
            '{user_name}' => __('Username','user-verification'),
            '{user_display_name}' => __('User display name','user-verification'),
            '{first_name}' => __('User first name','user-verification'),
            '{last_name}' => __('User last name','user-verification'),
            '{user_avatar}' => __('User avatar','user-verification'),
            '{user_email}' => __('User email address','user-verification'),
            '{ac_activaton_url}' => __('Account activation URL','user-verification'),

            );

        $parameters['email_confirmed'] = array(
            '{site_name}' => __('Website title','user-verification'),
            '{site_description}' => __('Website tagline','user-verification'),
            '{site_url}' => __('Website URL','user-verification'),
            '{site_logo_url}' => __('Website logo URL','user-verification'),
            '{user_name}' => __('Username','user-verification'),
            '{user_display_name}' => __('User display name','user-verification'),
            '{first_name}' => __('User first name','user-verification'),
            '{last_name}' => __('User last name','user-verification'),
            '{user_avatar}' => __('User avatar','user-verification'),
            '{user_email}' => __('User email address','user-verification'),

        );

        $parameters['email_resend_key'] = array(
            '{site_name}' => __('Website title','user-verification'),
            '{site_description}' => __('Website tagline','user-verification'),
            '{site_url}' => __('Website URL','user-verification'),
            '{site_logo_url}' => __('Website logo URL','user-verification'),
            '{user_name}' => __('Username','user-verification'),
            '{user_display_name}' => __('User display name','user-verification'),
            '{first_name}' => __('User first name','user-verification'),
            '{last_name}' => __('User last name','user-verification'),
            '{user_avatar}' => __('User avatar','user-verification'),
            '{user_email}' => __('User email address','user-verification'),
            '{ac_activaton_url}' => __('Account activation URL','user-verification'),

            );

        $parameters['send_mail_otp'] = array(
            '{site_name}' => __('Website title','user-verification'),
            '{site_description}' => __('Website tagline','user-verification'),
            '{site_url}' => __('Website URL','user-verification'),
            '{site_logo_url}' => __('Website logo URL','user-verification'),
            '{user_name}' => __('Username','user-verification'),
            '{user_display_name}' => __('User display name','user-verification'),
            '{first_name}' => __('User first name','user-verification'),
            '{last_name}' => __('User last name','user-verification'),
            '{user_avatar}' => __('User avatar','user-verification'),
            '{user_email}' => __('User email address','user-verification'),
            '{otp_code}' => __('OTP','user-verification'),

        );



        $parameters = apply_filters('user_verification_email_templates_parameters',$parameters);

        return $parameters;
	}
}
	
