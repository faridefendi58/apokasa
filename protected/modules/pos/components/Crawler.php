<?php
/**
 * Crawler class to crawl all controller and action.
 */
class Crawler
{
	private static $_rawData = array();
	private static $_declaredClasses;
	private static $_alreadyIncluded;
	private static $_moduleName;
	private static $_controllerName;
	private static $_alias;
	//for widget
	private static $_widgetName;
	private static $_property;
	
	public static function homeList()
	{
		// reset the value of _rawData
		self::$_rawData = array();
		// gets the full list of declared classes
		self::$_declaredClasses = get_declared_classes();
		// reset the value of _alreadyIncluded
		self::$_alreadyIncluded = array();
		// extracts the controller data of the controllers that are part of the core web application
		self::extractControllers('application.controllers', false, 'homeList');
		// extracts the controller and module data of the controllers that belong to a module
		self::extractControllers('application.modules.*.controllers', true, 'homeList');
		return self::$_rawData;
	}
	
	private static function extractControllers($where, $module = false, $mode = 'dataProvider',$exception=array())
	{
		if(is_array(glob(Yii::getPathOfAlias($where) . "/*Controller.php"))){
		foreach (glob(Yii::getPathOfAlias($where) . "/*Controller.php") as $controller){
			if ($module) {
				if (DIRECTORY_SEPARATOR === '/') // fix for windows machines 
					self::$_moduleName = preg_replace('/^.*\/modules\/(.*)\/controllers.*$/', '$1', $controller);
				else
					self::$_moduleName = preg_replace('/^.*\\\modules\\\(.*)\\\controllers.*$/', '$1', $controller);
			} else
				self::$_moduleName = 'Basic';
			$_controllerName = basename($controller, "Controller.php"); // TODO when stop supporting php 5.2 use lcfirst
			$_controllerName{0} = $_controllerName{0};
			self::$_controllerName = $_controllerName;
			 
			$controller_class = self::$_controllerName.'Controller';
			
			// extract the value of permission controller inside the controller
			if (!in_array($controller_class, self::$_alreadyIncluded)) {
				// use reflectionClass if a controller with the same class name was not previously included
				// add the controller class to the alreadyIncluded array
				self::$_alreadyIncluded[] = $controller_class;
				if (!in_array($controller_class, self::$_declaredClasses))
					include($controller);
				
				$class = new ReflectionClass($controller_class);
				if ($class->hasProperty('_permissionControl'))
					$permissionControl = $class->getStaticPropertyValue('_permissionControl');
				else
					$permissionControl = NULL;
				if($class->hasProperty('_alias'))
					$_alias = $class->getStaticPropertyValue('_alias');
				else
					$_alias = self::$_controllerName;
				self::$_alias = $_alias;
			} else {
				// parse the file if a controller with the same class name was previously included
				// get the controller file content
				$controller_file = file_get_contents($controller, false, NULL, 0);
				// check if there is permissionControl inside it
				if (strpos($controller_file, 'permissionControl') !== false) {
					// get portion of the file containing permissionControl
					$controller_file = substr($controller_file, strpos($controller_file, 'permissionControl'));
					$controller_file = substr($controller_file, 0, strpos($controller_file, ';'));
					$permissionControl = eval('return $'.$controller_file.';');
				} else
					$permissionControl = NULL;
			}
			
			// check the value of permissionControl and skip this controller if necessary
			if ($permissionControl === false || (count($permissionControl) === 1 && isset($permissionControl['label']) && $mode === 'dataProvider'))
					continue;
			
			if ($mode === 'dataProvider') {	
				if(!in_array(strtolower(self::$_controllerName),$exception)){
					self::$_rawData[] = array(
							'module' => self::$_moduleName,
							'controller' => isset($permissionControl['label']) ? $permissionControl['label'] : self::$_controllerName,
							'alias' => self::$_alias,
						);
				}
			} else if ($mode === 'homeList'){
				self::$_rawData['/'.(self::$_moduleName === 'Basic' ? NULL : self::$_moduleName.'/') . self::$_controllerName] = (self::$_moduleName === 'Basic' ? NULL : self::$_moduleName.': ').(isset($permissionControl['label']) ? $permissionControl['label'] : self::$_controllerName);
			} else if ($mode === 'controllerlist'){
				self::$_rawData[] = array(
					'name'=>isset($permissionControl['label']) ? $permissionControl['label'] : self::$_controllerName,
					'class'=>isset($permissionControl['label']) ? $permissionControl['label'] : ucfirst(self::$_controllerName).'Controller',
				);
			}
		}
		}
	}
	
	public function getAllController()
	{
		// reset the value of _rawData
		self::$_rawData = array();
		// gets the full list of declared classes
		self::$_declaredClasses = get_declared_classes();
		// reset the value of _alreadyIncluded
		self::$_alreadyIncluded = array();
		// extracts the controller data of the controllers that are part of the core web application
		self::extractControllers('application.controllers', false, 'controllerlist');
		// extracts the controller and module data of the controllers that belong to a module
		self::extractControllers('application.modules.*.controllers', true, 'controllerlist');
		return self::$_rawData;
	}

	public function getAllControllerName()
	{
		$controllers=self::getAllController();
		$items=array();
		foreach($controllers as $index=>$controller){
			$items[]=strtolower($controller['name']);
		}
		return $items;
	}
	
	//nangkap smua action yg ada di setiap controller
	public function getInternalActions($controller,$exception=array())
    {
        $methods = get_class_methods($controller);
        $inActions = array();
        foreach($methods as $method)
        {
            if( substr($method, 0, strlen('action')) == 'action' && ctype_upper($method[strlen('action')]) ){
                $pattern = '/action/';
				$matches=preg_split($pattern, $method);
				if(!in_array($matches[1],$exception))
					$inActions[] = $matches[1];
			}
        }
		return $inActions;
    }
	
	//module + controller + action
	public function getDataProvider($exception=array(),$just_in_modules=false)
	{
		// reset the value of _rawData
		self::$_rawData = array();
		// gets the full list of declared classes
		self::$_declaredClasses = get_declared_classes();
		// reset the value of _alreadyIncluded
		self::$_alreadyIncluded = array();
		// extracts the controller data of the controllers that are part of the core web application
		if(!$just_in_modules)
			self::extractControllers('application.controllers', false, 'dataProvider', $exception);
		// extracts the controller and module data of the controllers that belong to a module
		self::extractControllers('application.modules.*.controllers', true, 'dataProvider', $exception);
		return self::$_rawData;
	}

	public function getDataProvider2($exception=array())
	{
		// reset the value of _rawData
		self::$_rawData = array();
		// gets the full list of declared classes
		self::$_declaredClasses = get_declared_classes();
		// reset the value of _alreadyIncluded
		self::$_alreadyIncluded = array();
		// extracts the controller data of the controllers that are part of the core web application
		self::extractControllers('application.controllers', false, 'dataProvider', $exception);
		return self::$_rawData;
	}
	
	public static function widgetList()
	{
		// reset the value of _rawData
		self::$_rawData = array();
		// gets the full list of declared classes
		self::$_declaredClasses = get_declared_classes();
		// reset the value of _alreadyIncluded
		self::$_alreadyIncluded = array();
		// extracts the widget
		self::extractWidgets('application.widgets');
		return self::$_rawData;
	}
	
	private static function extractWidgets($where)
	{
		foreach (glob(Yii::getPathOfAlias($where) . "/*Widget.php") as $widget){
			$_widgetName = basename($widget, "Widget.php"); // TODO when stop supporting php 5.2 use lcfirst
			$_widgetName{0} = $_widgetName{0};
			self::$_widgetName = $_widgetName;
			 
			$widget_class = self::$_widgetName.'Widget';
			
			if (!in_array($widget_class, self::$_alreadyIncluded)) {
				self::$_alreadyIncluded[] = $widget_class;
				if (!in_array($widget_class, self::$_declaredClasses))
					include($widget);
				
			} else {
				// parse the file if a widget with the same class name was previously included
				$widget_file = file_get_contents($widget, false, NULL, 0);
			}
			self::$_rawData[] = array(
					'name'=>self::$_widgetName,
					'class'=>self::$_widgetName.'Widget',
				);
		}
	}
}
