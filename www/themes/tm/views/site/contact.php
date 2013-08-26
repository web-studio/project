<?php
/* @var $this SiteController */
/* @var $model ContactForm */
/* @var $form CActiveForm */

$this->pageTitle=Yii::app()->name . ' - Контакты';
$this->breadcrumbs=array(
	'Контакты',
);
?>

<h2 class='h2'>Контакты</h2>

<?php if(Yii::app()->user->hasFlash('contact')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('contact'); ?>
</div>

<?php else: ?>

<p class='p1'>
В любой момент вы можете обратится к экспертам Cronofunds.com, заполнив приведенную ниже форму. <br />
    Пожалуйста, указывайте достоверные данные. Максимальный срок ответа - 3 рабочих дня.
</p>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">Поля отмеченные <span class="required">*</span> обязательны для заполнения.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php //echo $form->labelEx($model,'name'); ?>
        <label for="ContactForm_name" class="required"><strong>Name <span class="required">*</span></strong>
		<?php echo $form->textField($model,'name'); ?>
		<?php echo $form->error($model,'name'); ?></label>
	</div>

	<div class="row">
		<?php //echo $form->labelEx($model,'email'); ?>
        <label for="ContactForm_email" class="required"><strong>Email <span class="required">*</span></strong>
		<?php echo $form->textField($model,'email'); ?>
		<?php echo $form->error($model,'email'); ?></label>
	</div>

	<div class="row">
		<?php //echo $form->labelEx($model,'subject'); ?>
        <label for="ContactForm_subject" class="required"><strong>Subject <span class="required">*</span></strong>
		<?php echo $form->textField($model,'subject',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'subject'); ?></label>
	</div>

	<div class="row">
		<?php //echo $form->labelEx($model,'body'); ?>
		<?php echo $form->textArea($model,'body',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'body'); ?>
	</div>

	<?php if(CCaptcha::checkRequirements()): ?>
	<div class="row">
		<?php echo $form->labelEx($model,'verifyCode'); ?>
		<div>
		<?php $this->widget('CCaptcha'); ?>
		<?php echo $form->textField($model,'verifyCode'); ?>
		</div>
		<div class="hint">Пожалуйста введите код с картинки.
		<br/>Буквы вводятся без учета регистра.</div>
		<?php echo $form->error($model,'verifyCode'); ?>
	</div>
	<?php endif; ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Отправить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php endif; ?>