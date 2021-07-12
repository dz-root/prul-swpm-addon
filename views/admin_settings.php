<div class="wrap">  

    <div class="prul-square-col prul-settings-menu">
    </div>

    <div class="prul-half-col">
        <h1>Prul Settings</h1>
        <p>Prul (Password Reset Unique Link) is adddon for the awesome Simple WP Membership</p>
        <div class="prul-menu-item">
        <a href="#" class="conf-item message-alert"  onclick="openTab('message-alert')">
            <span class="dashicons dashicons-admin-comments"></span> Custom Alerts
            <div class="triangle-selector message-alert"></div>
        </a>
        <a href="#" class="conf-item"  onclick="openTab('email-set')"><span class="dashicons dashicons-email"></span> Custom email<div class="triangle-selector email-set"></div></a>
        <a href="#" class="conf-item"  onclick="openTab('strong-pass')"><span class="dashicons dashicons-privacy"></span> Other settings<div class="triangle-selector strong-pass"></div></a>
        <a href="#" class="conf-item"  onclick="openTab('reset-conf')" style="float:right; border-radius: 5px 5px 0px 0px"><span class="dashicons dashicons-admin-tools"></span><div class="triangle-selector strong-pass"></div></a>
        </div>

        <?php echo ( $_GET['prul_statut'] == 'updated' ) ? '<div class="notice notice-success is-dismissible"><h3>Successfully updated!</h3></div>' : ''; ?>

        
        <form action="" method="post" >

            <div id="message-alert" class="postbox prul_section" style="padding:40px">

                <p class="bigLabel">Message alert when reset password email sent</p> <?= $settings->prul_editor('p_a_email_success'); ?>

                <p class="bigLabel"> Message alert when token is invalid or expired</p> <?= $settings->prul_editor('p_a_invalidToken'); ?>

                <p class="bigLabel"> Message alert when not same passwords</p> <?= $settings->prul_editor('p_a_passNotsame'); ?>

                <p class="bigLabel"> Message alert when passwords is not strong</p> <?= $settings->prul_editor('p_a_passNotStrong'); ?>

                <p class="bigLabel"> Message alert when password is successful updated</p> <?= $settings->prul_editor('p_a_passUpdate'); ?>
            </div>


            <div id="email-set" class="postbox prul_section" style="display:none; padding:40px" >
                <h2>CUSTOM THE RESTE PASSWORD EMAIL</h2>
                <hr>
                <table class="form-table" role="presentation">
                    <tbody>
                        <tr>
                            <th><label for="enable_strong_pass"><b>Email subject </b></label></th> 
                            <td><input type="text" name="p_a_email_subject" value="<?= AlertMessages::get_prul_alert('p_a_email_subject'); ?>" style="width:100%"></td>
                        </tr>

                        <tr>
                            <th><label for="enable_strong_pass"><b>Update password link* </b></label></th> 
                            <td><select name="p_a_update_password_link" ><option  <?php $l=get_option('prul_alerts'); if( empty ( $l["p_a_update_password_link"] ) ) {echo "selected"; } ?> disabled>Select page</option><?php pages_ids(); ?></select></td>
                        </tr>
                    </tbody>
                </table>

                <p class="bigLabel"> Email content <br><small> NB: the *update password link will be automatically included into the bottom of the email</p> <?= $settings->prul_editor('p_a_email_content'); ?>

            </div>

            <div id="strong-pass" class="postbox prul_section" style="padding:40px; display:none">
                <table class="form-table" role="presentation" >
                    <tbody>
                    
                        <tr>
                            <th><label for="enable_strong_pass"><b>Reset password button text</b></label></th> 
                            <td><input type="text" name="p_a_button_reset" value="<?= AlertMessages::get_prul_alert('p_a_button_reset'); ?>" style="width:100%"></td>
                        </tr>

                        <tr>
                            <th><label for="enable_strong_pass"><b>Reset password button text </b></label></th> 
                            <td><input type="text" name="p_a_button_update" value="<?= AlertMessages::get_prul_alert('p_a_button_update'); ?>" style="width:100%"></select></td>
                        </tr>
                        <tr>
                            <th><label for="enable_strong_pass"><b>Enable strong password verification</label></th>
                            <td><input type="checkbox" value="1" id="enable_strong_pass" name="enable_strong_pass" <?php $s=get_option('prul_alerts'); if($s["enable_strong_pass"] == '1') {echo "checked";} ?>> </td>
                        </tr>          
                    </tbody>
                </table> 
            </div>

            <div id="reset-conf" class="postbox prul_section" style="padding:40px; display:none">
                <h3>Reset Configuration</h3>
                <form action="" method="post">
                    <p><input type="checkbox" name="rc-agreement" id="rc-agreement" value="prul-reset"> <label for="rc-agreement">I Understand, and I want to reset the configuration: </label></p>
                    <input type="submit" value="Reset" class="button-primary" name="reset_prul_conf">
                </form>
            </div>

            <input type="submit" class="button-primary" value="Update settings !" name="updateSettings">
                

        </form>

    </div>

    <div class="prul-square-col"></div>
    
</div>


<script>

function openTab(section) {
    let i, x = document.getElementsByClassName("prul_section"), y = document.getElementsByClassName("triangle-selector");
    for (i = 0; i < x.length; i++) { 
        x[i].style.display = "none";
    }
    document.getElementById(section).style.display = "block";
   
}

</script>
