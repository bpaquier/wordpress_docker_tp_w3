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
            <input id="id" type="text" name="log" placeholder="identifiant">
        </div>
        <input type="hidden" action="">
        <div>
            <label for="pass">Mot de passe</label>
            <input type="password" id="pass" name="pwd" placeholder="password">
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
            <label for="name">Name</label>
            <input id="name" type="text" name="name" placeholder="Name" required>
        </div>
        <div>
            <label for="mail">Email</label>
            <input id="mail" type="mail" name="mail" placeholder="email" required>
        </div>
        <div>
            <label for="pass">Mot de passe</label>
            <input type="password" id="pass" name="pwd" placeholder="password" required>
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

function cd($data, $label='', $return = false) {
    $debug = debug_backtrace();
    $callingFile = $debug[0]['file'];
    $callingFileLine = $debug[0]['line'];

    ob_start();
    var_dump($data);
    $c = ob_get_contents();
    ob_end_clean();

    $c = preg_replace("/\r\n|\r/", "\n", $c);
    $c = str_replace("]=>\n", '] = ', $c);
    $c = preg_replace('/= {2,}/', '= ', $c);
    $c = preg_replace("/\[\"(.*?)\"\] = /i", "[$1] = ", $c);
    $c = preg_replace('/  /', "    ", $c);
    $c = preg_replace("/\"\"(.*?)\"/i", "\"$1\"", $c);
    $c = preg_replace("/(int|float)\(([0-9\.]+)\)/i", "$1() <span class=\"number\">$2</span>", $c);

    // Syntax Highlighting of Strings. This seems cryptic, but it will also allow non-terminated strings to get parsed.
    $c = preg_replace("/(\[[\w ]+\] = string\([0-9]+\) )\"(.*?)/sim", "$1<span class=\"string\">\"", $c);
    $c = preg_replace("/(\"\n{1,})( {0,}\})/sim", "$1</span>$2", $c);
    $c = preg_replace("/(\"\n{1,})( {0,}\[)/sim", "$1</span>$2", $c);
    $c = preg_replace("/(string\([0-9]+\) )\"(.*?)\"\n/sim", "$1<span class=\"string\">\"$2\"</span>\n", $c);

    $regex = array(
        // Numberrs
        'numbers' => array('/(^|] = )(array|float|int|string|resource|object\(.*\)|\&amp;object\(.*\))\(([0-9\.]+)\)/i', '$1$2(<span class="number">$3</span>)'),
        // Keywords
        'null' => array('/(^|] = )(null)/i', '$1<span class="keyword">$2</span>'),
        'bool' => array('/(bool)\((true|false)\)/i', '$1(<span class="keyword">$2</span>)'),
        // Types
        'types' => array('/(of type )\((.*)\)/i', '$1(<span class="type">$2</span>)'),
        // Objects
        'object' => array('/(object|\&amp;object)\(([\w]+)\)/i', '$1(<span class="object">$2</span>)'),
        // Function
        'function' => array('/(^|] = )(array|string|int|float|bool|resource|object|\&amp;object)\(/i', '$1<span class="function">$2</span>('),
    );

    foreach ($regex as $x) {
        $c = preg_replace($x[0], $x[1], $c);
    }

    $style = '
    /* outside div - it will float and match the screen */
    .dumpr {
        margin: 2px;
        padding: 2px;
        background-color: #fbfbfb;
        float: left;
        clear: both;
    }
    /* font size and family */
    .dumpr pre {
        color: #000000;
        font-size: 9pt;
        font-family: "Courier New",Courier,Monaco,monospace;
        margin: 0px;
        padding-top: 5px;
        padding-bottom: 7px;
        padding-left: 9px;
        padding-right: 9px;
    }
    /* inside div */
    .dumpr div {
        background-color: #fcfcfc;
        border: 1px solid #d9d9d9;
        float: left;
        clear: both;
    }
    /* syntax highlighting */
    .dumpr span.string {color: #c40000;}
    .dumpr span.number {color: #ff0000;}
    .dumpr span.keyword {color: #007200;}
    .dumpr span.function {color: #0000c4;}
    .dumpr span.object {color: #ac00ac;}
    .dumpr span.type {color: #0072c4;}
    ';

    $style = preg_replace("/ {2,}/", "", $style);
    $style = preg_replace("/\t|\r\n|\r|\n/", "", $style);
    $style = preg_replace("/\/\*.*?\*\//i", '', $style);
    $style = str_replace('}', '} ', $style);
    $style = str_replace(' {', '{', $style);
    $style = trim($style);

    $c = trim($c);
    $c = preg_replace("/\n<\/span>/", "</span>\n", $c);

    if ($label == ''){
        $line1 = '';
    } else {
        $line1 = "<strong>$label</strong> \n";
    }

    $out = "\n<!-- Dumpr Begin -->\n".
        "<style type=\"text/css\">".$style."</style>\n".
        "<div class=\"dumpr\">
        <div><pre>$line1 $callingFile : $callingFileLine \n$c\n</pre></div></div><div style=\"clear:both;\">&nbsp;</div>".
        "\n<!-- Dumpr End -->\n";
    if($return) {
        return $out;
    } else {
        echo $out;
    }
}

/**
 * Proper way to enqueue scripts and styles
 */
function get_navigation_items($type) {
  $mainNavItems = [];
  $recipesNavItems = [];

  if ( $menu_items = wp_get_nav_menu_items( 'main-menu' ) ) {

    foreach ( $menu_items as $menu_item ) {
      if($menu_item->title === 'Recettes' || $menu_item->title === 'Actualités') {
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