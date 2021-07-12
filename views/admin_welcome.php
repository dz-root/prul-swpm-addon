<div class="wrap">  

    <div class="prul-square-col"></div>

    <div class="prul-half-col">
        
        <h1>Prul Configuration</h1>
        <a href="#" class="conf-item"  onclick="openTab('manual_conf_sec')">
            <span class="dashicons dashicons-admin-comments"></span> Manual Configuration
        </a>
        <a href="#" class="conf-item"  onclick="openTab('auto_conf_sec')">
            <span class="dashicons dashicons-email"></span> Automatic Configuration     
        </a>

        <div id="manual_conf_sec" class="postbox prul_section" style="padding:40px">
            <h2>Method 1 : Manually configuration</h2>

            <div class="prul-step-config">
                <p>
                    <span class="title">Create a new reset password page</span> 
                    By default, Simple Membreship creates  <code>/password-reset/</code> page.<br>
                    <b>We recommend you create <code>/reset-password/</code></b> slug but you're free to pick any slug you like.<br>
                    <br>
                    Once the page is created, copy paste the following shortcode into it and come back to PRUL settings to continue the installation.
                </p>
                <div class="lpf-shortcode">
                    <input type="text" value="[lost-password-form]" id="shortcode_1">
                    <span onclick="prul_copy_shortcode('shortcode_1')">Copy</span>
                </div>
                <br>
                <i>Optional: for a cleaner maintenance of your website, you can delete the original "reset password" page created by Simple Membership plugin.</i>
            </div>

            <div class="prul-step-config">
                <p>
                    <span class="title right">Go to Simple Membership settings</span> 
                    Change the slug for "Password reset page URL" section and insert the slug for the page you created in step 1.
                    <br>
                    <img src="<?= site_url(INTER_PATH.'/dist/img/membership_settings_screenshot.jpg'); ?>" style="width:100%; margin-top:20px">
                    <a href="<?php echo get_site_url(); ?>/wp-admin/admin.php?page=simple_wp_membership_settings" target="_blank">Go to Simple Membership settings</a>
                </p>
            </div>
            
            <div class="prul-step-config">
                <p>
                    <span class="title">Create update password page</span> 
                    We recommend you create  <code>/update-password/.</code><br>
                    Once the page (<code><?= site_url()?>/update-password/</code>) is created, copy/paste the shortcode below into it:
                </p>

                <div class="lpf-shortcode">
                    <input type="text" value="[reset-password-form']" id="shortcode_2">
                    <span onclick="prul_copy_shortcode('shortcode_2')">Copy</span>
                </div>
            </div>

            <div class="prul-step-config">
                <p>
                <span class="title right">Check if your configuration is correct</span>
                <form action="" method="post" id="prul_checker">
                    <div class="checker_result">
                        <ul>

                        </ul>
                    </div>
                    <table class="check-config-form form-table" role="presentation">
                        <tbody>
                            <tr>
                                <th><label for="enable_strong_pass"><b>What is the "Reset Password' page you created:</b></label></th> 
                                <td><select name="selected_pwd_reset_page" ><option selected disabled>Select page</option><?php pages_ids(); ?></select></td>
                                <td class="result"> </td>
                            </tr>

                            <tr>
                                <th><label for="enable_strong_pass"><b>What is the "Update Password' page you created:</b></label></th> 
                                <td><select name="selected_pwd_update_page"><option selected disabled>Select page</option><?php pages_ids(); ?></select></td>
                            </tr>

                            <tr>
                                <th> <input type="submit" class="prul-check-btn button-primary" name="check_pages" value="Check it!"></th>
                            </tr>
                        </tbody>
                    </table>
                </form>

                <div class="success-check">
                    <form action="" method="post" ><input type="submit" class="go_settings button-primary" name="go_settings" value="Continue to settings"></form>
                </div>

                </p>

            </div>

        </div>

        <div id="auto_conf_sec" class="postbox prul_section" style="padding:40px">
            <h2>Methode 2 : Automatic configuration</h2>
            It will : 
            <ul>
                <li>Creates reset password page.</li>
                <li>Creates update password page.</li>
                <li>Updates Simple Membership settings (replace reset password link).</li>
            </ul>

            <form action="" method="post">

                <input type="checkbox" name="agreement" id="agreement" value="on"> <label for="agreement">I Understand, and I allow: </label>
                <input type="submit" value="Auto-configuration !" class="button-primary" name="auto_conf">

            </form>
        </div>
    </div>

    <div class="prul-square-col"></div>
    
</div>
<script>

function openTab(section) {

    let i, 
        x = document.getElementsByClassName("prul_section");

    for (i = 0; i < x.length; i++) { x[i].style.display = "none";}
    
    document.getElementById(section).style.display = "block";
}
</script>