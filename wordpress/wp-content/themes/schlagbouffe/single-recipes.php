<?php
get_header();

$dummy = array(
  "rate" => 4,
);
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
            <img src="https://source.unsplash.com/random/700x300" alt="">
          </div>
          <ul class="recipe__content-infos">
            <li class="recipe__content-info">
              <div class="recipe__content-info-row">
                <p class="recipe__content-info-label">Prix</p>
                <p class="recipe__content-info-value">5,20</p>
              </div>
              <p class="recipe__content-info-help">
                Bon marché
              </p>
            </li>
            <li class="recipe__content-info">
              <div class="recipe__content-info-row">
                <p class="recipe__content-info-label">Niveau</p>
                <p class="recipe__content-info-value">Très facile</p>
              </div>
              <p class="recipe__content-info-help">
              </p>
            </li>
            <li class="recipe__content-info">
              <div class="recipe__content-info-row">
                <p class="recipe__content-info-label">Temps total</p>
                <p class="recipe__content-info-value">35 min</p>
              </div>
              <p class="recipe__content-info-help-key-value mt">
                <span>Préparation :</span>
                <span>10min</span>
              </p>
              <p class="recipe__content-info-help-key-value">
                <span>Cuisson :</span>
                <span>25min</span>
              </p>
            </li>
          </ul>
      </div>

      <div class="recipe__details">
        <div class="recipe__details-header">
          <div class="recipe__details-header-item active" data-detail="ingredient">Ingrédients</div>
          <div class="recipe__details-header-item" data-detail="utensils">Ustensiles</div>
        </div>

        <!-- INGREDIENT ITEMS -->
        <div class="recipe__details-content active" data-detail-content="ingredient">
            <div class="recipe__details-content-small-cards">
              <div class="recipe__details-content-small-card">
                <p>
                  <span>1</span>
                  <span>oignons</span>
                </p>
                <div class="recipe__details-content-small-card-img">
                  <img src="https://source.unsplash.com/random/500x500" alt="">
                </div>
              </div>
              <div class="recipe__details-content-small-card">
                <p>
                  <span class="recipe__details-content-small-card-quantity">1</span>
                  <span class="recipe__details-content-small-card-name">oignons</span>
                </p>
                <div class="recipe__details-content-small-card-img">
                  <img src="https://source.unsplash.com/random/500x500" alt="">
                </div>
              </div>
            </div>
        </div>
        <!-- USTENSILS ITEMS -->
        <div class="recipe__details-content" data-detail-content="utensils">
          <div class="recipe__details-content-small-cards">
              <div class="recipe__details-content-small-card">
                <p>
                  <span>Louche</span>
                </p>
                <div class="recipe__details-content-small-card-img">
                  <img src="https://source.unsplash.com/random/500x500" alt="">
                </div>
              </div>
              <div class="recipe__details-content-small-card">
                <p>
                  <span class="recipe__details-content-small-card-name">Mixeur</span>
                </p>
                <div class="recipe__details-content-small-card-img">
                  <img src="https://source.unsplash.com/random/500x500" alt="">
                </div>
              </div>
              <div class="recipe__details-content-small-card">
                <p class="recipe__details-content-small-card-texts">
                  <span class="recipe__details-content-small-card-name">Blendeur chauffant</span>
                </p>
                <div class="recipe__details-content-small-card-img">
                  <img src="https://source.unsplash.com/random/500x500" alt="">
                </div>
              </div>
            </div>
        </div>

        <div class="recipe__details-input">
          <div class="input-number" data-input-number>
            <div class="input-number__button" data-input-number-minus >-</div>
            <div class="input-number__result"><span data-input-number-value >4</span>&nbsp;personnes</div>
            <div class="input-number__button" data-input-number-plus >+</div>
          </div>
        </div>

        <div class="recipe__details-steps">
          <div class="recipe__details-steps-group">
            <h3 class="recipe__details-steps-group-title">Préparation</h3>
            <div class="recipe__details-step">
              <h4 class="recipe__details-step-title">Etape 1</h4>
              <p class="recipe__details-step-content">Epluchez et lavez tous les légumes, épluchez et émincez l'oignon, coupez les légumes en rondelles.</p>
            </div>
            <div class="recipe__details-step">
              <h4 class="recipe__details-step-title">Etape 1</h4>
              <p class="recipe__details-step-content">Epluchez et lavez tous les légumes, épluchez et émincez l'oignon, coupez les légumes en rondelles.</p>
            </div>
            <div class="recipe__details-step">
              <h4 class="recipe__details-step-title">Etape 1</h4>
              <p class="recipe__details-step-content">Epluchez et lavez tous les légumes, épluchez et émincez l'oignon, coupez les légumes en rondelles.</p>
            </div>
            <div class="recipe__details-step">
              <h4 class="recipe__details-step-title">Etape 1</h4>
              <p class="recipe__details-step-content">Epluchez et lavez tous les légumes, épluchez et émincez l'oignon, coupez les légumes en rondelles.</p>
            </div>
          </div>
          <div class="repice__details-steps-group"></div>
        </div>
      </div>
    </div>
    
    


  </main>
<?php
get_footer();
?>