<form action="" method="post" id="update_password_form">
  
  <input type="password" name="pwd" id="pwd" placeholder="New password">
  <input type="password" name="rpwd" id="rpwd" placeholder="Repeat password">
  <input type="submit" name="update-password" value="<?php $ubtn = get_option('prul_alerts'); echo $ubtn['p_a_button_update']; ?>"">
  
</form>