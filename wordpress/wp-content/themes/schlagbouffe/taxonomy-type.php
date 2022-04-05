<?php

/* HEADER */
get_header();


$taxonomyId = get_queried_object()->term_id;
$taxonomyName = get_queried_object()->name;
$taxonomyParentId = get_queried_object()->parent;
$paramsGetTerms = array(
	'taxonomy' => 'type',
	'hide_empty' => false,
	'child_of' => ''
);

if ($taxonomyParentId === 0) {
	$paramsGetTerms['child_of'] = $taxonomyId;
} else {
	$paramsGetTerms['child_of'] = $taxonomyParentId;
}
?>

<main class="main">
  <?php

    /* HERO */
    get_template_part(
    'template-parts/hero',
    'hero',
        array(
          'type' => 'recipes',
          'title' => $taxonomyName,
          'text' => '',
          'image' => 'https://source.unsplash.com/random/2080x2900'
        )
    );

    /* TAB-BAR */
	$categories = get_terms($paramsGetTerms);
  ?>

  <div class="tabBar">
    <?php foreach($categories as $category) : ?>
      <a class="tabBar__link <?php if($taxonomyId === $category->term_id) echo 'active' ?>" href="<?= get_category_link( $category->term_id ) ?>" title="<?= sprintf( __( "View all posts in %s" ), $category->name ) ?>"><?= $category->name ?></a>
    <?php endforeach; ?>
  </div>

  <?php
  /* RECIPES */
	$recipesArgs = [
      'post_type' => 'recipes',
      'tax_query' => [
         [
          'taxonomy' => 'type',
          'terms' => $taxonomyId,
          'include_children' => true
         ],
      ],
	];

	$recipes = get_posts($recipesArgs);
  ?>
    <div class="recipe-list">
      <div class="recipe-list__header">
      </div>
      <div class="recipe__list-content">
        <div class="recipe-list__list">
          <?php foreach($recipes as $recipe) : ?>
          <?php cd($recipe) ?>
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

