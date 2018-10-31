<?php
$this->widget('zii.widgets.CMenu', array(
    'items' => array(
        array('label' => '<i class="fa fa-laptop"></i> <span>' . Yii::t('menu', 'Dashboard') . '</span>', 'url' => array('/' . Yii::app()->controller->module->id . '/default/index'), 'visible' => !Yii::app()->user->isGuest),
        array('label' => '<i class="fa fa-briefcase"></i> <span>' . Yii::t('order', 'Orders') . '</span><b class="arrow icon-angle-down"></b>', 'url' => '#',
            'items' => array(
                array('label' => '<i class="fa fa-caret-right"></i> ' . Yii::t('order', 'Overview'), 'url' => array('/' . Yii::app()->controller->module->id . '/orders/view'), 'visible' => RbacUserAccess::isChecked(Yii::app()->controller->module->id, 'orders', Yii::app()->user->id, 'read_p')),
                array('label' => '<i class="fa fa-caret-right"></i> ' . Yii::t('order', 'Create Sales'), 'url' => array('/' . Yii::app()->controller->module->id . '/orders/create'), 'visible' => RbacUserAccess::isChecked(Yii::app()->controller->module->id, 'orders', Yii::app()->user->id, 'create_p')),
            ),
            'itemOptions' => array('class' => 'nav-parent'),
            'linkOptions' => array('class' => 'expand'),
            'visible' => RbacUserAccess::isChecked(Yii::app()->controller->module->id, 'orders', Yii::app()->user->id, 'read_p')
        ),
        array('label' => '<i class="fa fa-money"></i> <span>' . Yii::t('order', 'Invoices') . '</span><b class="arrow icon-angle-down"></b>', 'url' => '#',
            'items' => array(
                array('label' => '<i class="fa fa-caret-right"></i> ' . Yii::t('order', 'Overview'), 'url' => array('/' . Yii::app()->controller->module->id . '/invoices/view'), 'visible' => RbacUserAccess::isChecked(Yii::app()->controller->module->id, 'invoices', Yii::app()->user->id, 'read_p')),
            ),
            'itemOptions' => array('class' => 'nav-parent'),
            'linkOptions' => array('class' => 'expand'),
            'visible' => RbacUserAccess::isChecked(Yii::app()->controller->module->id, 'invoices', Yii::app()->user->id, 'read_p')
        ),
        array('label' => '<i class="fa fa-suitcase"></i> <span>' . Yii::t('order', 'Products') . '</span><b class="arrow icon-angle-down"></b>', 'url' => '#',
            'items' => array(
                array('label' => '<i class="fa fa-caret-right"></i> ' . Yii::t('order', 'Overview'), 'url' => array('/' . Yii::app()->controller->module->id . '/products/view'), 'visible' => RbacUserAccess::isChecked(Yii::app()->controller->module->id, 'products', Yii::app()->user->id, 'read_p')),
                array('label' => '<i class="fa fa-caret-right"></i> ' . Yii::t('global', 'Create') . ' ' . Yii::t('order', 'Product'), 'url' => array('/' . Yii::app()->controller->module->id . '/products/create'), 'visible' => RbacUserAccess::isChecked(Yii::app()->controller->module->id, 'products', Yii::app()->user->id, 'create_p')),
            ),
            'itemOptions' => array('class' => 'nav-parent'),
            'linkOptions' => array('class' => 'expand'),
            'visible' => RbacUserAccess::isChecked(Yii::app()->controller->module->id, 'products', Yii::app()->user->id, 'read_p')
        ),
        array('label' => '<i class="fa fa-file-text-o"></i> <span>' . Yii::t('order', 'Promo') . '</span><b class="arrow icon-angle-down"></b>', 'url' => '#',
            'items' => array(
                array('label' => '<i class="fa fa-caret-right"></i> ' . Yii::t('order', 'Overview'), 'url' => array('/' . Yii::app()->controller->module->id . '/promos/view'), 'visible' => RbacUserAccess::isChecked(Yii::app()->controller->module->id, 'promos', Yii::app()->user->id, 'read_p')),
                array('label' => '<i class="fa fa-caret-right"></i> ' . Yii::t('global', 'Create') . ' ' . Yii::t('order', 'Promo'), 'url' => array('/' . Yii::app()->controller->module->id . '/promos/create'), 'visible' => RbacUserAccess::isChecked(Yii::app()->controller->module->id, 'promos', Yii::app()->user->id, 'create_p')),
            ),
            'itemOptions' => array('class' => 'nav-parent'),
            'linkOptions' => array('class' => 'expand'),
            'visible' => RbacUserAccess::isChecked(Yii::app()->controller->module->id, 'promos', Yii::app()->user->id, 'read_p')
        ),
        array('label' => '<i class="fa fa-users"></i> <span>' . Yii::t('customer', 'Customer') . '</span><b class="arrow icon-angle-down"></b>', 'url' => '#',
            'items' => array(
                array('label' => '<i class="fa fa-caret-right"></i> ' . Yii::t('customer', 'Overview'), 'url' => array('/' . Yii::app()->controller->module->id . '/customers/view'), 'visible' => RbacUserAccess::isChecked(Yii::app()->controller->module->id, 'customers', Yii::app()->user->id, 'read_p')),
                array('label' => '<i class="fa fa-caret-right"></i> ' . Yii::t('global', 'Create') . ' ' . Yii::t('customer', 'Customer'), 'url' => array('/' . Yii::app()->controller->module->id . '/customers/create'), 'visible' => RbacUserAccess::isChecked(Yii::app()->controller->module->id, 'promos', Yii::app()->user->id, 'create_p')),
            ),
            'itemOptions' => array('class' => 'nav-parent'),
            'linkOptions' => array('class' => 'expand'),
            'visible' => RbacUserAccess::isChecked(Yii::app()->controller->module->id, 'customer', Yii::app()->user->id, 'read_p')
        ),
        array('label' => '<i class="fa fa-bar-chart-o"></i> <span>' . Yii::t('order', 'Statistic') . '</span><b class="arrow icon-angle-down"></b>', 'url' => '#',
            'items' => array(
                array('label' => '<i class="fa fa-caret-right"></i> ' . Yii::t('order', 'Income'), 'url' => array('/' . Yii::app()->controller->module->id . '/reports/view'), 'visible' => RbacUserAccess::isChecked(Yii::app()->controller->module->id, 'reports', Yii::app()->user->id, 'read_p')),
                array('label' => '<i class="fa fa-caret-right"></i> ' . Yii::t('order', 'Analytic'), 'url' => array('/' . Yii::app()->controller->module->id . '/reports/analytic'), 'visible' => RbacUserAccess::isChecked(Yii::app()->controller->module->id, 'reports', Yii::app()->user->id, 'read_p')),
            ),
            'itemOptions' => array('class' => 'nav-parent'),
            'linkOptions' => array('class' => 'expand'),
            'visible' => RbacUserAccess::isChecked(Yii::app()->controller->module->id, 'reports', Yii::app()->user->id, 'read_p')
        ),
        array('label' => '<i class="fa fa-wrench"></i> <span>' . Yii::t('global', 'Manage') . '</span><b class="arrow icon-angle-down"></b>', 'url' => '#',
            'items' => array(
                array('label' => '<i class="fa fa-caret-right"></i> Users', 'url' => array('/' . Yii::app()->controller->module->id . '/users/view'), 'visible' => RbacUserAccess::isChecked(Yii::app()->controller->module->id, 'users', Yii::app()->user->id, 'read_p')),
                array('label' => '<i class="fa fa-caret-right"></i> Parameter', 'url' => array('/' . Yii::app()->controller->module->id . '/params/view'), 'visible' => RbacUserAccess::isChecked(Yii::app()->controller->module->id, 'params', Yii::app()->user->id, 'read_p')),
            ),
            'itemOptions' => array('class' => 'nav-parent'),
            'linkOptions' => array('class' => 'expand'),
            'visible' => RbacUserAccess::isChecked(Yii::app()->controller->module->id, 'users', Yii::app()->user->id, 'read_p') || RbacUserAccess::isChecked(Yii::app()->controller->module->id, 'params', Yii::app()->user->id, 'read_p')
        ),
        array('label' => '<i class="fa fa-power-off"></i> <span>Logout</span>', 'url' => array('/' . Yii::app()->controller->module->id . '/default/logout'), 'visible' => !Yii::app()->user->isGuest),
    ),
    'htmlOptions' => array('class' => 'navigation'),
    'encodeLabel' => false,
    'activeCssClass' => 'active',
    'submenuHtmlOptions' => array('class' => 'children')
));
?>
