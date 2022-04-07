<?php


if(is_user_logged_in()) {
    wp_redirect(home_url('/'));
    exit();


} else {
    get_header();
    if (isset($_GET['success']) && $_GET['success'] == 'false') {
        ?>
        <p class="alert"><?= $_GET['message'] ?? 'Une erreur est survenue' ?></p>
        <?php
    }
    the_content();

    get_footer();
}