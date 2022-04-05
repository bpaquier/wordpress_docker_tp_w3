
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Red+Hat+Display:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <?php wp_head(); ?>

  <title>ShlagBouff'</title>
</head>
<body>

<header class="header">

    <a href="/"><img class="header__logo" src="<?= home_url(); ?>/wp-content/uploads/2022/03/logo.png" alt="logo ShlagBouff'" /></a>

    <nav class="header__nav">
      <ul class="header__navItems">

        <?php $navMainItems = get_navigation_items('main'); ?>
        <?php $navRecipesItems = get_navigation_items('recipes'); ?>

        <?php foreach ( $navMainItems as $main_item ) : ?>

          <?php $current = ( $main_item->object_id == get_queried_object_id() ) ? 'active' : ''; ?>

          <?php if ($main_item->title === 'Recettes') : ?>
            <li>
              <a class="header__navLink <?= $current ?>" id="nav-link-recipes"><?= $main_item->title ?></a>
              <ul class="header__recipesList" id="nav-list-recipes">
                <li>Catégories</li>

                <?php foreach ( $navRecipesItems as $recipe_item ) : ?>
                    <li><a class="" href="<?= $recipe_item->url ?>"><?= $recipe_item->title ?> <?= $recipe_item->slug ?></a></li>
                <?php endforeach; ?>
              </ul>
            </li>
          <?php else : ?>
            <li><a class="header__navLink <?= $current ?>" href="<?= $main_item->url ?>"><?= $main_item->title ?></a></li>
          <?php endif; ?>

        <?php endforeach; ?>
      </ul>
    </nav>

    <?php if (is_user_logged_in()) : ?>
      <?php global $current_user; get_currentuserinfo(); ?>
      <span class="header__welcomeText">Bonjour <?= $current_user->user_firstname; ?> 👋</span>
      <a class="button" href="<?= wp_logout_url('/') ?>">Déconnexion</a>
    <?php else : ?>
      <a class="button" href="/login">Connexion</a>
    <?php endif; ?>

</header>
  