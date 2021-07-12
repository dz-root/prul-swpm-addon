<form action="" method="post" id="reset_password_form">
  
  <input type="text" name="email" id="email" placeholder="Email address">
  <input type="submit" name="password-reset" value="<?php $rbtn = get_option('prul_alerts'); echo $rbtn['p_a_button_reset']; ?>">
  
</form>