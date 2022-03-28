<?php

namespace Slim;

class Controller
{
    protected $app = NULL;
    
    public function __construct()
    {
        $this->app = \Slim\CSlim::getInstance();
    }
    
    public function app()
    {
		return $this->app;
	}
    
    public function render($template, $vars = array(), $layoutFile = null)
    {
        $content = $this->renderPartial($template, $vars, true);
        
        if(!array_key_exists('title', $vars)) $vars['title'] = $this->app->config('defaultTitle');
        if($layoutFile === null) $layoutFile = $this->app->config('defaultLayout');
        
        $this->app->render($layoutFile, array(
            'title' => $vars['title'],
            'content' => $content,
            'app' => $this->app,
        ));
    }
    
    public function renderPartial($template, $vars = array(), $return = false)
    {
        if($return) ob_start();
        $this->app->render($template, array_merge(array('app' => $this->app, 'controller' => $this), $vars));
        if($return) return ob_get_clean();
    }
    
    public function redirect($path)
    {
        $this->app->redirect($this->app->request->getRootUri().$path);
    }
    
    public function createUrl($path)
    {
		$path=str_replace('/index.php','', $path);
		$rooturi=str_replace('/index.php','', $this->app->request->getRootUri());
        return $rooturi."/index.php".$path;
    }

    public function rootUri()
    {
        return $this->app->request->getRootUri();
	}

	public function path()
	{
		return dirname($this->app->request->getRootUri()).'/';
	}
    
    public function flash($key, $text)
    {
		$this->app->flashNow($key, $text);
	}
	
	public function xls($filename, $params = null, $confirmText = null)
	{
		if(isset($_GET['xls']))
		{
			$this->app->response->headers->set('Content-Type', 'application/vnd.ms-excel');
			$this->app->response->headers->set('Expires', '0');
			$this->app->response->headers->set('Cache-Control', 'post-check=0, pre-check=0');
			$this->app->response->headers->set('Content-Disposition', 'attachment; filename='.$filename.'.xls');
		}
		else
		{
			$action = $_SERVER['PHP_SELF']."?xls=1";
			
			if(is_array($params))
				$action .= \HTML::createUrlString($params);
			
			$attributes = array();
			
			if($confirmText !== null)
				$attributes['onclick'] = 'return confirm("'.$confirmText.'");';
			
			$out[] = \HTML::tag('p');
			$out[] = \HTML::link('XLS-Datei erstellen', $action, $attributes);
			$out[] = \HTML::closeTag('p');
			
			return implode("\r\n", $out)."\r\n";
		}
	}
}
