<?php

$minHeight = '';

if($args['type'] === 'recipes') {
	$minHeight = '38.7rem';
} else {
	$minHeight = '55.4rem';
}

?>
<div class="hero">
  <div class="hero__wrapper" style="background-image: url(<?= $args['image'] ?>); min-height: <?= $minHeight ?>">
    <div class="hero__overlay"></div>
    <div class="hero__content">

      <h1 class="hero__title"><?= $args['title'] ?></h1>

      <?php if ($args['text'] !== '') : ?>
        <p class="hero__text"><?= $args['text'] ?></p>
      <?php endif; ?>

    </div>
  </div>
</div>