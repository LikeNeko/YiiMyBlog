<?php
use yii\helpers\Url;

?>
<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
	<!-- Indicators -->
	<ol class="carousel-indicators">
		<?php foreach( $data['items'] as $k => $list ): ?>
			<li data-target="#carousel-example-generic" data-slide-to="<?= $k ?>" class="active"></li>
		<?php endforeach; ?>
	</ol>

	<!-- Wrapper for slides -->
	<div class="carousel-inner home-banner" role="listbox">
		<?php foreach( $data['items'] as $k => $list ): ?>
			<div class="item <?= ( isset( $list['active'] ) && $list['active'] ) ? 'active' : ''; ?>">
				<a href="<?= Url::to( $list['url'] ) ?>"><img src="<?= $list['image_url'] ?>"
				                                              alt="<?= $list['label'] ?>">
					<div class="carousel-caption">
						<?= $list['html'] ?>
					</div>
				</a>
			</div>
		<?php endforeach; ?>

	</div>

	<!-- Controls -->
	<a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
		<span class="glyphicon glyphicon-chevron-left"></span>
	</a>
	<a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
		<span class="glyphicon glyphicon-chevron-right"></span>
	</a>
</div>