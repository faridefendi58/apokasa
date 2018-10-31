<div class="container-fluid" id="main-container">
	<a id="menu-toggler" href="#">
		<span></span>
	</a>

	<div id="sidebar">
		<div id="sidebar-shortcuts">
			<div id="sidebar-shortcuts-large">
				<?php echo CHtml::link('<i class="icon-envelope-alt"></i>',array('/message/inbox'),array('class'=>'btn btn-small btn-success'));?>

				<?php echo CHtml::link('<i class="icon-pencil"></i>',array('/member/blog'),array('class'=>'btn btn-small btn-info'));?>


				<button class="btn btn-small btn-warning">
					<i class="icon-group"></i>
				</button>

				<?php echo CHtml::link('<i class="icon-cogs"></i>',array('/member/setting'),array('class'=>'btn btn-small btn-danger'));?>
			</div>

			<div id="sidebar-shortcuts-mini">
				<span class="btn btn-success"></span>

				<span class="btn btn-info"></span>

				<span class="btn btn-warning"></span>

				<span class="btn btn-danger"></span>
			</div>
		</div><!--#sidebar-shortcuts-->
		<?php
			$this->widget('zii.widgets.CMenu', array(
				'items'=>array(
					array('label'=>'<i class="icon-home"></i> '.Yii::t('menu','Dashboard'), 'url'=>array('member/index'), 'visible'=>!Yii::app()->user->isGuest),
					array('label'=>'<i class="icon-list"></i> Curriculum Vitae<b class="arrow icon-angle-down"></b>', 'url'=>'#', 
						'items'=>array(
							array('label'=>'<i class="icon-double-angle-right"></i> '.Yii::t('global','Manage').' CV', 'url'=>array('member/curriculumVitae')),
							array('label'=>'<i class="icon-double-angle-right"></i> Print CV', 'url'=>array('/member/printCV','id'=>Yii::app()->user->id,'title'=>Yii::app()->user->first_name)),
						),
						'linkOptions'=>array('class'=>'dropdown-toggle'),
						'visible'=>!Yii::app()->user->isGuest
					),
					array('label'=>'<i class="icon-globe"></i> Blog<b class="arrow icon-angle-down"></b>', 'url'=>'#', 
						'items'=>array(
							array('label'=>'<i class="icon-double-angle-right"></i> '.Yii::t('menu','Create New Post'), 'url'=>array('/member/blog')),
							array('label'=>'<i class="icon-double-angle-right"></i> '.Yii::t('menu','Manage Post'), 'url'=>array('/member/manageBlog')),
						),
						'linkOptions'=>array('class'=>'dropdown-toggle'),
						'visible'=>!Yii::app()->user->isGuest
					),
					array('label'=>'<i class="icon-film"></i> Course<b class="arrow icon-angle-down"></b>', 'url'=>'#', 
						'items'=>array(
							array('label'=>'<i class="icon-double-angle-right"></i> Create New Course', 'url'=>array('/course/create')),
							//array('label'=>'<i class="icon-double-angle-right"></i> Manage Course', 'url'=>array('/course/manage')),
							array('label'=>'<i class="icon-double-angle-right"></i> Explore All Course', 'url'=>array('/course/explore')),
						),
						'linkOptions'=>array('class'=>'dropdown-toggle'),
						'visible'=>!Yii::app()->user->isGuest
					),
					array('label'=>'<i class="icon-th"></i> '.Yii::t('menu','Class').'<b class="arrow icon-angle-down"></b>', 'url'=>'#', 
						'items'=>array(
							array('label'=>'<i class="icon-double-angle-right"></i> '.Yii::t('menu','Create New Class'), 'url'=>array('/class/create')),
							array('label'=>'<i class="icon-double-angle-right"></i> '.Yii::t('class','My Class'), 'url'=>array('/class/manage')),
							array('label'=>'<i class="icon-double-angle-right"></i> Explore All Class', 'url'=>array('/class/explore')),
						),
						'linkOptions'=>array('class'=>'dropdown-toggle'),
						'visible'=>!Yii::app()->user->isGuest
					),
					array('label'=>'<i class="icon-envelope-alt"></i> '.Yii::t('menu','Message').'<b class="arrow icon-angle-down"></b>', 'url'=>'#', 
						'items'=>array(
							array('label'=>'<i class="icon-double-angle-right"></i> '.Yii::t('menu','Compose Message'), 'url'=>array('/message/compose')),
							array('label'=>'<i class="icon-double-angle-right"></i> '.Yii::t('menu','Inbox'), 'url'=>array('/message/inbox')),
							array('label'=>'<i class="icon-double-angle-right"></i> '.Yii::t('menu','Outbox'), 'url'=>array('/message/outbox')),
						),
						'linkOptions'=>array('class'=>'dropdown-toggle'),
						'visible'=>!Yii::app()->user->isGuest
					),
				),
				'id'=>'sidebar_menu',
				'htmlOptions'=>array('class'=>'nav nav-list'),
				'submenuHtmlOptions'=>array('class'=>'submenu'),
				'encodeLabel'=>false,
			));
		?>
		<?php /*
		<ul class="nav nav-list">
			<li class="active">
				<!-- <a href="index.html">
					<i class="icon-dashboard"></i>
					<span>Dashboard</span>
				</a> -->
				<?php echo CHtml::link('<i class="icon-dashboard"></i>'.Yii::t('member','Dashboard'),array('index'));?>
			</li>

			<li>
				<a href="typography.html">
					<i class="icon-text-width"></i>
					<span>Typography</span>
				</a>
			</li>

			<li>
				<a href="#" class="dropdown-toggle">
					<i class="icon-desktop"></i>
					<span>UI Elements</span>
					<b class="arrow icon-angle-down"></b>
				</a>

				<ul class="submenu">
					<li>
						<a href="#">
							<i class="icon-double-angle-right"></i>
							Elements
						</a>
					</li>

					<li>
						<a href="#">
							<i class="icon-double-angle-right"></i>
							Buttons &amp; Icons
						</a>
					</li>

					<li>
						<a href="#">
							<i class="icon-double-angle-right"></i>
							Treeview
						</a>
					</li>
				</ul>
			</li>

			<li>
				<a href="#">
					<i class="icon-list"></i>
					<span>Curriculum Vitae</span>
				</a>
			</li>

			<li>
				<a href="#" class="dropdown-toggle">
					<i class="icon-edit"></i>
					<span>Forms</span>

					<b class="arrow icon-angle-down"></b>
				</a>

				<ul class="submenu">
					<li>
						<a href="#">
							<i class="icon-double-angle-right"></i>
							Form Elements
						</a>
					</li>

					<li>
						<a href="#">
							<i class="icon-double-angle-right"></i>
							Wizard &amp; Validation
						</a>
					</li>

							
				</ul>
			</li>

			<li>
				<a href="#">
					<i class="icon-list-alt"></i>
					<span>Widgets</span>
				</a>
			</li>

			<li>
				<a href="#">
					<i class="icon-calendar"></i>
					<span>Calendar</span>
				</a>
			</li>

			<li>
				<a href="#">
					<i class="icon-picture"></i>
					<span>Gallery</span>
				</a>
			</li>

			<li>
				<a href="#">
					<i class="icon-th"></i>
					<span>Grid</span>
				</a>
			</li>

			<li>
				<a href="#" class="dropdown-toggle">
					<i class="icon-file"></i>
					<span>Other Pages</span>

					<b class="arrow icon-angle-down"></b>
				</a>

				<ul class="submenu">
					<li>
						<?php echo CHtml::link('<i class="icon-double-angle-right"></i>'.Yii::t('member','Account Setting'),array('setting'));?>
					</li>

					<li>
						<?php echo CHtml::link('<i class="icon-double-angle-right"></i>'.Yii::t('member','Curriculum Vitae'),array('curriculumVitae'));?>
					</li>
				</ul>
			</li>
		</ul><!--/.nav-list-->
		*/?>

		<div id="sidebar-collapse">
			<i class="icon-double-angle-left"></i>
		</div>
</div> <!-- end of left menu -->
