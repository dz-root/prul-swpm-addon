<?php

class Settings{

    public function prul_editor( $y ){
        
        $editor_id = $y;
        $content   = AlertMessages::get_prul_alert( $editor_id );
       
        $args = array(
            'textarea_name' => $editor_id,
            'wpautop' => false,
            'textarea_rows' => 5,
            'media_buttons' => FALSE,
            'teeny'         => TRUE,
            'tinymce'       => TRUE
        );
        
        wp_editor( $content, $editor_id, $args );
    }

}

$settings = new Settings();