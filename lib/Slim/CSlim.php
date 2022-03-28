<?php

namespace Slim;

class CSlim extends Slim
{
	public function __construct(array $userSettings = array())
    {
		$userSettings = array_merge(array(
			'CSlimAutoloader' => __NAMESPACE__.'\\CSlim::defaultCSlimAutoloader',
		), $userSettings);
		
		parent::__construct($userSettings);
		
		spl_autoload_register($this->config('CSlimAutoloader'));
	}
	
	public static function defaultCSlimAutoloader($className)
	{
		$path  = __FILE__;

		$path  = str_replace(DIRECTORY_SEPARATOR.basename(__FILE__), '', $path);
		$path  = str_replace(__NAMESPACE__, '', $path);

		$path  = str_replace(DIRECTORY_SEPARATOR.'lib', '', $path);

//		$path .= str_replace(DIRECTORY_SEPARATOR , '', dirname($_SERVER['SCRIPT_NAME'])).DIRECTORY_SEPARATOR;
		$path .= 'controllers'.DIRECTORY_SEPARATOR;
		
		$fileName = $path.$className.'.php';

		if(file_exists($fileName))
			require($fileName);
		else
		{
			$path  = $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR;
			$path .= 'base'.DIRECTORY_SEPARATOR;
			$path .= 'controllers'.DIRECTORY_SEPARATOR;
			$fileName = $path.$className.'.php';

			if(file_exists($fileName))
				require($fileName);
		}
	}
	
	private function addMiddleware($middleware)
	{
		if(is_bool($middleware))
			return $middleware;
		if(is_callable($middleware))
			return call_user_func($middleware) !== false;
		elseif(is_array($middleware))
		{
			foreach($middleware as $single) if($this->addMiddleware($single) === false)
				return false;
			
			return true;
		}
		else return false;
	}
	
	public function getController($class, $method = 'Index', $params = array())
	{
		$class = ucfirst($class).'Controller';
		$method = ucfirst($method);

		if(!class_exists($class))
			return false;

		$obj = new $class;
		
		if(!$obj instanceof \Slim\Controller || !method_exists($obj, $method))
			return false;
		
		if(method_exists($obj, 'middleware'))
		{
			$middleware = call_user_func_array(array($obj, 'middleware'), $params);
			
			if(is_array($middleware))
			{
				if(array_key_exists('*', $middleware) && $this->addMiddleware($middleware['*']) === false)
					return false;
				
				if(array_key_exists($method, $middleware) && $this->addMiddleware($middleware[$method]) === false)
					return false;
			}
		}
		
		return call_user_func_array(array($obj, $method), $params) !== false;	
	}
	
	public function run()
	{
		$this->map('/:class(/:method(/:params+))', array($this, 'getController'))->via('GET', 'POST');
		
		parent::run();
	}
}
