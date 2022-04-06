<?php
/*
 Plugin Name: Recipes Manager
 Author: Bastien Paquier
 Description: Ce plugin permet de pouvoir ajouter des recettes, crée des roles définis pour les administer et permet de gerer les formulaires d'ajout de recettes grâce à un shortcode.
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
            $new[$i] = $step[$i]; // and however you want to sanitize
        endif;
    }

    if ( !empty( $new ) && $new != $old )
        update_post_meta( $post_id, 'recipe_steps', $new );
    elseif ( empty($new) && $old )
        delete_post_meta( $post_id, 'recipe_steps', $old );


}

function recipe_difficulty() {

    $value = isset($_GET['post']) ? get_post_meta($_GET['post'], 'recipe_difficulty', true) ? get_post_meta($_GET['post'],'recipe_difficulty', true ) : null : null;

   ?>
        <input type="radio" name="difficulty" value="easy" id="easy" <?= $value === 'easy' || $value === null ? 'checked' : null ?>/>
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
    $value = isset($_GET['post']) ? get_post_meta($_GET['post'], 'recipe_price', true) ? get_post_meta($_GET['post'],'recipe_price', true ) : 0 : 0;
    ?>
        <input type="number" name="price" id="price" value="<?= $value; ?>" />
        <label for="price">euros</label>
    <?php
}

function cooking_time() {
    $value = isset($_GET['post']) ? get_post_meta($_GET['post'], 'cooking_time', true) ? get_post_meta($_GET['post'],'cooking_time', true ) : 0 : 0;
    ?>
        <input type="number" name="cooking_time" id="cooking" value="<?= $value; ?>" />
        <label for="cooking">min</label>
    <?php
}

function preparation_time() {
    $value = isset($_GET['post']) ? get_post_meta($_GET['post'], 'preparation_time', true) ? get_post_meta($_GET['post'],'preparation_time', true ) : 0 : 0;
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
    if(isset($_POST['difficulty'])) {
        update_post_meta($post_id,'recipe_difficulty', $_POST['difficulty']);
    }

    if(isset($_POST['price'])) {
        update_post_meta($post_id, 'recipe_price', $_POST['price'] );
    } else {
        delete_post_meta($post_id, 'recipe_price');
    }

    if(isset($_POST['cooking_time'])) {
        update_post_meta($post_id, 'cooking_time', $_POST['cooking_time'] );
    } else {
        delete_post_meta($post_id, 'cooking_time');
    }

    if(isset($_POST['cooking_time'])) {
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
    // cd($_POST['category_image']);
    // die();
	if ( isset( $_POST['category_image'] ) ) {
		update_term_meta($term_id, 'category_image', $_POST['category_image']);
	}
}
add_action( 'edited_ingredient', 'save_taxonomy_custom_meta_field', 10, 2 );
add_action( 'edited_utensil', 'save_taxonomy_custom_meta_field', 10, 2 );
add_action( 'create_ingredient', 'save_taxonomy_custom_meta_field', 10, 2 );
add_action( 'create_utensil', 'save_taxonomy_custom_meta_field', 10, 2 );

/**
 * add post Form shortcode
 */
add_shortcode('new_post_form', function () {
    $types = get_terms([
        'taxonomy' => 'type',
        'hide_empty' => false
    ]);

    $ingredients = get_terms([
        'taxonomy' => 'ingredient',
        'hide_empty' => false
    ]);

    $ustensils = get_terms([
            "taxonomy" => 'utensil',
        'hide_empty' => false
    ]);

    $userID = get_current_user_id();
    $userRole = get_userdata($userID)->roles;

    ob_start(); ?>
        <form action='<?= admin_url('admin-post.php') ?>' method='post' enctype="multipart/form-data">
            <div>
                <p for="title">Title</p>
                <input id="title" type="text" name="title">
            </div>
            <div>
                <p>Image</p>
                <input type="file" name="image_upload" id="image_upload" multiple="false">
            </div>
            <div>
                <p for="description">Description</p>
                <textarea id="description" name="description" rows="10"></textarea>
            </div>
            <div>
                <p>Difficulty</p>
                <div class="group">
                    <input type="radio" name="difficulty" value="easy" id="easy" />
                    <label for="easy">easy</label>
                </div>
                <div class="group">
                    <input type="radio" name="difficulty" value="normal" id="normal" />
                    <label for="normal">normal</label>
                </div>
                <div class="group">
                    <input type="radio" name="difficulty" value="hard" id="hard" />
                    <label for="hard">hard</label>
                </div>
            </div>
            <div>
                <p>Price</p>
                <div class=group>
                <input type="number" name="price" id="price"  />
                <label for="price">euros</label>
                </div>
            </div>
            <div>
                <p>Preparation time</p>
                <div class="group">
                <input type="number" name="preparation_time" id="preparation" />
                <label for="preparation">min</label>
                </div>
            </div>
            <div>

                <p>Cooking time</p>
                <div class="group">
                <input type="number" name="cooking_time" id="cooking" />
                <label for="cooking">min</label>
                </div>
            </div>
            <?php if(count($types) > 0) : ?>
                <p>Ingredients</p>
                <div class="groupContainer">
                    <?php foreach ($types as $key => $type) : ?>
                            <div class="group">
                                <input type="checkbox" name="type <?= $type->term_id; ?>" id="<?= 'type' . $key ?>" />
                                <label for="<?= 'type' . $key ?>"><?= $type->name; ?></label>
                            </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php if(count($ingredients) > 0) : ?>
                <p>Ingredients</p>
                <div class="groupContainer">
                    <?php foreach ($ingredients as $key => $ingredient) : ?>
                    <div class="group">
                        <input type="checkbox" name="ingredient <?= $ingredient->term_id; ?>" id="<?= 'ingredient' . $key ?>" />
                        <label for="<?= 'ingredient' . $key ?>"><?= $ingredient->name; ?></label>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php if(count($ustensils) > 0) : ?>
                <p>Ustensils</p>
                <div class="groupContainer">
                    <?php foreach ($ustensils as $key => $ustensil) : ?>
                    <div class="group">
                        <input type="checkbox" name="ustensil <?= $ustensil->term_id; ?>" id="<?= 'ustensil' . $key ?>" />
                        <label for="<?= 'ustensil' . $key ?>"><?= $ustensil->name; ?></label>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div class="steps">
                <p>Steps</p>
                <div class="group">
                    <input type="text" name="step-1" placeholder="step 1">
                </div>
            </div>
            <button class="newStep">add new step</button>
            <input type="hidden" name="action" value="recipe_form">
            <?php wp_nonce_field('add_recipe', 'add_recipe'); ?>
            <?php wp_referer_field() ?>
            <div>
                <input type="submit" value="<?= in_array( 'recipes_contributor', $userRole) ? 'Save on draft' : 'Publish' ?>" name="wp-submit">
            </div>
        </form>
        <script>
            let count = 2
            document.querySelector('.newStep').addEventListener('click', (e) => {
                e.preventDefault()
                const container = document.createElement('div');
                container.className = "group"
                container.innerHTML = "<input type='text' name='step-" + count +"' placeholder='step " + count + "'>"
                document.querySelector('.steps').appendChild(container)
                count++
            } )
        </script>
    <?php return ob_get_clean();
});


add_action('admin_post_recipe_form', function() {

    if (!wp_verify_nonce($_POST['add_recipe'], 'add_recipe')) {
        die('nonce invalide');
    }

    $userID = get_current_user_id();
    $userRole = get_userdata($userID)->roles;


   function getSteps() {
       $array = array();
       $i = 0;
       foreach ($_POST as $key => $value) {
           if(str_contains($key, 'step')) {
               $array[$i] = stripslashes($value);
               $i++;
           }
       }
       return $array;
   }

   function getTaxo($taxo) {
       $array = array();

       foreach ($_POST as $key => $value) {
           if(str_contains($key, $taxo)) {
               $array[] = stripslashes(explode('_',$key)[1]);
           }
       }
       return $array;
   }

   $args = [
        'post_title' => $_POST['title'],
        'post_content' => $_POST['description'],
        'post_type' => 'recipes',
        'post_status' => in_array( 'recipes_contributor', $userRole) ? 'draft' : 'publish',
        'post_author' => $userID,
        'meta_input' => [
            'recipe_difficulty' => $_POST['difficulty'] ?? 'easy' ,
            'recipe_steps' => getSteps(),
            'recipe_price' => $_POST['price'],
            'cooking_time' => $_POST['cooking_time'],
            'preparation_time' => $_POST['preparation_time']
        ],
       'tax_input' => [
               'ingredient' => getTaxo('ingredient'),
                'utensil' => getTaxo('ustensil'),
                'type' => getTaxo('type')
       ]
   ];

   $postId = wp_insert_post($args);

   if($_FILES['image_upload']['error'] == 0) {
       $attachementId = media_handle_upload('image_upload', $postId);

       if(is_wp_error($attachementId)) {
           wp_redirect($_POST['_wp_http_referer'] . '?status=uploadError');
       } else {
            set_post_thumbnail($postId, $attachementId);
       }
   }



   $status = in_array( 'recipes_contributor', $userRole) ? 'draft' : 'publish';

   wp_redirect($_POST['_wp_http_referer'] . '?status=' . $status);

   exit();
});

/*
register_uninstall_hook(__FILE__, function () {
    //TODO: clear DB
});*/
