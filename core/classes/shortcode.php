<?php

class Shortcode {

	public function __construct() {

		include_once( PRUL_PATH . 'core/classes/alertMessages.php' );

		add_shortcode( 'lost-password-form',  array( $this,'lost_password' ) );
		add_shortcode( 'reset-password-form', array( $this,'reste_password') );

	}

	public function lost_password(){

		
		include_once(PRUL_PATH . 'views/lost-password-form.php' );
		
		wp_register_style('swpm-jquery-ui', SIMPLE_WP_MEMBERSHIP_URL . '/css/jquery-ui.min.css');

		if( isset( $_POST['email'] ) && is_email( $_POST['email'] ) ){
	
			global $wpdb;
			 
			$user_email = $_POST['email'];
		
			$results = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."swpm_members_tbl WHERE email = '".$user_email."'", OBJECT );
		
			if( !empty( $results ) ){
				
				// Generate and encrypte token
				
				$bytes = random_bytes(15);
				$token = bin2hex($bytes);
		
				// Token expire date verification

				$gen_date = new DateTime("now");
				$exp_date = $gen_date->modify('+1 day');
				$frm_date = $exp_date->format( 'Y-m-d h:i:s' );
				$table_name = $wpdb->prefix . "token_log";
		
				$update = $wpdb->insert( 
					$table_name, 
					array( 
						'email'      => $user_email,
						'token'      => $token,
						'exp_date'   => $frm_date, 
					)
				);
		
				/**
				 * PHP mail()
				 * Send email with activation link
				 * */ 
				$headers[] = 'MIME-Version: 1.0';
				$headers[] = 'Content-type: text/html; charset=iso-8859-1';
				$headers[] = 'From: no-reply@'.substr(get_option( 'home' ), 8);
				$subcnt = get_option('prul_alerts');
				$subject = $subcnt['p_a_email_subject'];
				$resetLink = get_post($subcnt['p_a_update_password_link']);	
				$message = $subcnt['p_a_email_content'] . "<br><a href='". get_option( 'home' ) .'/'. $resetLink->post_name ."/?email=". $user_email . "&token=".$token."'> Reset my password </a>";

				mail( $user_email, $subject, $message, implode("\r\n", $headers));

				echo "<div style='text-align:center'>" . AlertMessages::get_prul_alert( 'p_a_email_success' ) . "</div>";
				echo "<script> jQuery( function($){ $('#reset_password_form').hide(); } );</script>";
			}
		}

	}

	public function reste_password(){

		if( !empty( $_GET['email'] ) && !empty( $_GET['token'] ) ){

			include_once(PRUL_PATH . 'views/reset-password-form.php' );

			$url_email =  $_GET['email'];
			$is_email =  is_email($_GET['email']) ? 'ok' : die();
			$url_token =  htmlspecialchars($_GET['token']);

		
			if ( isset ( $_POST["update-password"] ) ){
				
				$newpwd  = $_POST['pwd'];
				$rnewpwd = $_POST['rpwd'];
				$enabled_strong_pass = get_option('prul_alerts');
				 
				// Validate password strength
				$uppercase    = ($enabled_strong_pass['enable_strong_pass'] == "1") ? preg_match('@[A-Z]@', $newpwd) : preg_match('@[a-zA-Z0-9]@', $newpwd) ;
			    $lowercase 	  = ($enabled_strong_pass['enable_strong_pass'] == "1") ? preg_match('@[a-z]@', $newpwd) : preg_match('@[a-zA-Z0-9]@', $newpwd);
				$number   	  = ($enabled_strong_pass['enable_strong_pass'] == "1") ? preg_match('@[0-9]@', $newpwd) : preg_match('@[a-zA-Z0-9]@', $newpwd); 
				$specialChars = ($enabled_strong_pass['enable_strong_pass'] == "1") ? preg_match('@[^\w]@', $newpwd) : preg_match('@[a-zA-Z0-9]@', $newpwd);
				$lenghtChars 	= ($enabled_strong_pass['enable_strong_pass'] == "1") ? 7 : 2;
				 
				if( $uppercase && $lowercase && $specialChars && $number && strlen($newpwd) > $lenghtChars ) {

					if( $newpwd == $rnewpwd ){
			
						global $wpdb;		
			
						// token verification

						$token_table = $wpdb->prefix."token_log"; 
			
						$results = $wpdb->get_results( "SELECT * FROM $token_table WHERE email = '".$url_email."'", OBJECT );
					
						$last_token_checker = count($results)-1;
			
						$last_token = $results[ $last_token_checker ] -> token;
			
						$exp_date = new DateTime( $results[ $last_token_checker ] -> exp_date );
			
						$tdy_date = new DateTime("now");
			
						if( $last_token == $url_token && $exp_date > $tdy_date){
			
							// hash and update password
			
							$hashed_pwd = wp_hash_password( $newpwd );
			
							$update = $wpdb->update( $wpdb->prefix."swpm_members_tbl", array('password' => $hashed_pwd), array("email" => $url_email) );
			
							if( $update == 1 ){
			
								echo "<div style='text-align:center'>" . AlertMessages::get_prul_alert( 'p_a_passUpdate' ) . "</div>";
								echo "<script> jQuery( function($){ $('#update_password_form').hide(); } );</script>";
			
							} else {
			
								echo "<div style='text-align:center'> Error (during update). </div>";
					
							}
			
							// delete generated token
			
							$delete_token =  $wpdb->delete( $wpdb->prefix."token_log", array( 'email' => $url_email ) );
			
						} else { echo "<div style='text-align:center'>" . AlertMessages::get_prul_alert( 'p_a_invalidToken' ) . "</div>"; }

					} else { echo "<div style='text-align:center'>" . AlertMessages::get_prul_alert( 'p_a_passNotsame' ) ."</div>"; }
			
				} else { echo "<div style='text-align:center'>" . ( AlertMessages::get_prul_alert( 'p_a_passNotStrong' ) ) . "</div>";}	
			
			}
		
		}else{ echo "<div style='text-align:center'> <a href='".get_option( 'home' )."'> Back to home </a> </div>";}

	}

}