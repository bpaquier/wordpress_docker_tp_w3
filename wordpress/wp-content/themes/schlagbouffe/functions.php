<?php

add_action('after_setup_theme',
    function () {
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');

        add_theme_support('menus');

        //Hide admin bar for suscriber profile
        if(current_user_can("suscriber") && !is_admin()) {
            show_admin_bar(false);
        }
    });

/**
 * Shortcodes
 */

add_shortcode('login_form', function () {
    ob_start(); ?>
    <form action='<?= home_url('wp-login.php') ?>' method='post'>
        <div>
            <label for="id">Identifiant</label>
            <input id="id" type="text" name="log" placeholder="Identifiant">
        </div>
        <input type="hidden" action="">
        <div>
            <label for="pass">Mot de passe</label>
            <input type="password" id="pass" name="pwd" placeholder="•••••••••••">
        </div>
        <div>
            <input type="submit" value="se connecter" name="wp-submit">
        </div>
    </form>
    <?php return ob_get_clean();

});

add_shortcode('register_form', function() {
    ob_start(); ?>
    <form action="<?= admin_url('admin-post.php')?>" method="post">
        <div>
            <label for="name">Nom</label>
            <input id="name" type="text" name="name" placeholder="Nom" required>
        </div>
        <div>
            <label for="mail">Email</label>
            <input id="mail" type="mail" name="mail" placeholder="Email" required>
        </div>
        <div>
            <label for="pass">Mot de passe</label>
            <input type="password" id="pass" name="pwd" placeholder="•••••••••••" required>
        </div>
        <div>
            <label for="role">Choisir un role</label>
            <select id="role" name="role" required>
                <option value="recipes_contributor">Contributor</option>
                <option value="administrator">admin</option>
                <option value="recipes_manager">Recipes Manager</option>
            </select>
        </div>
        <input type="hidden" name="action" value="wp_user_register">
        <input type="submit" value="Envoyer" >
        <?php wp_nonce_field('register', 'register'); ?>
    </form>
    <?php return ob_get_clean();
});

/**
 * handle register form
 */
add_action('admin_post_wp_user_register', function() {
    if (!wp_verify_nonce($_POST['register'], 'register')) {
        die('nonce invalide');
    }

    $name = $_POST['name'];
    $mail = $_POST['mail'];
    $pwd = $_POST['pwd'];
    $role = $_POST['role'];

    if(isset($name) and isset($mail) and isset($pwd) and isset($role)) {
        wp_insert_user([
            'user_pass' => $pwd,
            'user_login' => $name,
            "user_mail" => $mail,
            "role" => $role
        ]);
        wp_redirect(home_url() . $_POST['_wp_http_referer'] . '?success=true');
    } else {
        wp_redirect(home_url() . $_POST['_wp_http_referer'] . '?success=false');
    }
});

/**
 * Proper way to enqueue scripts and styles
 */
function get_navigation_items($type) {
  $mainNavItems = [];
  $recipesNavItems = [];

  if ( $menu_items = wp_get_nav_menu_items( 'main-menu' ) ) {

    foreach ( $menu_items as $menu_item ) {
      if($menu_item->title === 'Recettes' || $menu_item->title === 'Actualités' || $menu_item->title === 'Ajouter une recette') {
        $mainNavItems[] = $menu_item;
      } else {
        $recipesNavItems[] = $menu_item;
      }
    }

    if($type === 'main') {
      return $mainNavItems;
    } else if ($type === 'recipes') {
      return $recipesNavItems;
    }
  }
}


/**
 * Proper way to enqueue scripts and styles
 */
function wpdocs_theme_name_scripts() {
    wp_enqueue_style( 'style', get_stylesheet_uri() );
    wp_enqueue_script( 'script.js', get_template_directory_uri() . '/assets/js/script.js', [], false, true );
}
add_action( 'wp_enqueue_scripts', 'wpdocs_theme_name_scripts' );

function aw_include_script() {

	if ( ! did_action( 'wp_enqueue_media' ) ) {
		wp_enqueue_media();
	}

	wp_enqueue_script( 'awscript', get_stylesheet_directory_uri() . '/assets/js/awscript.js', array('jquery'), null, false );
}
add_action( 'admin_enqueue_scripts', 'aw_include_script' );