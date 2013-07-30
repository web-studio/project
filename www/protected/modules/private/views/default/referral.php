<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
    $this->module->id,
);?>
    <h1>Партнерская программа</h1>
<p>
<strong>Ваша реферальная ссылка:</strong> <?php  echo  CHtml::link(Yii::app()->request->hostInfo . '/?ref=' . $user->name, Yii::app()->request->hostInfo . '/?ref=' . $user->name) ?><br />
<strong>Процентная ставка: 8%</strong><br />
</p>
<p>
<h2>Список рефералов:</h2>
</p>
<?php if ( !empty($user->refs) ) :?>
    <ol>
        <?php foreach ( $user->refs as $referral ): ?>
            <li><?php echo $referral->user->name?></li>
        <?php endforeach; ?>
    </ol>
<?php else : ?>
    У Вас нет приглашенных инвесторов
<?php endif ?>