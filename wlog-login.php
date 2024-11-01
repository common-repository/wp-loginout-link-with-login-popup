<?php
/**
 * Plugin Name: WP login/out link with login popup
 * Plugin URI: https://wordpress.org/plugins/wp-loginout-link-with-login-popup/
 * Description: This plugin adds login/ logout link to the navigation menu accordinng to the user login status and redirect user after login/ logout. 
 * Author: Priyanka Bhave
 * Requires at least: 4.0
 * Tested up to: 4.6
 * Version: 1.0
 */

$class = new WLOG_initiate_login();

final class WLOG_initiate_login {
    public function __construct() {
        $this->WLOG_initiase_constant(); 
        $this->WLOG_init_hooks();                       
    }
    public function WLOG_init_hooks() {
        register_activation_hook(__FILE__,array($this, 'WLOG_activating_plugincode'));
        register_deactivation_hook(__FILE__,array($this, 'WLOG_deactivating_plugincode'));
        include_once WLOG_direct_path.'includes/load-options.php';
        new WLOG_options_login();
    }

    public function WLOG_initiase_constant(){
        define('WLOG_direct_path', plugin_dir_path(__FILE__));
        define('WLOG_direct_url', plugin_dir_url(__FILE__));
    }
    public function WLOG_activating_plugincode(){
        if(!is_admin() || !current_user_can('manage_options'))
            return;
        update_option('WLOG_status','activated');
    }
    public function WLOG_deactivating_plugincode(){
        if(!is_admin() || !current_user_can('manage_options'))
            return;
        delete_option('WLOG_status');
    }
}
