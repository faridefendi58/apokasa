<?php $this->beginContent('/layouts/main'); ?>
	<!-- Sidebar -->
	<div class="sidebar collapse">
		<?php $this->widget('adminMainMenu');?>
        	<!--<ul class="navigation">
            	<li class="active"><a href="index-2.html"><i class="fa fa-laptop"></i> Dashboard</a></li>
                <li><a href="charts.html"><i class="fa fa-bar-chart-o"></i> Graphs and charts</a></li>
                <li>
                    <a href="#" class="expand"><i class="fa fa-align-justify"></i> Form components</a>
                    <ul>
                        <li><a href="form_components.html">Form components</a></li>
                        <li><a href="form_validation.html">Form validation</a></li>
                        <li><a href="wysiwyg.html" >WYSIWYG editor</a></li>
                        <li><a href="form_layouts.html">Form layouts</a></li>
                        <li><a href="form_grid.html">Inputs grid</a></li>
                        <li><a href="file_uploader.html">Multiple file uploader</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="expand"><i class="fa fa-tasks"></i> Interface components</a>
                    <ul>
                        <li><a href="visuals.html">Visuals &amp; notifications</a></li>
                        <li><a href="navs.html">Navs &amp; navbars</a></li>
                        <li><a href="panel_options.html">Panels</a></li>
                        <li><a href="icons.html">Icons <span class="label label-danger">190</span></a></li>
                        <li><a href="buttons.html">Buttons</a></li>
                        <li><a href="content_grid.html">Content grid</a></li>
                    </ul>
                </li>
                <li><a href="typography.html"><i class="fa fa-text-height"></i> Typography</a></li>
                <li><a href="gallery.html"><i class="fa fa-picture-o"></i> Gallery</a></li>
                <li>
                    <a href="#" class="expand"><i class="fa fa-table"></i> Tables</a>
                    <ul>
                        <li><a href="tables_static.html">Static tables</a></li>
                        <li><a href="tables_data.html">Data tables</a></li>
                        <li><a href="tables_custom.html">Custom tables</a></li>
                        <li><a href="tables_data_advanced.html">Advanced data tables</a></li>
                    </ul>
                </li>
                <li><a href="calendar.html"><i class="fa fa-calendar"></i> Calendar</a></li>
                <li><a href="#" class="expand"><i class="fa fa-warning"></i> Error pages <span class="label label-warning">6</span></a>
                	<ul>
                        <li><a href="403.html">403 page</a></li>
                        <li><a href="404.html">404 page</a></li>
                        <li><a href="405.html">405 page</a></li>
                        <li><a href="500.html">500 page</a></li>
                        <li><a href="503.html">503 page</a></li>
                        <li><a href="offline.html">Website is offline</a></li>
                    </ul>
                </li>
                <li><a href="#" class="expand"><i class="fa fa-copy"></i> Blank pages <span class="label label-warning">6</span></a>
                    <ul>
                        <li><a href="blank_fixed_navbar.html">Fixed navbar</a></li>
                        <li><a href="blank_static_navbar.html">Static navbar</a></li>
                        <li><a href="blank_collapsed_sidebar.html">Collapsed sidebar</a></li>
                        <li><a href="blank_full_width.html">Full width page</a></li>
                    </ul>
                </li>
            </ul>-->
	</div><!-- /sidebar -->
	<!-- Page content -->
	<div class="page-content">

            <!-- Page title -->
        	<div class="page-title">
                <h5><i class="fa fa-bars"></i> <?php echo Yii::t('menu','Dashboard');?> <small><?php echo Yii::t('menu','Welcome');?>, <?php echo ucfirst(Yii::app()->user->name);?>!</small></h5>
				<div class="btn-group">
					<a href="#" class="btn btn-link btn-lg btn-icon dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cog"></i><span class="caret"></span></a>
					<?php
					$this->widget('zii.widgets.CMenu', array(
						'items'=>$this->menu,
						'htmlOptions'=>array('class'=>'dropdown-menu dropdown-menu-right'),
						'encodeLabel'=>false,
					));
					?>
                </div>
            </div>
            <!-- /page title -->

            <?php echo $content;?>

            <!-- Footer -->
            <div class="footer">
                &copy; Copyright 2015. All rights reserved.</a>
            </div>
            <!-- /footer -->

        </div>
    </div>
<?php $this->endContent(); ?>
<script type="text/javascript">
if (window.addEventListener){ // W3C standard
	window.addEventListener('load', initDOMContentLoaded, false);
}else if (window.attachEvent){ // Microsoft
	window.attachEvent('onload', initDOMContentLoaded);
}
function initDOMContentLoaded(){
	if(typeof jQuery=='undefined'){
		setTimeout(function(){
			$(function(){
				dataReload();
			});
		}, 3000);
	}else{
	$(function(){
		dataReload();
	});
	}
}
function reloadGrid(id, data) {
	dataReload();
	return false;
}
function dataReload(){
	$('input[type=text]').addClass('form-control');
	$('textarea').addClass('form-control');
	$('select').addClass('form-control');
	$('input[type=password]').addClass('form-control');
	$('input[type=submit]').addClass('btn btn-primary');
	$('input[type=button]').addClass('btn btn-primary');
	$('.yiiPager').addClass('dataTables_paginate paging_full_numbers');
	$('.dataTables_paginate').removeClass('yiiPager');
	return false;
}
</script>
