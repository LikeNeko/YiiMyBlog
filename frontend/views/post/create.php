<?php
use \yii\helpers\Html;
use \yii\bootstrap\ActiveForm;

$this->title = '创建文章';
$this->params['breadcrumbs'][] = ['label' => '文章', 'url' => ['post/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-9">
        <div class="panel panel-default">
            <div class="panel-heading">
                添加内容
            </div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin() ?>

                <?= $form->field($model, 'title') ?>

                <?= $form->field($model, 'cat_id')->dropDownList($cat) ?>

                <?= $form->field($model, 'label_img')->widget('common\widgets\file_upload\FileUpload',[
                    'config'=>[
                    ]
                ]) ?>

                <?= $form->field($model, 'content')->widget('common\widgets\ueditor\Ueditor',[
                    'options'=>[
                        'initialFrameHeight' => 450,
                    ]
                ]) ?>

                <?= $form->field($model, 'tags')->widget('common\widgets\tags\TagWidget') ?>


                <div class="form-group">
                    <?= Html::submitButton('发布', ['class' => 'btn btn-success']); ?>
                </div>

                <?php $form->end() ?>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                添加内容
            </div>
            <div class="panel-body">
                123
            </div>
        </div>
    </div>
</div>