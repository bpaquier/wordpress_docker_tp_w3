<?php

add_action('after_setup_theme',
    function () {
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');

        add_theme_support('menus');
    });

function register_recipes_post_type() {
    register_post_type('recipes', [
        'public' => true,
        'label'  => 'Recettes',
        'hierarchical' => false,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'supports' => [ 'author', 'excerpt', 'page-attributes', 'thumbnail', 'custom-fields', 'title']
    ]);
}


add_action( 'init', 'register_recipes_post_type' );