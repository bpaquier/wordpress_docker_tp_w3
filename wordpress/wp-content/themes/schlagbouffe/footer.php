<footer class="footer">
  <div class="footer__overlay"></div>
  <div class="footer__links">
    <nav class="footer__navLinks">
      <?php $navMainItems = get_navigation_items('main'); ?>
            <?php $navRecipesItems = get_navigation_items('recipes'); ?>

            <?php foreach ( $navMainItems as $main_item ) : ?>

          <?php $current = ( $main_item->object_id == get_queried_object_id() ) ? 'active' : ''; ?>

          <?php if ($main_item->title === 'Recettes') : ?>
            <ul class="footer__navLinks--links">
                <h3 class="footer__navLinks--title" id="nav-list-recipes">Catégories</h3>

                <?php foreach ( $navRecipesItems as $recipe_item ) : ?>
                    <li class="footer__navLinks--link"><a href="<?= $recipe_item->url ?>"><?= $recipe_item->title ?> <?= $recipe_item->slug ?></a></li>
                <?php endforeach; ?>
              </ul>
            </ul>
          <?php else : ?>
            <ul class="footer__navLinks--links">
                <h3 class="footer__navLinks--title" id="nav-list-recipes"><a href="<?= $main_item->url ?>"><?= $main_item->title ?></a></h3>
                <li></li>
            </ul>
          <?php endif; ?>

        <?php endforeach; ?>
        </nav>
        <a href="/"><img class="footer__logo" src="<?= home_url(); ?>/wp-content/uploads/2022/03/logo.png" alt="logo ShlagBouff'" /></a>
    </div>
</footer>

</body>
<?php wp_footer();?>
</html>
