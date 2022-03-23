<?php

get_header();
?>

<main class="main">
  <?php

    /* Hero */
    get_template_part(
  'template-parts/hero',
  'hero',
        array(
          'type' => 'recipes',
          'title' => wp_title('', false),
          'text' => '',
          'image' => 'https://source.unsplash.com/random/2080x2900'
		)
	);

    /* Sub Categories TabBar */
	$taxonomyId = get_queried_object()->term_id;

	$categories = get_terms( array(
		'taxonomy' => 'type',
		'hide_empty' => false,
		'child_of' => $taxonomyId
	) );

  ?>

  <div class="tabBar">
    <?php foreach($categories as $category) : ?>
      <a class="tabBar__link" href="<?= get_category_link( $category->term_id ) ?>" title="<?= sprintf( __( "View all posts in %s" ), $category->name ) ?>"><?= $category->name ?></a>
    <?php endforeach; ?>
  </div>

</main>


