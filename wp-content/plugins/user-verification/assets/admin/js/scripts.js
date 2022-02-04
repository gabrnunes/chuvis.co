jQuery(document).ready(function($){




		$(document).on('click', '.reset-email-templates', function()
			{

				if(confirm( L10n_user_verification.reset_confirm_text )){
					
					$.ajax(
						{
					type: 'POST',
					context: this,
					url:uv_ajax.uv_ajaxurl,
					data: {"action": "user_verification_reset_email_templates", },
					success: function(data)
							{	
							
								$(this).val('Reset Done');
							
								location.reload();
							}
						});
					
					}

				})













	});	







