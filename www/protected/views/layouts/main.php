<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html lang="en">
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

    <link rel="stylesheet" type="text/css" href="<?php echo     Yii::app()->request->baseUrl; ?>/protected/extensions/bootstrap/assets/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo     Yii::app()->request->baseUrl; ?>/protected/extensions/bootstrap/assets/css/bootstrap-responsive.css" />
    <script type = "text/javascript" scr = "<?php echo     Yii::app()->request->baseUrl; ?>/protected/extensions/bootstrap/assets/js/bootstrap.js"></script>
    <script type = "text/javascript" scr = "<?php echo     Yii::app()->request->baseUrl; ?>http://code.jquery.com/jquery-1.10.2.js"></script>

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
                array('label'=>Yii::t('app','Home'), 'url'=>array('/site/index')),
                array('label'=>Yii::t('app','About'), 'url'=>array('/site/page', 'view'=>'about')),
                array('label'=>Yii::t('app','Contact'), 'url'=>array('/site/contact')),
                array('label'=>Yii::t('app','Login'), 'url'=>array('/user/login'),'visible'=>Yii::app()->user->isGuest),
                array('label'=>Yii::t('app','Logout').' ('.Yii::app()->user->name.')', 'url'=>array('/user/logout'), 'visible'=>!Yii::app()->user->isGuest),
                array('url'=>Yii::app()->getModule('user')->registrationUrl, 'label'=>Yii::app()->getModule('user')->t("Register"), 'visible'=>Yii::app()->user->isGuest),
                array('url'=>Yii::app()->getModule('user')->profileUrl, 'label'=>Yii::app()->getModule('user')->t("Profile"), 'visible'=>!Yii::app()->user->isGuest),
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
