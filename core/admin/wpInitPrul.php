<?php

	/***
	** Add Prul to Wordpress menu as Child of Settings section
	*/

	function admin_menu_t()
	{
		
		add_submenu_page('options-general.php', 'Purl settings', 'Purl settings', 'manage_options', 'prul_admin_settings', 'prul_settings' );
		
	}
		
	add_action( "admin_menu", "admin_menu_t" );
	
	
	
	/***
	 *  Load Prul Admin welcome page if not setup.
	 */
	
	function prul_settings()
	{
		$check_config;

		global $wpdb ; 
		
		require_once('settings.php');

		$check_config = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."options WHERE option_name = 'prul_auto_config' ", OBJECT );

		( $check_config[0]->option_value == false ) ? require_once( PRUL_PATH.'/views/admin_welcome.php' ) : require_once( PRUL_PATH.'/views/admin_settings.php' );

	}



	/***
	 * Get pages list to select needed pages
	 */

	function pages_ids()
	{

		$args = array(
			'sort_order' => 'asc',
			'sort_column' => 'post_title',
			'hierarchical' => 1,
			'child_of' => 0,
			'parent' => -1,
			'offset' => 0,
			'post_type' => 'page',
			'post_status' => 'publish'
		); 

		$pages = get_pages($args);
		$l=get_option('prul_alerts');

		foreach( $pages as $page)
		{
			if ($page->ID == $l['p_a_update_password_link'] ) {
				echo "<option value='". $page->ID ."' selected>" . $page->post_title . "</option>";
			} else {
				echo "<option value='". $page->ID ."'>" . $page->post_title . "</option>";
			}
		}
		
	}


	/***
	 * Check manual configuration
	 */

	function check_pages(){	
		if ( isset($_POST['pwd_update_page'] ) && isset($_POST['pwd_reset_page'] ) ){
		$reset_page_id = get_post( $_POST['pwd_reset_page'] ) ;
		$reset_page_slug = ( $reset_page_id->post_name == 'reset-password' ) ? "<h3> Reset password page</h3><li class='success'><span class='dashicons dashicons-saved'></span><b>Good job ! </b> the reset password slug page is created as recommanded !<li> " : "<h3> Reset password page</h3><li class='error'><span class='dashicons dashicons-no-alt'></span> <b>The page slug's seems not same as the recommendation</b><code>/reset-password/</code><br><b>Are you sure that is the right page ?</b><u> " .get_permalink( $_POST['pwd_reset_page']) . "</u></li>";
		$update_page_id = get_post( $_POST['pwd_update_page']);
		$update_page_slug = ( $update_page_id->post_name == 'update-password' ) ? "<h3> Update password page</h3><li class='success'><span class='dashicons dashicons-saved'></span><b>Good job !</b> the update password slug page is created as recommanded ! </li>" : "<h3> Update password page</h3><li class='error'><span class='dashicons dashicons-no-alt'></span> <b>The page slug's seems not same as the recommendation</b> <code>/update-password/</code><br>Are you sure that is the right page <u> ? " .get_permalink( $_POST['pwd_update_page']) . "</u></li>";

		$check_score = 0;
		( $reset_page_id->post_name  == 'reset-password' ) ? $check_score += 2 :"";
		( $update_page_id->post_name == 'update-password') ? $check_score += 2 :"";

		$all_options = wp_load_alloptions();
		$my_options  = array();
		
		foreach ( $all_options as $name => $value ) {
			if ( stristr( $name, 'swpm-settings' ) ) {
				$my_options[ $name ] = $value;
			}
		}


		if( stristr ( $my_options['swpm-settings'], site_url().'/reset-password' )  && $reset_page_id->post_name == 'reset-password' ){

			$checkWpsmUrl = '<li class="success"><span class="dashicons dashicons-saved"></span> Simple membership reset password url is linked to your page: '.site_url().'/reset-password/</li>' ;
			$check_score += 1 ;

		} elseif ( stristr( $my_options['swpm-settings'], site_url().'/membership-login/reset-password/' ) && $reset_page_id->post_name == 'reset-password' ) {
			
			$checkWpsmUrl = '<li class="error"><span class="dashicons dashicons-no-alt"></span> Actually Simple membership reset password url is the next : <br>'. 
			site_url().'/<b>membership-login</b>/reset-password/ <br>Be sure that you defined your reset password page as child of <b>membership login</b> page.</li>' ;

		} else {
			
			$checkWpsmUrl = '<li class="error"><span class="dashicons dashicons-no-alt"></span> Replace simple membership reset password url by the url of the reset password page that you supposed to create.</li>' ;

		}
		
		
		$response = array(
			'statut_reset_page' => $reset_page_slug,
			'statut_update_page' => $update_page_slug,
			'wpsm' => $checkWpsmUrl,
			'score' => $check_score
		);

		echo json_encode($response);
		}
		wp_die();
	}

	add_action( 'wp_ajax_check_pages', 'check_pages' );
	add_action( 'wp_ajax_nopriv_check_pages', 'check_pages' );


	// Manual configuration - continue to settingd

	function continuTosettings(){
		if( isset( $_POST['go_settings'] ) ){

		global $wpdb ;
		global $post ;

		update_option( "prul_auto_config", true ,  'yes' );
		
		echo "<script>document.location.href='".$_SERVER['HTTP_REFERER']."';</script>";

		return false;

		}

	}



	/***
	 *  Auto config agreement
	*/

	function  auto_config(){

		if( isset( $_POST['auto_conf'] ) ){

			global $wpdb ;
			global $post ;
			$approved = $_POST['agreement'];

			if( $approved == 'on' ){

				update_option( "prul_auto_config", true ,  'yes' );

				if ( post_exists( 'Reset Password' ) == false ){
					$creat_Reset_Pwd_Page = array(
						'post_title'    => 'Reset Password',
						'post_content'  => '[lost-password-form]',
						'post_status'   => 'publish',
						'post_author'   => 1,
						'post_category' => array(1), 
						'post_type'     => 'page'
					);
					
					wp_insert_post( $creat_Reset_Pwd_Page );
				}
				
				if ( post_exists( 'Update password' ) == false ){
					$creat_Update_Pwd_Page = array(
						'post_title'    => 'Update password',
						'post_content'  => '[reset-password-form]',
						'post_status'   => 'publish',
						'post_author'   => 1,
						'post_category' => array(1), 
						'post_type'     => 'page'
					);
				}
				wp_insert_post( $creat_Update_Pwd_Page );

				echo "<script>document.location.href='".$_SERVER['HTTP_REFERER']."';</script>";

				return false;

			}	
		}
	}	

	/***
	 *  Reset config agreement
	*/

	function reset_config(){

		if( isset( $_POST['reset_prul_conf'] ) ){

			global $wpdb ;
			global $post ;
			$approved = $_POST['rc-agreement'];
			
			if( $approved == 'prul-reset' ){
			
				update_option( "prul_auto_config", false ,  'yes' );
			
			}
			
			echo "<script>document.location.href='".$_SERVER['HTTP_REFERER']."';</script>";
			return false;
		}
	}

	/***
	 *  Update settings
	*/

	function updateSettings(){

		if(isset($_POST['updateSettings'])){
	
			$data = array(

				/* Alert */
				'p_a_email_success' => $_POST['p_a_email_success'],
				'p_a_invalidToken' => $_POST['p_a_invalidToken'],
				'p_a_passNotsame' => $_POST['p_a_passNotsame'],
				'p_a_passNotStrong' => $_POST['p_a_passNotStrong'],
				'p_a_passUpdate' => $_POST['p_a_passUpdate'],

				/* email */
				'p_a_email_subject' => $_POST['p_a_email_subject'],
				'p_a_update_password_link' => $_POST['p_a_update_password_link'],
				'p_a_email_content' => $_POST['p_a_email_content'],

				/* text_buttun */
				'p_a_button_reset' => $_POST['p_a_button_reset'],
				'p_a_button_update' => $_POST['p_a_button_update'],

				/* Strong password */
				'enable_strong_pass' =>  $_POST['enable_strong_pass']
				
			);
			
			if ( isset( $data ) && !empty($data) ){

				global $wpdb ;
				update_option( 'prul_alerts', $data ,'',  'yes'  );
			
			}
			
			echo "<script>document.location.href='".$_SERVER['HTTP_REFERER']."&prul_statut=updated';</script>";
			
			}
	
	}