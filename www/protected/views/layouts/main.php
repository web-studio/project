<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
<?php Yii::app()->clientScript->registerCoreScript('cookie'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/protected/extensions/bootstrap/assets/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/protected/extensions/bootstrap/assets/css/bootstrap-responsive.css" />

    <script scr = "<?php echo Yii::app()->request->baseUrl; ?>/protected/extensions/bootstrap/assets/js/jquery-2.0.3.js" type = "text/javascript" ></script>
    <script scr = "<?php echo Yii::app()->request->baseUrl; ?>/protected/extensions/bootstrap/assets/js/bootstrap.js" type = "text/javascript" ></script>




	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
	</div><!-- header -->

	<div id="mainmenu">
        <?php
        $this->widget('zii.widgets.CMenu',array(
            'items'=>array(
                array('label'=>Yii::t('app','Home'), 'url'=>$this->createAbsoluteUrl('/')),
                array('label'=>Yii::t('app','News'), 'url'=>array('/news')),
                array('label'=>Yii::t('app','About'), 'url'=>array('/about')),
                array('label'=>Yii::t('app','Contact'), 'url'=>array('/contact')),
                array('label'=>Yii::t('app','Login'), 'url'=>array('/login'),'visible'=>Yii::app()->user->isGuest),
                array('label'=>Yii::t('app','Logout').' ('.Yii::app()->user->name.')', 'url'=>array('/logout'), 'visible'=>!Yii::app()->user->isGuest),
                array('url'=>array('/signup'), 'label'=>Yii::app()->getModule('user')->t("Register"), 'visible'=>Yii::app()->user->isGuest),
                array('url'=>array('/profile'), 'label'=>Yii::app()->getModule('user')->t("Profile"), 'visible'=>!Yii::app()->user->isGuest),
                ((UserModule::isAdmin())
                    ?array('label'=>'Админпанель', 'url'=>array('/admin'))
                    :array()),
            )));
        ?>
	</div><!-- mainmenu -->
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>

	<div class="clear"></div>

	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> by My Company.<br/>
		All Rights Reserved.<br/>
		<?php echo Yii::powered(); ?>
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>
