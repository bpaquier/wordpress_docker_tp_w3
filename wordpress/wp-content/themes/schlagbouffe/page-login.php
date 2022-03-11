<?php
/**
 * form de login
 */

//creation user
/*
wp_insert_user([
        'user_pass' => 'password',
        'user_login' => 'name',
        "user_mail" => 'emailemail.com',
        "user_role" => "role"
])*/

?>

<form action="<?= home_url('wp-login.php') ?>" method="post">
    <div>
        <label for="mail">mail</label>
        <input id="mail" type="text" name="log">
    </div>
    <input type="hidden" action="">
    <div>
        <label for="pass">Password</label>
        <input type="password" id="pass" name="pwd">
    </div>
    <div>
        <input type="submit" value="se connecter" name="wp-submit">
    </div>
</form>
