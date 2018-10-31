<?php
class Rbac
{
	/**
	 * ruleAccess dipake pada accessRules controller
	 * array('allow',
	 *			'actions'=>array('index','view','create','update','admin','delete'),
	 *			'expression'=>'Rbac::ruleAccess(\'priv_type\')==1',
	 *		),
	 */
	public function ruleAccess($priv)
	{
		$module=(empty(Yii::app()->controller->module->id))? 'basic':strtolower(Yii::app()->controller->module->id);
		$controller=strtolower(Yii::app()->controller->id);
		/*if(Yii::app()->user->hasState('user_access') && is_array(Yii::app()->user->user_access)){
			$user_access=Yii::app()->user->user_access;
			$akses=$user_access[$module][$controller][$priv];
		}else{
			$akses=false;
		}*/
		
		$user_access=RbacUserAccess::listAccess(Yii::app()->user->id);
		if(is_array($user_access)){
			//$user_access=Yii::app()->user->user_access;
			$akses=$user_access[$module][$controller][$priv];
		}else{
			$akses=false;
		}
		
		return (!Yii::app()->user->isGuest)? $akses : false;
	}
	
	public function isRoot()
	{
		$criteria=new CDbCriteria;
		$criteria->order='level ASC';
		$groups=RbacGroup::model()->findAll($criteria);
		foreach($groups as $group){}
		//yang level paling tinggi
		return (Yii::app()->user->level==$group->level)? true : false;
	}
	
	/**
	 * dipake untuk menu dan link
	 * array('label'=>'nama_menu', 'url'=>array('module/controller/action'), 'visible'=>Rbac::isAllowed('module/controller/action'));
	 */
	public function isAllowed($link_action,$priv_type='create_p')
	{
		$chars = explode("/", $link_action);
		$new_chars=array();
		for($i=0; $i<count($chars); $i++){
			if(!empty($chars[$i]))
				array_push($new_chars,strtolower($chars[$i]));
		}
		
		/*for($j=0; $j<count($new_chars); $j++){
			$in_module=RbacGroupAccess::model()->findByAttributes(array('module'=>$new_chars[$j]));
			$in_controller=RbacGroupAccess::model()->findByAttributes(array('controller'=>$new_chars[$j]));
			if(count($in_module)>0)
				$module=$new_chars[$j];
			elseif(count($in_controller)>0)
				$controller=$new_chars[$j];
		}*/
		
		$modules=array_keys(Yii::app()->modules);
		$controllers=Crawler::getAllControllerName();
		for($j=0; $j<count($new_chars); $j++){
			if(in_array($new_chars[$j],$controllers))
				$controller=$new_chars[$j];
			elseif(in_array($new_chars[$j],$modules))
				$module=$new_chars[$j];
		}

		if(empty($module))
			$module='basic';
		if(empty($controller)){
			$exception=Yii::app()->params['exceptionRbacAccess'];
			for($k=0; $k<count($new_chars); $k++){
				if(in_array($new_chars[$k],$exception))
					$ctrl=$new_chars[$k];
			}
			if(empty($ctrl))
				$controller='default';
			else
				$controller=$ctrl;
		}
		if(empty($action))
			$action='index';
		
		$route=array(
					'module'=>$module,
					'controller'=>$controller,
					'create_p'=>$model->create_p,
					'read_p'=>$model->read_p,
					'update_p'=>$model->update_p,
					'delete_p'=>$model->delete_p,
				);
		$exception=Yii::app()->params['exceptionRbacAccess'];
		
		// user and group validation
		/* if(is_array(Yii::app()->user->group_access) && is_array(Yii::app()->user->user_access)){
			if(in_array($controller,$exception)){
				$akses=true;
			}else{
				if(!Yii::app()->user->isGuest){
					if(in_array($route,Yii::app()->user->group_access)){
						if(in_array($route,Yii::app()->user->user_access))
							$akses=true;
						else
							$akses=false;
					}else{
						if(in_array($route,Yii::app()->user->user_access))
							$akses=true;
						else
							$akses=false;
					}
				}else{
					$akses=false;
				}
			}
		} */
		
		return RbacUserAccess::isChecked($module,$controller,Yii::app()->user->id,$priv_type);
	}
	
	public function itemsPriviledge()
	{
		$list=array(
				'create_p'=>'Create',
				'read_p'=>'Read',
				'update_p'=>'Update',
				'delete_p'=>'Delete',
			);
		return $list;
	}
}
?>
