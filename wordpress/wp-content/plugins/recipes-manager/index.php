<?php
/*
 Plugin Name: Recipes Manager
 Author: Bastien Paquier
 Version: 1.0
 */

if (!defined('ABSPATH')) {
  die;
}


add_action('init', function () {

  /**
   * CATEGORIE : 'Plat types'
   */

  $labels_platType = [
    'name' => 'Types de plat',
    'all_items' => 'Tous les types de plat'
  ];

  $args_platType = [
    'labels' => $labels_platType,
    'public' => true,
    'hierarchical' => true,
    'show_in_rest' => true,
    'has_archive' => true,
    'show_admin_column' => true
  ];

  register_taxonomy('type', ['recipes'], $args_platType);

  /**
   * TERMS FOR 'Plat types'
   */

  wp_insert_term('apéritifs', 'type' );
  wp_insert_term('entrées', 'type' );
  wp_insert_term('plats', 'type' );
  wp_insert_term('desserts', 'type' );

	/**
	 * CATEGORIE : 'Ingredients'
	 */

	$labels_ingredients = [
		'name' => 'Ingrédients',
		'all_items' => 'Tous les ingrédients'
	];

	$args_ingredients = [
		'labels' => $labels_ingredients,
		'public' => true,
		'hierarchical' => true,
		'show_in_rest' => true,
		'has_archive' => true,
		'show_admin_column' => true
	];

	register_taxonomy('ingredient', ['recipes'], $args_ingredients);

	/**
	 * CATEGORIE : 'Utensils'
	 */

	$labels_utensils = [
		'name' => 'Ustensiles',
		'all_items' => 'Tous les ustensiles'
	];

	$args_utensils = [
		'labels' => $labels_utensils,
		'public' => true,
		'hierarchical' => true,
		'show_in_rest' => true,
		'has_archive' => true,
		'show_admin_column' => true
	];

	register_taxonomy('utensil', ['recipes'], $args_utensils);


  /**
   * POST-TYPE : Recipes
   */

  register_post_type('recipes', [
    'public' => true,
    'label' => 'Recettes',
    'hierarchical' => false,
    'show_in_menu' => true,
    'show_in_nav_menus' => true,
    'supports' => ['author', 'excerpt', 'page-attributes', 'thumbnail', 'custom-fields', 'title'],
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
});


/**
 * add capacity to administrator
 * add  a new role
 */


register_activation_hook(__FILE__, function () {
  $admin = get_role('administrator');
  $admin->add_cap('manage_recipes');
  $admin->add_cap('edit_my_recipes');

  add_role('recipes_manager', "Recipes Manager", [
    "manage_recipes" => true,
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

register_deactivation_hook(__FILE__, function () {
  $admin = get_role('administrator');
  $admin->remove_cap('manage_recipes');

  remove_role("recipe_manager");
  remove_role('recipes_contributor');
});

/**
 * Add custom field image for taxonomy
 */

function taxonomy_add_custom_field() {
	?>
	<div class="form-field term-image-wrap">
		<label for="cat-image"><?php _e( 'Image' ); ?></label>
		<p><a href="#" class="aw_upload_image_button button button-secondary"><?php _e('Upload Image'); ?></a></p>
		<input type="text" name="category_image" id="cat-image" value="" size="40" />
	</div>
	<?php
}
add_action( 'ingredient_add_form_fields', 'taxonomy_add_custom_field', 10, 2 );
add_action( 'utensil_add_form_fields', 'taxonomy_add_custom_field', 10, 2 );

function taxonomy_edit_custom_field($term) {
	$image = get_term_meta($term->term_id, 'category_image', true);
	?>
	<tr class="form-field term-image-wrap">
		<th scope="row"><label for="category_image"><?php _e( 'Image' ); ?></label></th>
		<td>
			<p><a href="#" class="aw_upload_image_button button button-secondary"><?php _e('Upload Image'); ?></a></p><br/>
			<input type="text" name="category_image" id="cat-image" value="<?php echo $image; ?>" size="40" />
		</td>
	</tr>
	<?php
}
add_action( 'ingredient_edit_form_fields', 'taxonomy_edit_custom_field', 10, 2 );
add_action( 'utensil_edit_form_fields', 'taxonomy_edit_custom_field', 10, 2 );

function save_taxonomy_custom_meta_field( $term_id ) {
	if ( isset( $_POST['category_image'] ) ) {
		update_term_meta($term_id, 'category_image', $_POST['category_image']);
	}
}
add_action( 'edited_category', 'save_taxonomy_custom_meta_field', 10, 2 );
add_action( 'create_category', 'save_taxonomy_custom_meta_field', 10, 2 );


/*
register_uninstall_hook(__FILE__, function () {
    //TODO: clear DB
});*/
