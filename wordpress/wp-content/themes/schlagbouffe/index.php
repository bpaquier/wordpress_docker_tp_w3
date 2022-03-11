<?php
get_header();

$dummy = [
  array(
  "title" => "Gateau aux chocolat sans oeuf",
  "image" => "https://source.unsplash.com/random/1000x1000",
  "link" => "https://www.google.com",
  ),
  array(
    "title" => "Crèpe Saumon de Shlag au fromage de catin",
    "image" => "https://source.unsplash.com/random/1000x1000",
    "link" => "https://www.google.com",
    ),
  array(
    "title" => "Soupe de topinambours",
    "image" => "https://source.unsplash.com/random/1000x1000",
    "link" => "https://www.google.com",
  ),
  array(
    "title" => "Mont d’or au four",
    "image" => "https://source.unsplash.com/random/1000x1000",
    "link" => "https://www.google.com",
  ),
  array(
    "title" => "Crèpe Saumon de Shlag au fromage de catin",
    "image" => "https://source.unsplash.com/random/1000x1000",
    "link" => "https://www.google.com",
  ),
  array(
    "title" => "Crèpe Saumon de Shlag au fromage de catin",
    "image" => "https://source.unsplash.com/random/1000x1000",
    "link" => "https://www.google.com",
  )]
?>

  <main class="main">
    <?php get_template_part('template-parts/hero', 'hero'); ?>
    <div class="recipe-list">
      <div class="recipe-list__header">
        <?php get_template_part('template-parts/search-bar', 'search-bar'); ?>
      </div>
      <div class="recipe__list-content">
        <h2 class="recipe-list__title">Nos recettes du moment</h2>
        <div class="recipe-list__list">
        <?php foreach($dummy as $recipe): ?>
          <div class="recipe-list__item">
            <?php get_template_part('template-parts/recipe-card', 'recipe-card', $recipe); ?>
          </div>
        <?php endforeach; ?>
        </div>
      </div>
    </div>
    
  </main>
<?php
get_footer();
?>