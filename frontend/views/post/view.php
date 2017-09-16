<?php
/**
 * Created by  NekoSakura
 * blog: www.nekosakura.cn
 * Date: 2017/9/5
 * Time: 下午 6:56:55
 */
$this->title = $data['title'];
$this->params['breadcrumbs'][] = ['label' => '文章', 'url' => ['post/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-9">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><?=$data['title'];?></h4>
            </div>
        </div>
        <div class="panel-body">
            <span>作者：<?=$data['user_name']?></span>
            <span>发布：<?=date("Y-m-d H:i:s",$data['created_at'])?></span>
            <span>更新：<?=date("Y-m-d H:i:s",$data['updated_at'])?></span>
            <span>浏览：<?=isset($data['extend']['browser'])?:0;?></span>
            <div class="panel-body">
                <?=$data['content']?>
            </div>

        </div>
        <div class="page-tag">
            标签：
            <?php foreach ($data['tags'] as $tag) : ?>
                <span class="label"><a href="#"><?=$tag?></a></span>
            <?php endforeach;?>
        </div>
        <div class="panel-footer">

        </div>
    </div>
    <div class="col-lg-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                侧边栏内容
            </div>
            <div class="panel-body">
                123
            </div>
        </div>
    </div>
</div>
