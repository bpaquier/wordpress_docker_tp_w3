<?php


if(is_user_logged_in()) {
    get_header();
    if(isset($_GET['status'])) {
        if($_GET['status'] === 'posted'){
            ?>
                <p class="info">Your post has been published</p>
            <?php
    } elseif ($_GET['status'] === 'draft') {
            ?>
                <p class="info">Your post is on draft</p>
            <?php
        }
    } else {
        the_content();
    }
    get_footer();
} else {
    wp_redirect(home_url('/login'));
    exit;
}


