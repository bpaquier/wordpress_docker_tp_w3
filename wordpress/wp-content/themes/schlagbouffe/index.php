<?php

get_header();
$recipesArgs = [
        'post_type' => 'recipes',
        'posts_per_page' => '6',
        'orderby' => 'publish_date',
    ];
$query = new WP_Query($recipesArgs);
?>


  <main class="main">
    <?php
      get_template_part(
        'template-parts/hero',
        'hero',
        array(
          'type' => 'home',
          'title' => 'Bienvenue !',
          'text' => 'Toujours plus d’inspiration en cuisine grâce à nos recettes faciles, rapides et tendances.',
          'image' => 'http://localhost:2345/wp-content/uploads/2022/04/hero-header.png'
        )
      );
    ?>
    <div class="recipe-list">
      <div class="recipe-list__header">
        <?php get_template_part('template-parts/search-bar', 'search-bar'); ?>
      </div>
      <div class="recipe__list-content">
        <h2 class="recipe-list__title">Nos recettes du moment</h2>
        <div class="recipe-list__list">
        <?php while ($query->have_posts()) : ?>
            <?php $query->the_post(); ?>
            <div class="recipe-list__item">
              <?php $args = array('title' => get_the_title(), 'link' => get_the_guid(), 'image' => get_the_post_thumbnail_url()) ?>
              <?php get_template_part('template-parts/recipe-card', 'recipe-card', $args); ?>
            </div>
          <?php endwhile; ?>
        </div>
      </div>
    </div>
  </main>
<?php
get_footer();
?>
