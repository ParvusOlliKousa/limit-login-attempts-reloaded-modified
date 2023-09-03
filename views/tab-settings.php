<?php

use LLARS\Core\Config;
use LLARS\Core\Helpers;

if( !defined( 'ABSPATH' ) ) exit();

/**
 * @var $this LLARS\Core\LimitLoginAttempts
 */

$gdpr = Config::get( 'gdpr' );
$gdpr_message = Config::get( 'gdpr_message' );

$v = explode( ',', Config::get( 'lockout_notify' ) );
$email_checked = in_array( 'email', $v ) ? ' checked ' : '';

$show_top_level_menu_item = Config::get( 'show_top_level_menu_item' );
$show_top_bar_menu_item = Config::get( 'show_top_bar_menu_item' );
$hide_dashboard_widget = Config::get( 'hide_dashboard_widget' );
$show_warning_badge = Config::get( 'show_warning_badge' );

$admin_notify_email = Config::get( 'admin_notify_email' );
$admin_email_placeholder = ( !is_network_admin() ) ? get_option( 'admin_email' ) : get_site_option( 'admin_email' );

$trusted_ip_origins = Config::get( 'trusted_ip_origins' );
$trusted_ip_origins = ( is_array( $trusted_ip_origins ) && !empty( $trusted_ip_origins ) ) ? implode( ", ", $trusted_ip_origins ) : 'REMOTE_ADDR';

$active_app = Config::get( 'active_app' );
$app_setup_code = Config::get( 'app_setup_code' );
$active_app_config = Config::get( 'app_config' );

?>


<h3><?php echo __( 'General Settings', 'limit-login-attempts-reloaded' ); ?></h3>
<form action="<?php echo $this->get_options_page_uri('settings'); ?>" method="post">

    <?php wp_nonce_field( 'limit-login-attempts-options' ); ?>

    <?php if ( is_network_admin() ): ?>
    <input type="checkbox" name="allow_local_options" <?php echo Config::get( 'allow_local_options' ) ? 'checked' : '' ?> value="1"/> <?php esc_html_e( 'Let network sites use their own settings', 'limit-login-attempts-reloaded' ); ?>
        <p class="description"><?php esc_html_e('If disabled, the global settings will be forcibly applied to the entire network.', 'limit-login-attempts-reloaded') ?></p>
    <?php elseif ( Helpers::is_network_mode() ): ?>
    <input type="checkbox" name="use_global_options" <?php echo Config::get('use_local_options' ) ? '' : 'checked' ?> value="1" class="use_global_options"/> <?php echo __( 'Use global settings', 'limit-login-attempts-reloaded' ); ?><br/>
        <script>
            jQuery(function($){
                var first = true;
                $('.use_global_options').change( function(){
                    var form = $('.llar-settings-wrap');
                    form.stop();

                    if ( this.checked )
                        first ? form.hide() : form.fadeOut();
                    else
                        first ? form.show() : form.fadeIn();

                    first = false;
                }).change();
            });
        </script>
    <?php endif ?>

    <div class="llar-settings-wrap">
        <table class="form-table">
            <tr>
                <th scope="row"
                    valign="top"><?php echo __( 'GDPR compliance', 'limit-login-attempts-reloaded' ); ?></th>
                <td>
                    <input type="checkbox" name="gdpr" value="1" <?php if($gdpr): ?> checked <?php endif; ?>/>
				    <?php echo __( 'this makes the plugin <a href="https://gdpr-info.eu/" target="_blank">GDPR</a> compliant by showing a message on the login page. <a href="https://www.limitloginattempts.com/gdpr-qa/?from=plugin-settings-gdpr" target="_blank">Read more</a>', 'limit-login-attempts-reloaded' ); ?> <br/>
                </td>
            </tr>
            <tr>
                <th scope="row"
                    valign="top"><?php echo __( 'GDPR message', 'limit-login-attempts-reloaded' ); ?>
                    <i class="llar-tooltip" data-text="<?php esc_attr_e( 'This message will appear at the bottom of the login page.', 'limit-login-attempts-reloaded' ); ?>">
                        <span class="dashicons dashicons-editor-help"></span>
                    </i></th>
                <td>
                    <textarea name="gdpr_message" cols="60"><?php echo esc_textarea( stripslashes( $gdpr_message ) ); ?></textarea>
                    <p class="description"><?php echo __( 'You can use a shortcode here to insert links, for example, a link to your Privacy Policy page. <br>The shortcode is: [llar-link url="https://example.com" text="Privacy Policy"]', 'limit-login-attempts-reloaded' ); ?></p>
                </td>
            </tr>
            
            <tr >
                <div style="display:none!important;">

                    <input type="checkbox" name="lockout_notify_email" <?php echo $email_checked; ?>
                           value="email"/> <?php echo __( 'Email to', 'limit-login-attempts-reloaded' ); ?>
                    <input type="email" name="admin_notify_email"
                           value="<?php echo esc_attr( $admin_notify_email ) ?>"
                           placeholder="<?php echo esc_attr( $admin_email_placeholder ); ?>"/> <?php echo __( 'after', 'limit-login-attempts-reloaded' ); ?>
                    <input type="text" size="3" maxlength="4"
                           value="<?php echo( Config::get( 'notify_email_after' ) ); ?>"
                           name="email_after"/> <?php echo __( 'lockouts', 'limit-login-attempts-reloaded' ); ?>

                    <input type="checkbox" name="show_top_bar_menu_item" <?php checked( $show_top_bar_menu_item ); ?>> <?php _e( '(Save and reload this page to see the changes)', 'limit-login-attempts-reloaded' ) ?>

                    <input type="checkbox" name="show_top_level_menu_item" <?php checked( $show_top_level_menu_item ); ?>> <?php _e( '(Save and reload this page to see the changes)', 'limit-login-attempts-reloaded' ) ?>
     
                    <input type="checkbox" name="hide_dashboard_widget" <?php checked( $hide_dashboard_widget ); ?>>
           
            
                    <input type="checkbox" name="show_warning_badge" <?php checked( $show_warning_badge ); ?>> <?php _e( '(Save and reload this page to see the changes)', 'limit-login-attempts-reloaded' ) ?>
          
                    <select name="active_app" id="">
                        <option value="local" <?php selected( $active_app, 'local' ); ?>><?php echo __( 'Local', 'limit-login-attempts-reloaded' ); ?></option>
					    <?php if( $active_app_config ) : ?>
                            <option value="custom" <?php selected( $active_app, 'custom' ); ?>><?php echo esc_html( $active_app_config['name'] ); ?></option>
					    <?php endif; ?>
                    </select>
                </div>
            </tr>
            <tr>
                        <th scope="row" valign="top"><?php echo __( 'Lockout', 'limit-login-attempts-reloaded' ); ?>
                            <i class="llar-tooltip" data-text="<?php esc_attr_e( 'Set lockout limits based on failed attempts.', 'limit-login-attempts-reloaded' ); ?>">
                                <span class="dashicons dashicons-editor-help"></span>
                            </i></th>
                        <td>
                            <input type="text" size="3" maxlength="4"
                                   value="<?php echo( Config::get( 'allowed_retries' ) ); ?>"
                                   name="allowed_retries"/> <?php echo __( 'allowed retries', 'limit-login-attempts-reloaded' ); ?>
                            <i class="llar-tooltip" data-text="<?php esc_attr_e( 'Number of failed attempts allowed before locking out.', 'limit-login-attempts-reloaded' ); ?>">
                                <span class="dashicons dashicons-editor-help"></span>
                            </i>
                            <br/>
                            <input type="text" size="3" maxlength="4"
                                   value="<?php echo( Config::get( 'lockout_duration' ) / 60 ); ?>"
                                   name="lockout_duration"/> <?php echo __( 'minutes lockout', 'limit-login-attempts-reloaded' ); ?>
                            <i class="llar-tooltip" data-text="<?php esc_attr_e( 'Lockout time in minutes.', 'limit-login-attempts-reloaded' ); ?>">
                                <span class="dashicons dashicons-editor-help"></span>
                            </i>
                            <br/>
                            <input type="text" size="3" maxlength="4"
                                   value="<?php echo( Config::get( 'allowed_lockouts' ) ); ?>"
                                   name="allowed_lockouts"/> <?php echo __( 'lockouts increase lockout time to', 'limit-login-attempts-reloaded' ); ?>
                            <input type="text" size="3" maxlength="4"
                                   value="<?php echo( Config::get( 'long_duration' ) / 3600 ); ?>"
                                   name="long_duration"/> <?php echo __( 'hours', 'limit-login-attempts-reloaded' ); ?>
                            <i class="llar-tooltip" data-text="<?php esc_attr_e( 'After the specified number of lockouts the lockout time will increase by specified hours.', 'limit-login-attempts-reloaded' ); ?>">
                                <span class="dashicons dashicons-editor-help"></span>
                            </i>
                            <br/>
                            <input type="text" size="3" maxlength="4"
                                   value="<?php echo( Config::get( 'valid_duration' ) / 3600 ); ?>"
                                   name="valid_duration"/> <?php echo __( 'hours until retries are reset', 'limit-login-attempts-reloaded' ); ?>
                            <i class="llar-tooltip" data-text="<?php esc_attr_e( 'Time in hours before blocks are removed.', 'limit-login-attempts-reloaded' ); ?>">
                                <span class="dashicons dashicons-editor-help"></span>
                            </i>
                            <p class="description">
                            <?php echo sprintf(
                                __( 'After a specific IP address fails to log in <b>%1$s</b> times, a lockout lasting <b>%2$s</b> minutes is activated. If additional failed attempts occur within <b>%3$s</b> hours and lead to another lockout, once their combined total hits <b>%4$s</b>, the <b>%2$s</b> minutes duration is extended to <b>%5$s</b> hours. The lockout will be lifted once <b>%3$s</b> hours have passed since the last lockout incident.', 'limit-login-attempts-reloaded' ),
	                            Config::get( 'allowed_retries' ),
	                            Config::get( 'lockout_duration' ) / 60,
	                            Config::get( 'valid_duration' ) / 3600,
	                            Config::get( 'allowed_lockouts' ),
	                            Config::get( 'long_duration' ) / 3600
                            ); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"
                            valign="top"><?php echo __( 'Trusted IP Origins', 'limit-login-attempts-reloaded' ); ?>
                            <i class="llar-tooltip" data-text="<?php esc_attr_e( 'Server variables containing IP addresses.', 'limit-login-attempts-reloaded' ); ?>">
                                <span class="dashicons dashicons-editor-help"></span>
                            </i></th>
                        <td>
                            <div class="field-col">
                                <input type="text" class="regular-text" style="width: 100%;max-width: 431px;" name="LLAS_trusted_ip_origins" value="<?php echo esc_attr( $trusted_ip_origins ); ?>">
                                <p class="description"><?php _e( 'Specify the origins you trust in order of priority, separated by commas. We strongly recommend that you <b>do not</b> use anything other than REMOTE_ADDR since other origins can be easily faked. Examples: HTTP_X_FORWARDED_FOR, HTTP_CF_CONNECTING_IP, HTTP_X_SUCURI_CLIENTIP', 'limit-login-attempts-reloaded' ); ?></p>
                            </div>
                        </td>
                    </tr>
        </table>

      
    </div>

    <script type="text/javascript">
        (function($){

            $(document).ready(function(){

                $( "#llar-apps-accordion" ).accordion({
                    heightStyle: "content",
                    active: <?php echo ( $active_app === 'local' ) ? 0 : 1; ?>
                });

                var $app_ajax_spinner = $('.llar-app-ajax-spinner'),
                    $app_ajax_msg = $('.llar-app-ajax-msg'),
                    $app_config_field = $('#limit-login-app-config');

                if($app_config_field.val()) {
                    var pretty = JSON.stringify(JSON.parse($app_config_field.val()), undefined, 2);
                    $app_config_field.val(pretty);
                }

                $('#limit-login-app-setup').on('click', function(e) {
                    e.preventDefault();

                    $app_ajax_msg.text('').removeClass('success error');
                    $app_ajax_spinner.css('visibility', 'visible');

                    var setup_code = $('#limit-login-app-setup-code').val();

                    $.post(ajaxurl, {
                        action: 'app_setup',
                        code: setup_code,
                        sec: '<?php echo esc_js( wp_create_nonce( "llar-action" ) ); ?>',
                        is_network_admin: <?php echo esc_js( is_network_admin() ? 1 : 0 ); ?>
                    }, function(response){

                        if(!response.success) {

                            $app_ajax_msg.addClass('error');
                        } else {

                            $app_ajax_msg.addClass('success');

                            setTimeout(function(){

                                window.location = window.location + '&llar-cloud-activated';

                            }, 1000);
                        }

                        if(!response.success && response.data.msg) {

                            $app_ajax_msg.text(response.data.msg);
                        }

                        $app_ajax_spinner.css('visibility', 'hidden');

                        setTimeout(function(){

                            $app_ajax_msg.text('').removeClass('success error');

                        }, 5000);
                    });

                });

                $('.llar-toggle-setup-field').on('click', function(e){
                    e.preventDefault();

                    $(this).hide();

                    $('.setup-code-wrap').toggleClass('active');
                    $('.app-form-field').toggleClass('active');
                });

            });

        })(jQuery);
    </script>

    <p class="submit">
        <input class="button button-primary" name="llar_update_settings" value="<?php echo __( 'Save Settings', 'limit-login-attempts-reloaded' ); ?>"
               type="submit"/>
    </p>
</form>

