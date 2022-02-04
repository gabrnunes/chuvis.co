<?php
if ( ! defined('ABSPATH')) exit;  // if direct access

ob_start();
?>
    <div style="background: #f5f5f5; color: #333; font-size: 14px; line-height: 20px; font-family: Arial, sans-serif;">
        <div style="width: 600px; margin: 0 auto;">
            <div class="header" style="border-bottom: 1px solid #ddd; padding: 20px 0; text-align: center;">

                <strong style="font-size: 20px;">{site_name}</strong>

            </div>
            <div class="content" style="padding: 10px 0 40px;">
                <p style="font-size: 14px; line-height: 20px; color: #333; font-family: Arial, sans-serif;"><?php echo __('{user_name} has successfully joined to {site_name}','user-verification'); ?></p>

            </div>
            <div class="footer" style="border-top: 1px solid #ddd; padding: 20px 0; clear: both; text-align: center;"><small style="font-size: 11px;">{site_name} - {site_description}</small></div>
        </div>
    </div>
<?php

$templates_data_html['email_confirmed'] = ob_get_clean();