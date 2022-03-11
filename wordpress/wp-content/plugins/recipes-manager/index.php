<?php
/*
 Plugin Name: Recipes Manager
 Author: Bastien Paquier
 Version: 1.0
 */

if(!defined('ABSPATH')){
    die;
}


add_action( 'init', function () {

    /**
     * CATEGORIE : 'Apéritif'
     */

    $labels_starter = [
        'name' => 'Apéritifs',
        'singular_name' => 'Apéritif',
        'search_items' => 'Rechercher un apéritif',
        'all_items' => 'Tous les apéritifs'
    ];

    $args_starter = [
        'labels' => $labels_starter,
        'public' => true,
        'hierarchical' => true,
        'show_in_rest' => true,
        'show_admin_column' => true
    ];

    register_taxonomy('apéritif', ['recipes'], $args_starter);

    /**
     * CATEGORIE : 'Entrée'
     */

    $labels_appetizer = [
        'name' => 'Entrées',
        'singular_name' => 'Entrée',
        'search_items' => 'Rechercher une entrée',
        'all_items' => 'Toutes les entrées'
    ];

    $args_appetizer = [
        'labels' => $labels_appetizer,
        'public' => true,
        'hierarchical' => true,
        'show_in_rest' => true,
        'show_admin_column' => true
    ];

    register_taxonomy('entrée', ['recipes'], $args_appetizer);

    /**
     * CATEGORIE : 'Plat'
     */

    $labels_dish = [
        'name' => 'Plats',
        'singular_name' => 'Plat',
        'search_items' => 'Rechercher un plat',
        'all_items' => 'Tous les plats'
    ];

    $args_dish = [
        'labels' => $labels_dish,
        'public' => true,
        'hierarchical' => true,
        'show_in_rest' => true,
        'show_admin_column' => true
    ];

    register_taxonomy('plat', ['recipes'], $args_dish);

    /**
     * CATEGORIE : 'Dessert'
     */

    $labels_dessert = [
        'name' => 'Desserts',
        'singular_name' => 'Dessert',
        'search_items' => 'Rechercher un dessert',
        'all_items' => 'Tous les desserts'
    ];

    $args_dessert = [
        'labels' => $labels_dessert,
        'public' => true,
        'hierarchical' => true,
        'show_in_rest' => true,
        'show_admin_column' => true
    ];

    register_taxonomy('dessert', ['recipes'], $args_dessert);


    /**
     * POST-TYPE : Recipes
     */

    register_post_type('recipes', [
        'public' => true,
        'label'  => 'Recettes',
        'hierarchical' => false,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'supports' => [ 'author', 'excerpt', 'page-attributes', 'thumbnail', 'custom-fields', 'title'],
        'capabilities' => [
            'edit_posts' => 'edit_my_recipes',
            'edit_post' => 'edit_my_recipes',
            'edit_published_posts' => 'manage_recipes',
            'read_posts' => 'edit_my_recipes',
            'delete_post' => 'manage_recipes',
            'delete_published_posts	' => 'manage_recipes',
            'publish_posts' => 'manage_recipes'
        ]
    ]);

    flush_rewrite_rules();
} );


/**
 * add capacity to administrator
 * add  a new role
 */


register_activation_hook(__FILE__, function() {
    $admin = get_role('administrator');
    $admin->add_cap('manage_recipes');
    $admin->add_cap('edit_my_recipes');

    add_role('recipes_manager', "Recipes Manager", [
        "manage_recipes"=> true,
        'edit_my_recipes' => true
    ]);

    add_role('recipes_contributor', "Recipes Contributor", [
        'read' => true,
        'publish_posts' => false,
        'edit_my_recipes' => true
    ]);
});

/**
 * delete capacity after desactivated plugin
 * delete custom roles after desactivated plugin
 */

register_deactivation_hook(__FILE__, function() {
    $admin = get_role('administrator');
    $admin->remove_cap('manage_recipes');

    remove_role("recipe_manager");
    remove_role('recipes_contributor');
});

/*
register_uninstall_hook(__FILE__, function () {
    //TODO: clear DB
});*/
