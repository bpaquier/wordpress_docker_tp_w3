<?php


if(is_user_logged_in()) {
    wp_redirect(home_url('/login'));
    exit();
} else {
    get_header();
    if(isset($_GET['status'])) {
        if ($_GET['status'] === 'draft') {
            ?>
            <p class="info">Your post is on draft</p>
            <?php
        } elseif ($_GET['status'] === "uploadError") {
            ?>
            <p class="alert">Can not upload image</p>
            <?php
        } elseif ($_GET['status'] === "error") {
            ?>
            <p class="alert">Error when publishing, please contact support</p>
            <?php
        }
    } else {
        the_content();
    }
    get_footer();
}


