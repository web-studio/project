<?php
/* @var $this AdminController */
/* @var $model User */
?>

<?php
$this->breadcrumbs=array(
    'Админпанель'
);
?>

<?php
$this->menu=array(
    array('label'=>('Управление пользователями'), 'url'=>array('/user/admin')),
    array('label'=>Yii::t('app','Права доступа'), 'url'=>array('/rights')),
    array('label'=>'Транзакции', 'url'=>array('/admin/userTransaction')),
    array('label'=>'Депозиты', 'url'=>array('/admin/deposit')),
    array('label'=>'Типы депозитов', 'url'=>array('/admin/depositType')),
    array('label'=>'Сообщения', 'url'=>array('/admin/messages')),
    array('label'=>'PerfectMoney', 'url'=>array('/admin/money')),
);

?>
<h1>График потенциальных выплат</h1>
<br />
<p>Общий баланс пользователей: <strong><?php echo (float)User::model()->allAmount; ?></strong><br />
<?php echo CHtml::link('Подробнее...', $this->createAbsoluteUrl('/admin/payments/usersBalance')) ?></p>



<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$deposits,
     'columns'=>array(
         array(
             'name'=>'expire',
             'value'=>'$data->expire',
         ),
         array(
             'name'=>'deposit_amount',
             'value'=>'$data->deposit_amount',
         ),

     ),
 ));

?>
