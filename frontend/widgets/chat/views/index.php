<?php
use yii\helpers\Url;
?>
<div class="panel-title box-title">
	<span><strong>只言片语</strong></span>
	<span class="pull-right"><a href="#" class="font-12">更多</a></span>
</div>
<div class="panel-body">
	<form id="w0" action="/" method="post">
		<div class="form-group input-group field-feed-content reqired">
			<textarea  id="feed-content" class="form-control" name="content" cols="30" rows="10"></textarea>
			<span class="input-group-btn">
				<button type="button" data-url="<?=Url::to(['site/add-feed'])?>" class="btn"></button>
			</span>
		</div>
	</form>
</div>
<?php if(!empty($data['feed'])):?>
<ul class="media-list media-feed feed-index ps-container ps-active-y">
	<?php foreach( $data['feed'] as $list ): ?>
		<li class="media">
			<div class="media-left">
				<a href="#" rel="author" data-origin-title=""></a>
			</div>
			<div class="media-body">
				<div class="media-content"><a href="#"></a></div>
				<div class="media-action">
					<?=date("Y-m-d H:i:s",$list['created_at'])?>
				</div>
			</div>
		</li>
	<?php endforeach;?>
</ul>
<?php endif;?>
