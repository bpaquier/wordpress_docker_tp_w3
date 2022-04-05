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
    'supports' => ['editor', 'thumbnail', 'title', 'author'],
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
 * METABOX for recipes POST-Type
 */

function recipe_steps() {
    global $post;
    $gpminvoice_group = get_post_meta($post->ID, 'recipe_steps', true);
    wp_nonce_field( 'gpm_repeatable_meta_box_nonce', 'gpm_repeatable_meta_box_nonce' );
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function( $ ){
            $( '#add-row' ).on('click', function() {
                var row = $( '.empty-row.screen-reader-text' ).clone(true);
                row.removeClass( 'empty-row screen-reader-text' );
                row.insertBefore( '#repeatable-fieldset-one tbody>tr:last' );
                return false;
            });

            $( '.remove-row' ).on('click', function() {
                $(this).parents('tr').remove();
                return false;
            });
        });
    </script>
    <table id="repeatable-fieldset-one" width="100%">
        <tbody>
        <?php
        if ( $gpminvoice_group ) :

            foreach ( $gpminvoice_group as $key => $field ) {
                ?>
                <tr>
                    <td width="15%">
                        <h3>Step <?= $key + 1 ?></h3>
                    <td width="70%">
                        <textarea placeholder="Description" cols="55" rows="5" name="TitleDescription[]"> <?php if ($field != '') echo esc_attr( $field ); ?> </textarea></td>
                    <td width="15%"><a class="button remove-row" href="#1">Remove</a></td>
                </tr>
                <?php
            }
        else :
            // show a blank one
            ?>
            <tr>
                <td>
                    <h3>New step</h3>
                <td>
                    <textarea  placeholder="Description" name="TitleDescription[]" cols="55" rows="5">  </textarea>
                </td>
                <td><a class="button  cmb-remove-row-button button-disabled" href="#">Remove</a></td>
            </tr>
        <?php endif; ?>

        <!-- empty hidden one for jQuery -->
        <tr class="empty-row screen-reader-text">
            <td>
                <h3>New step</h3>
            <td>
                <textarea placeholder="Description" cols="55" rows="5" name="TitleDescription[]"></textarea>
            </td>
            <td><a class="button remove-row" href="#">Remove</a></td>
        </tr>
        </tbody>
    </table>
    <p><a id="add-row" class="button" href="#">Add another</a></p>
    <?php
}

add_action('save_post', 'custom_repeatable_meta_box_save');
function custom_repeatable_meta_box_save($post_id) {
    if ( ! isset( $_POST['gpm_repeatable_meta_box_nonce'] ) ||
        ! wp_verify_nonce( $_POST['gpm_repeatable_meta_box_nonce'], 'gpm_repeatable_meta_box_nonce' ) )
        return;

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;

    if (!current_user_can('edit_post', $post_id))
        return;

    $old = get_post_meta($post_id, 'recipe_steps', true);
    $new = array();
    $step = $_POST['TitleDescription'];
    $count = count( $step );
    for ( $i = 0; $i < $count; $i++ ) {
        if ( $step[$i] != '' ) :
            $new[$i] = stripslashes( $step[$i] ); // and however you want to sanitize
        endif;
    }

    if ( !empty( $new ) && $new != $old )
        update_post_meta( $post_id, 'recipe_steps', $new );
    elseif ( empty($new) && $old )
        delete_post_meta( $post_id, 'recipe_steps', $old );


}

function recipe_difficulty() {
    $value = get_post_meta($_GET['post'], 'recipe_difficulty', true) ? get_post_meta($_GET['post'],'recipe_difficulty', true ) : null;
   ?>
        <input type="radio" name="difficulty" value="easy" id="easy" <?= $value == 'easy' or $value === null ? 'checked' : null ?>/>
        <label for="easy">easy</label>
        <br>
        <input type="radio" name="difficulty" value="normal" id="normal" <?= $value === 'normal' ? 'checked' : null ?>/>
        <label for="normal">normal</label>
        <br>
        <input type="radio" name="difficulty" value="hard" id="hard" <?= $value === 'hard' ? 'checked' : null ?>/>
        <label for="hard">hard</label>
    <?php
}

function recipe_price() {
    $value = get_post_meta($_GET['post'], 'recipe_price', true) ? get_post_meta($_GET['post'],'recipe_price', true ) : 0;
    ?>
        <input type="number" name="price" id="price" value="<?= $value; ?>" />
        <label for="price">euros</label>
    <?php
}

function cooking_time() {
    $value = get_post_meta($_GET['post'], 'cooking_time', true) ? get_post_meta($_GET['post'],'cooking_time', true ) : null;
    ?>
        <input type="number" name="cooking_time" id="cooking" value="<?= $value; ?>" />
        <label for="cooking">min</label>
    <?php
}

function preparation_time() {
    $value = get_post_meta($_GET['post'], 'preparation_time', true) ? get_post_meta($_GET['post'],'preparation_time', true ) : null;
    ?>
        <input type="number" name="preparation_time" id="preparation" value="<?= $value; ?>" />
        <label for="preparation">min</label>
    <?php
}

function recipes_meta_box() {
    add_meta_box( 'steps', 'Custom Repeatable', 'recipe_steps', 'recipes', 'normal', );
    add_meta_box('difficulty', 'Difficulty', 'recipe_difficulty', 'recipes', 'normal');
    add_meta_box('price', 'Price', 'recipe_price', 'recipes', 'normal');
    add_meta_box('cooking_time', 'Cooking time', 'cooking_time', 'recipes', 'normal');
    add_meta_box('preparation_time', 'Preparation time', 'preparation_time', 'recipes', 'normal');
}

add_action('add_meta_boxes', 'recipes_meta_box');

function save_difficulty($post_id) {
    update_post_meta($post_id,'recipe_difficulty', $_POST['difficulty']);
    if($_POST['price']) {
        update_post_meta($post_id, 'recipe_price', $_POST['price'] );
    } else {
        delete_post_meta($post_id, 'recipe_price');
    }

    if($_POST['cooking_time']) {
        update_post_meta($post_id, 'cooking_time', $_POST['cooking_time'] );
    } else {
        delete_post_meta($post_id, 'cooking_time');
    }

    if($_POST['cooking_time']) {
        update_post_meta($post_id, 'preparation_time', $_POST['preparation_time'] );
    } else {
        delete_post_meta($post_id, 'preparation_time');
    }
}
add_action('save_post', 'save_difficulty');

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
