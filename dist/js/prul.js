jQuery(function($) {

    //$('#choose_manual').click(function(){$('#manual_conf_sec').show();$('#auto_conf_sec').hide();});
    //$('#choose_auto').click(function(){$(this).addClass('.selected_conf');$('#auto_conf_sec').show();$('#manual_conf_sec').hide()});
    
    let frm = $('#prul_checker');
    frm.submit(function() {

        let pwd_reset_page= $(this).find("select[name=selected_pwd_reset_page]").val();
        let pwd_update_page= $(this).find("select[name=selected_pwd_update_page]").val();
        

        $.ajax({
            url: ajaxurl,
            type: "POST",
            dataType: 'json',
            data: {
              'action': 'check_pages',
              'pwd_reset_page': pwd_reset_page,
              'pwd_update_page': pwd_update_page,
            }
        }).done(function(response) {
            
            $(".checker_result").show();
            $(".checker_result > ul").html( response.statut_reset_page + '' + response.wpsm + '' + response.statut_update_page );
            console.log(response.score);

            if( response.score == 4 ){
                $('.check-config-form').hide();
                $(".success-check").show();
                $("<p>You can continue to the settings page <b> but don't forget to replace later the reset password page in Membership settings</b></p>").insertBefore( ".go_settings" );

            } else if( response.score > 4 ) {

                $('.check-config-form').hide();
                $(".success-check").show();
                $("<p style='color:green'><b>Well done!</b> <span class='dashicons dashicons-thumbs-up'></span> </p>").insertBefore( ".go_settings" );


            } else {
                $('input[type=submit].prul-check-btn').val( "Fix errors and check again !" );
            }
            
        });

        return false;
    });




    
});

function prul_copy_shortcode(x) {

    let copyCode = document.getElementById(x);
    console.log(copyCode.value);
    copyCode.select();
    return false;

}

