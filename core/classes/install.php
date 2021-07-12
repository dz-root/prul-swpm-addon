<?php

class Install {    


    static function creat_table(){

        global $wpdb ;

        $table_name = $wpdb->prefix . "token_log";  

        $charset_collate = $wpdb->get_charset_collate();

        $creatToken_tab = "CREATE TABLE IF NOT EXISTS $table_name
        (

            id int(11)  UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            email varchar(60) NOT NULL,
            token varchar(30) NOT NULL,
            exp_date datetime NOT NULL

        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        dbDelta($creatToken_tab);

        
    }

    static function config_init(){
        
        add_option( "prul_auto_config", false , '',  'yes' );

        $prul_alerts = array(

            'p_a_email_success' => 'Check your email, we sent you a link to reset your password.',
            'p_a_invalidToken' => '<u>Error:</u> Invalide token to update your password. (Wrong or expired)',
            'p_a_passNotsame' => '<u>Error:</u> Not same passwords',
            'p_a_passNotStrong' => 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.',
            'p_a_passUpdate' => 'Your password was update successfuly! ',

            /* email */
            'p_a_email_subject' => 'Reset your password',
            'p_a_update_password_link' => '',
            'p_a_email_content' => 'Click here to update your password',
           
            /* text_buttun */            
            'p_a_button_reset' => 'Reset my password',
            'p_a_button_update' => 'Update password',
            'enable_strong_pass' => 0,

        );        
        
        add_option( "prul_alerts", $prul_alerts , '',  'yes' );


    }



    static function prul_uninstall(){

        if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit();

        global $wpdb;

        $wpdb->query( "DROP TABLE IF EXISTS $wpdb->prefix.log_token" );

        $option_list = array( 'prul_alerts','prul_auto_config');

        delete_option( $option_list );

    }
    

}

Install::creat_table();
Install::config_init();
