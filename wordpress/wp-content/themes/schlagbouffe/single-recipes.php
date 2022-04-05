<?php
get_header();

$dummy = array(
  "rate" => 4,
);
$post = get_post();
$post_meta = get_post_meta($post->ID);
$post_ingredients = get_the_terms($post->ID, 'ingredient', true);
$post_utensils = get_the_terms($post->ID, 'utensil', true);

function get_term_image($term_id){
  return get_term_meta($term_id, 'category_image', true);
}



$difficulty_fr = array(
  'easy' => 'Facile',
  'normal' => 'Moyen',
  'hard' => 'Difficile',
);

function get_cost_fr ($price) {
  if($price == 0) {
    return 'Gratuit';
  } else if ($price < 10) {
    return 'Bon marché';
  } else if ($price < 30) {
    return 'Prix moyen';
  } else if ($price >= 30) {
    return 'Couteux';
  }
}

?>

  <main class="main">
    
    <div class="recipe">
      <div class="recipe__header">
          <h1 class="recipe__header-title"><?= the_title(); ?></h1>
          <div class="recipe__header-rate">
            <?php for ($i = 0; $i < 5; $i++) : ?>
              <span class="recipe__rate-star <?php if($i < $dummy['rate']) echo('active'); ?>">★</span>
            <?php endfor; ?>
          </div>
      </div>
      <div class="recipe__content">
          <div class="recipe__content-image">
            <?= the_post_thumbnail(); ?>
          </div>
          <ul class="recipe__content-infos">
            <?php if(isset($post_meta['recipe_price'])) : ?>
              <?php $price = $post_meta['recipe_price'][0]; ?>
              <li class="recipe__content-info">
                <div class="recipe__content-info-row">
                  <p class="recipe__content-info-label">Prix</p>
                  <p class="recipe__content-info-value"><?= $price ?>€</p>
                </div>
                <p class="recipe__content-info-help">
                  <?= get_cost_fr(intval($price)) ?>
                </p>
              </li>
            <?php endif; ?>
            <?php if(isset($post_meta['recipe_difficulty'][0])) :?>
              <li class="recipe__content-info">
                <div class="recipe__content-info-row">
                  <p class="recipe__content-info-label">Niveau</p>
                  <p class="recipe__content-info-value"><?= $difficulty_fr[$post_meta['recipe_difficulty'][0]] ?></p>
                </div>
                <p class="recipe__content-info-help">
                </p>
              </li>
            <?php endif; ?>
            
            <?php if(isset($post_meta['cooking_time'][0]) && isset($post_meta['preparation_time'][0])): ?>
              <?php $total_time = (int) $post_meta['cooking_time'][0] + (int) $post_meta['preparation_time'][0];               ?>
              <li class="recipe__content-info">
                <div class="recipe__content-info-row">
                  <p class="recipe__content-info-label">Temps total</p>
                  <p class="recipe__content-info-value"><?= $total_time ?> min</p>
                </div>
                <p class="recipe__content-info-help-key-value mt">
                  <span>Préparation :</span>
                  <span><?= $post_meta['preparation_time'][0] ?>min</span>
                </p>
                <p class="recipe__content-info-help-key-value">
                  <span>Cuisson :</span>
                  <span><?= $post_meta['cooking_time'][0] ?>min</span>
                </p>
              </li>
            <?php endif; ?>

          </ul>
      </div>

      <div class="recipe__details">
        <div class="recipe__details-header">
          <div class="recipe__details-header-item active" data-detail="ingredient">Ingrédients</div>
          <div class="recipe__details-header-item" data-detail="utensils">Ustensiles</div>
        </div>

        <!-- INGREDIENT ITEMS -->
        <div class="recipe__details-content active" data-detail-content="ingredient">
          <?php if(isset($post_ingredients)) : ?>
              <div class="recipe__details-content-small-cards">
                <?php foreach($post_ingredients as $ingredient) :  ?>
                  <div class="recipe__details-content-small-card">
                    <p>
                      <span class="recipe__details-content-small-card-name"><?= $ingredient->name ?></span>
                    </p>
                    <?php if(get_term_image($ingredient->term_id) !== "" && get_term_image($ingredient->term_id) !== null) : ?>
                      <div class="recipe__details-content-small-card-img">
                        <img src="<?= get_term_image($ingredient->term_id) ?>" alt="">
                      </div>
                    <?php endif; ?>
                  </div>
                <?php endforeach; ?>
              </div>
          <?php endif; ?>
        </div>
        <!-- USTENSILS ITEMS -->
        <div class="recipe__details-content" data-detail-content="utensils">
          <?php if(isset($post_utensils)) : ?>  
              <div class="recipe__details-content-small-cards">
                <?php foreach($post_utensils as $utensil) :  ?>
                  <div class="recipe__details-content-small-card">
                      <p>
                        <!-- <span class="recipe__details-content-small-card-quantity">1</span> -->
                        <span class="recipe__details-content-small-card-name"><?= $utensil->name ?></span>
                      </p>
                      <?php if(get_term_image($utensil->term_id) !== "" && get_term_image($utensil->term_id) !== null) : ?>
                        <div class="recipe__details-content-small-card-img">
                          <img src="<?= get_term_image($utensil->term_id) ?>" alt="">
                        </div>
                      <?php endif; ?>
                  </div>
                <?php endforeach; ?>
              </div>
          <?php endif; ?>
        </div>

        <div class="recipe__details-input">
          <div class="input-number" data-input-number>
            <div class="input-number__button" data-input-number-minus >-</div>
            <div class="input-number__result"><span data-input-number-value >4</span>&nbsp;personnes</div>
            <div class="input-number__button" data-input-number-plus >+</div>
          </div>
        </div>

        <div class="recipe__details-steps">
          <?php $steps = get_post_meta($post->ID, 'recipe_steps', true); ?>
          <?php if(isset($steps)) : ?>
            <div class="recipe__details-steps-group">
              <h3 class="recipe__details-steps-group-title">Préparation</h3>
              <?php foreach($steps as $i=>$step) : ?>
                <div class="recipe__details-step">
                  <h4 class="recipe__details-step-title">Etape <?= intval($i) + 1 ?> </h4>
                  <p class="recipe__details-step-content"><?= $step ?></p>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </main>
<?php
get_footer();
?>