<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="language" content="en"/>
    <meta name="robots" content="noindex">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css"
          href="<?php echo Yii::app()->request->baseUrl . '/css'; ?>/brain/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css"
          href="<?php echo Yii::app()->request->baseUrl . '/css'; ?>/brain/css/brain-theme.css">
    <link rel="stylesheet" type="text/css"
          href="<?php echo Yii::app()->request->baseUrl . '/css'; ?>/brain/css/styles.css">
    <link rel="stylesheet" type="text/css"
          href="<?php echo Yii::app()->request->baseUrl . '/css'; ?>/brain/css/font-awesome.min.css">

    <title><?php echo CHtml::encode(Yii::app()->config->get('site_name')); ?></title>
</head>

<body>
<!-- Navbar -->
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <?php if (!Yii::app()->user->isGuest): ?>
            <div class="navbar-header">
                <div class="hidden-lg pull-right">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-right">
                        <span class="sr-only">Toggle navigation</span>
                        <i class="fa fa-chevron-down"></i>
                    </button>

                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar">
                        <span class="sr-only">Toggle sidebar</span>
                        <i class="fa fa-bars"></i>
                    </button>
                </div>

                <ul class="nav navbar-nav navbar-left-custom">
                    <li class="user dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown">
                            <img src="<?php echo Yii::app()->request->baseUrl . '/uploads/'; ?>images/face6.png" alt="">
                            <span><?php echo Yii::app()->user->name; ?></span>
                            <i class="caret"></i>
                        </a>

                        <?php
                        $this->widget('zii.widgets.CMenu', array(
                            'items' => array(
                                array(
                                    'label' => '<i class="fa fa-mail-forward"></i> Logout</a>',
                                    'url' => array('/' . Yii::app()->controller->module->id . '/default/logout'),
                                    'visible' => !Yii::app()->user->isGuest
                                ),
                            ),
                            'htmlOptions' => array('class' => 'dropdown-menu'),
                            'encodeLabel' => false,
                        ));
                        ?>
                    </li>
                    <li><a class="nav-icon sidebar-toggle"><i class="fa fa-bars"></i></a></li>
                </ul>
            </div>
        <?php else: ?>
            <div class="navbar-header">
                <div class="hidden-lg pull-right">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-right">
                        <span class="sr-only">Toggle navigation</span>
                        <i class="fa fa-chevron-down"></i>
                    </button>

                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar">
                        <span class="sr-only">Toggle sidebar</span>
                        <i class="fa fa-bars"></i>
                    </button>
                </div>

                <ul class="nav navbar-nav navbar-left-custom">
                    <li class="user dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown">
                            <span><?php echo Yii::app()->config->get('site_name'); ?></span>
                        </a>
                    </li>
                    <li><a class="nav-icon sidebar-toggle"><i class="fa fa-bars"></i></a></li>
                </ul>
            </div>
        <?php endif; ?>
        <?php
        $this->widget('zii.widgets.CMenu', array(
            'items' => $this->menu,
            'htmlOptions' => array('class' => 'nav navbar-nav navbar-right collapse', 'id' => 'navbar-right'),
            'encodeLabel' => false,
        ));
        ?>
    </div>
</div>
<!-- /navbar -->
<!-- Page header -->
<div class="container-fluid">
    <div class="page-header">
        <div class="logo">
            <?php echo CHtml::link(CHtml::image(Yii::app()->request->baseUrl . '/uploads/images/' . Yii::app()->config->get('logo')), array('/' . Yii::app()->controller->module->id . '/default')); ?>
        </div>
        <?php if (!Yii::app()->user->isGuest): ?>
            <ul class="middle-nav">
                <li>
                    <a href="<?php echo Yii::app()->createUrl('/' . Yii::app()->controller->module->id . '/reports/view'); ?>"
                       class="btn btn-default"><i class="fa fa-bar-chart-o"></i>
                        <span><?php echo Yii::t('order', 'Statistic'); ?></span></a></li>
                <li>
                    <a href="<?php echo Yii::app()->createUrl('/' . Yii::app()->controller->module->id . '/invoices/view'); ?>"
                       class="btn btn-default">
                        <i class="fa fa-money"></i> <span><?php echo Yii::t('order', 'List Invoices'); ?></span>
                    </a>
                    <div class="label label-info"><?php echo Invoice::getTotalPaid(); ?></div>
                </li>
                <li>
                    <a href="<?php echo Yii::app()->createUrl('/' . Yii::app()->controller->module->id . '/orders/create'); ?>"
                       class="btn btn-default">
                        <i class="fa fa-briefcase"></i> <span><?php echo Yii::t('order', 'New Transaction'); ?></span>
                    </a>
                </li>
            </ul>
        <?php endif; ?>
    </div>
</div>
<!-- /page header -->
<!-- Page container -->
<div class="page-container container-fluid">
    <?php echo $content; ?>
</div><!-- page-container -->

<script type="text/javascript" async
        src="<?php echo Yii::app()->request->baseUrl . '/css'; ?>/brain/js/plugins/interface/collapsible.min.js"></script>
<script type="text/javascript" async
        src="<?php echo Yii::app()->request->baseUrl . '/css'; ?>/brain/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl . '/css'; ?>/brain/js/application.js"
        id="app-js"></script>
<?php $this->widget('ext.widgets.loading.LoadingWidget'); ?>
</body>
</html>
