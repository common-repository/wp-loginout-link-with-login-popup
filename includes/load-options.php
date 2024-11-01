<?php
class WLOG_options_login {
	protected $items_classes='';
	public function __construct() {
		$this->WLOG_load_settings();        
	}
	public function WLOG_load_settings(){
		if(get_option('WLOG_status')){
			add_action('admin_menu', array($this,'WLOG_add_settings_page'));
			add_action('admin_enqueue_scripts', array($this,'WLOG_add_scripts'));
			add_action('wp_enqueue_scripts', array($this,'WLOG_add_scriptsFrontend'));
			add_filter( 'wp_nav_menu_items', array($this,'WLOG_add_loginout_link'), 10, 2 );
			add_filter( 'nav_menu_css_class', array($this,'WLOG_get_nav_class'), 10, 2 );
			add_action( 'admin_init', array($this,'WLOG_update_options'), 10, 2 );
			add_action( 'wp_ajax_nopriv_wlog_log_user', array($this,'WLOG_fn_login_user') );
		}
    }
    public function WLOG_add_settings_page(){   
    	if(!is_admin() || !current_user_can('manage_options'))
			return; 	
    	add_submenu_page( 
	        'options-general.php',
	        'Login/Logout settings',
	        'Login/Logout settings',
	        'manage_options',
	        'login-logout-settings',
	        array($this,'WLOG_login_logout_settings')
	    );
    } 
    public function WLOG_update_options(){

    	if(!is_admin() || !current_user_can('manage_options'))
			return;
		if(!wp_verify_nonce( $_POST['_update_sett_nonce'], '_set_options' ))
			return;
		if(isset($_POST['update_settings'])){
			if(isset($_POST['login_subscriber']) ){
				$update = sanitize_text_field($_POST['login_subscriber']);
				if($update)
					$update = urlencode($update);
				update_option('WLOG_subscriberLogin',$update);
			}
			elseif(isset($_POST['select_login_subscriber'])){
				$update = sanitize_text_field($_POST['select_login_subscriber']);
				if($update)
					$update = urlencode($update);
				update_option('WLOG_subscriberLogin',$update);
			}
			if(isset($_POST['logout_subscriber']) ){
				$update = sanitize_text_field($_POST['logout_subscriber']);
				if($update)
					$update = urlencode($update);
				update_option('WLOG_subscriberLogout',$update);
			}
			elseif(isset($_POST['select_logout_subscriber'])){
				$update = sanitize_text_field($_POST['select_logout_subscriber']);
				if($update)
					$update = urlencode($update);
				update_option('WLOG_subscriberLogout',$update);
			}
			if(isset($_POST['login_admin'])){
				$update = sanitize_text_field($_POST['login_admin']);
				if($update)
					$update = urlencode($update);
				update_option('WLOG_adminLogin',$update);
			}
			elseif(isset($_POST['select_login_admin'])){
				$update = sanitize_text_field($_POST['select_login_admin']);
				if($update)
					$update = urlencode($update);
				update_option('WLOG_adminLogin',$update);
			}
			if(isset($_POST['logout_admin'])){
				$update = sanitize_text_field($_POST['logout_admin']);
				if($update)
					$update = urlencode($update);
				update_option('WLOG_adminLogout',$update);	
			}
			elseif(isset($_POST['select_logout_admin'])){
				$update = sanitize_text_field($_POST['select_logout_admin']);
				if($update)
					$update = urlencode($update);
				update_option('WLOG_adminLogout',$update);
			}
		}
    }
    public function WLOG_login_logout_settings(){
    	if(!is_admin() || !current_user_can('manage_options'))
			return;
		$subscriberLogin = '';
		$select_subscriberLogin = '';
		$subscriberLogout = '';
		$select_subscriberLogout = '';
		$adminLogin = '';
		$select_adminLogin = '';
		$adminLogout = '';
		$select_adminLogout = '';

		if(get_option('WLOG_subscriberLogin'))
			$subscriberLogin = esc_url_raw(urldecode(get_option('WLOG_subscriberLogin')));
		if(get_option('WLOG_subscriberLogout'))
			$subscriberLogout = esc_url_raw(urldecode(get_option('WLOG_subscriberLogout')));
		if(get_option('WLOG_adminLogin'))
			$adminLogin = esc_url_raw(urldecode(get_option('WLOG_adminLogin')));
		if(get_option('WLOG_adminLogout'))
			$adminLogout = esc_url_raw(urldecode(get_option('WLOG_adminLogout')));

		// get page id
		$args = array(
					'post_type'   => 'page',
					'post_status' => 'publish'
			);

		$pages = get_posts($args);
		
		$page_list = "<option value=''>Select Page</option>";

		$select_subLogin = 0;
		$select_subLogout = 0;
		$select_admLogin = 0;
		$select_admLogout = 0;

		foreach ($pages as $page) {
			$url = get_page_link($page->ID);
			$optionSelected = "<option value='".$url."' selected>".$page->post_title."</option>";

			$option = "<option value='".$url."'>".$page->post_title."</option>";
			if($select_subLogin)
				$select_subscriberLogin .= $option;
			if($select_subLogout)
				$select_subscriberLogout .= $option;
			if($select_admLogin)
				$select_adminLogin .= $option;
			if($select_admLogout)
				$select_adminLogout .= $option;

			if(strcmp($url, $subscriberLogin) === 0){
				$subscriberLogin = '';
				$select_subscriberLogin = $page_list;
				$select_subscriberLogin .= $optionSelected;
				$select_subLogin = 1;
			}

			if(strcmp($url, $subscriberLogout) === 0){
				$subscriberLogout = '';
				$select_subscriberLogout = $page_list;
				$select_subscriberLogout .= $optionSelected;
				$select_subLogout = 1;
			}
			if(strcmp($url, $adminLogin) === 0){
				$adminLogin = '';
				$select_adminLogin = $page_list;
				$select_adminLogin .= $optionSelected;
				$select_admLogin = 1;
			}

			if(strcmp($url, $adminLogout) === 0){
				$adminLogout = '';
				$select_adminLogout = $page_list;
				$select_adminLogout .= $optionSelected;
				$select_admLogout = 1;
			}

			
			$page_list .= $option;
		}

		$select =  "</select>";
		if($select_subLogin)
			$select_subscriberLogin .= $select;
		if($select_subLogout)
			$select_subscriberLogout .= $select;
		if($select_admLogin)
			$select_adminLogin .= $select;
		if($select_admLogout)
			$select_adminLogout .= $select;

		$page_list .= $select;
		
		?>
		<div class="settings_div">
			<h1>Redirection settings after login/ logout</h1>
			<form action="" method="POST">
				<?php wp_nonce_field( '_set_options', '_update_sett_nonce',true,true ); ?>
				<div class="fn_redirect_setting">
					<h2>Subscribers</h2>
					<table class="settings_table">
						<thead>
							<tr>
								<th class="wlog_label"><h3>Action</h3></th>
								<th class="wlog_input"><h3>Redirect to page/ custom link</h3></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Login</td>
								<td>
									<select name="select_login_subscriber" id="select_login_subscriber" class='wlog_select_page'>
									<?php 	
										if($select_subscriberLogin) 
											echo $select_subscriberLogin;
										else
											echo $page_list; 
									?>
									</select>
									<input class="redirect_link" name="login_subscriber" id="login_subscriber" placeholder="Enter redirection url after login" value="<?php echo $subscriberLogin; ?>"/>
									<span class="default_url">Default: <?php echo site_url(); ?> </span>
								</td>
							</tr>
							<tr>
								<td>Logout</td>
								<td>
									<select name="select_logout_subscriber" id="select_logout_subscriber" class='wlog_select_page'>
									<?php 	
										if($select_subscriberLogout) 
											echo $select_subscriberLogout;
										else
											echo $page_list; 
									?>
									</select>
									<input class="redirect_link" name="logout_subscriber" id="logout_subscriber" placeholder="Enter redirection url after logout" value="<?php echo $subscriberLogout; ?>"/>
									<span class="default_url">Default: <?php echo site_url(); ?> </span>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="fn_redirect_setting">
					<h2>Admin</h2>
					<table class="settings_table">
						<thead>
							<tr>
								<th class="wlog_label"><h3>Action</h3></th>
								<th class="wlog_input"><h3>Redirect to page/ custom link</h3></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Login</td>
								<td>
									<select name="select_login_admin" id="select_login_admin" class='wlog_select_page'>
									<?php 	
										if($select_adminLogin) 
											echo $select_adminLogin;
										else
											echo $page_list; 
									?>
									</select>
									<input class="redirect_link" name="login_admin" id="login_admin" placeholder="Enter redirection url after login" value="<?php echo $adminLogin; ?>"/>
									<span class="default_url">Default: <?php echo get_dashboard_url(); ?> </span>
								</td>
							</tr>
							<tr>
								<td>Logout</td>
								<td>
									<select name="select_logout_admin" id="select_logout_admin" class='wlog_select_page'>
									<?php 	
										if($select_adminLogout) 
											echo $select_adminLogout;
										else
											echo $page_list; 
									?>
									</select>
									<input class="redirect_link" name="logout_admin" id="logout_admin" placeholder="Enter redirection url after logout" value="<?php echo $adminLogout; ?>"/>
									<span class="default_url">Default: <?php echo wp_login_url(); ?> </span> 
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<input type="submit" name="update_settings" id="update_settings" value="Update" class="button button-primary" />
			</form>
		</div>
		<?php
    }

    function WLOG_add_scripts(){
    	wp_enqueue_style('wlog-admin-style', WLOG_direct_url. 'assets/css/login-settings.css');
    	wp_enqueue_script('wlog-admin-script', WLOG_direct_url. 'assets/js/wlog-admin-js.js');   
    }
    function WLOG_add_scriptsFrontend(){
    	wp_enqueue_script('jquery');
    	wp_register_script('wlog-frontend-script', WLOG_direct_url. 'assets/js/frontend-script.js');
    	$ajax_nonce = wp_create_nonce( "_logi_frontend_user" );	
    	wp_localize_script( 'wlog-frontend-script', 'security', $ajax_nonce );
    	wp_localize_script( 'wlog-frontend-script', 'ajax_url', admin_url( 'admin-ajax.php' ) );
    	wp_enqueue_script('wlog-frontend-script');
    	wp_enqueue_style('wlog-frontend-style', WLOG_direct_url. 'assets/css/frontend.css'); 
    }
    function WLOG_add_loginout_link( $items, $args ) {
    	$item_counts = count($items);
    	if(strpos($this->items_classes, "menu-item") !== false){
    		$item_count = intval(preg_replace('/[^0-9]+/', '', $this->items_classes), 10);
    		$item_prev = "menu-item-".$item_count;
    		$item_count++;
    		$this->items_classes = str_replace($item_prev, "menu-item-".$item_count, $this->items_classes);
    	}    		
    	if (is_user_logged_in() && $args->theme_location == 'primary') {
    		$user = wp_get_current_user();
	 		$role = $user->roles[0];
	 		$logout_redirect = '';
    		if($role == "administrator")
				$logout_redirect = urldecode(get_option('WLOG_adminLogout'));
			else if($role != "administrator")
				$logout_redirect = urldecode(get_option('WLOG_subscriberLogout'));
	        $items .= '<li class="'.$this->items_classes.'"><a href="'. wp_logout_url($logout_redirect) .'">Log Out</a></li>';
	    }
	    elseif (!is_user_logged_in() && $args->theme_location == 'primary') {
	        $items .= '<li class="'.$this->items_classes.'" id="wlog_login"><a onclick="WLOG_loggingin_user()">Log In</a></li>';
	    }
	    return $items;
	}

	function WLOG_get_nav_class( $classes, $item ) {
	    $this->items_classes = implode(" ", $classes);
	    return $classes;
	}	
	function WLOG_fn_login_user(){
		if(!check_ajax_referer( '_logi_frontend_user', 'security' ))
			die("invalid");
		if(!isset($_POST))
			die("invalid");
		if(!isset($_POST['username']) && !isset($_POST['password']))
			die("empty");

		if(is_user_logged_in())
			die("already_loggedin");
		$username = $_POST['username'];
		$password = $_POST['password'];
		$user = wp_authenticate( $username, $password );
		if(is_wp_error($user))
			die("invalid_username");
		else{ 	 	
			$role = $user->roles[0];
			$login_redirect = 'success';
			if($role == "administrator")
				$login_redirect = urldecode(get_option('WLOG_adminLogin'));
			else if($role != "administrator")
				$login_redirect = urldecode(get_option('WLOG_subscriberLogin'));
			$user_id = $user->ID;
	        wp_set_current_user( $user_id, $user_login );
	        wp_set_auth_cookie( $user_id );
	        do_action( 'wp_login', $user_login );
	        die($login_redirect);
		}
	}
}

