<?php

/* @var $this yii\web\View */

$this->title = Yii::t('common','Blog')."-"."首页";
?>
<div class="row">
    <div class="col-lg-9">
        <?= \frontend\widgets\banner\BannerWidget::widget()?>
    </div>
    <div class="col-lg-3">
        <?= \frontend\widgets\hot\HotWidget::widget()?>
        <?= \frontend\widgets\tag\TagWidget::widget()?>
    </div>
    <div class="col-lg-9">
        <hr>
<!--        文章列表-->
        <?= \frontend\widgets\post\PostWidget::widget()?>
    </div>
</div>
