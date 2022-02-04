jQuery(document).ready(function($){


	//$("label[for='user_login'").text("Username or Email Address or Phone Number");




	$(document).on('click', '#send-otp', function() {

		user_login = $('#user_login, #username').val();

        //console.log(user_login );
        messages = $('.otp-messages');
		//console.log(user_login.length );
        //console.log(grecaptcha.getResponse() );

        // 'g-recaptcha-response': grecaptcha.getResponse()

        if(user_login.length <= 0 || user_login == null){
            messages.append('<div class="otp-message error">\t<strong>Error</strong>: Username or email should not empty.<br></div>');

            setTimeout( function(){messages.empty();}, 5000 );

            return;
        }


        $(this).addClass('loading');

		$.ajax(
			{
				type: 'POST',
				context: this,
				url:user_verification_ajax.user_verification_ajaxurl,
				data: {"action": "user_verification_send_otp", 'user_login': user_login,  },
				success: function(response)
				{
					var data = JSON.parse( response );
					otp_via_mail = data['otp_via_mail'];
					otp_via_sms = data['otp_via_sms'];
                    error = data['error'];
					success_message = data['success_message'];

                    $(this).removeClass('loading');


                    //console.log(data);
					//console.log(data.success_message);

                    if(error){
                        messages.append(error);
                        setTimeout( function(){messages.empty();}, 5000 );

                    }

                    else{


                        messages.append(success_message);
                        setTimeout( function(){messages.empty();}, 5000 );


                        $('.user-pass-wrap, .forgetmenot, .submit, .lost_password').fadeIn('slow');
                        $('#user_pass').removeAttr('disabled');
                        $("label[for='user_pass']").text("Enter OTP");
                        $("label[for='password']").text("Enter OTP");

                        //WooCommerce
                        $('.woocommerce-form-login p').fadeIn('slow');


                    }

					//location.reload();
				}
			});



	})










})
