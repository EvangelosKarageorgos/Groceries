<?php 


class ScriptResource
{
	private $_content = "";
	public function __construct($src){
		$this->_content = $src;
	}
	public function __toString()
	{
		return "<script type=\"text/javascript\" src=\"".$this->_content."\"></script>\n";
	}
}

class StyleResource
{
	private $_content = "";
	public function __construct($src){
		$this->_content = $src;
	}
	public function __toString()
	{
		return "<link href=\"".$this->_content."\" rel=\"stylesheet\" type=\"text/css\" />\n";
	}
}

function renderTemplate($template, $model)
{
	ob_start();
       try{
            include $template;
       }catch(Exception $ex){
               ob_get_clean();
               throw $ex;
       }
       $result = ob_get_clean();
    return $result;
}

function GetIncludingFile()
{
	$file = false;
	$backtrace =  debug_backtrace();
	$include_functions = array('include', 'include_once', 'require', 'require_once');
	for ($index = 0; $index < count($backtrace); $index++)
	{
		$function = $backtrace[$index]['function'];
		if (in_array($function, $include_functions))
		{
			$file = $backtrace[$index]['file'];
			break;
		}
	}
	return $file;
}

$__isMasterRendered = false;
$__headResources = array();
$__footResources = array();

function includeHeadResource(&$resource){
	global $__headResources;
	array_push($__headResources, $resource);
}

function includeFootResource(&$resource){
	global $__footResources;
	array_push($__footResources, $resource);
}

function renderMaster($header, $footer){
	global $__isMasterRendered;
	global $__headResources;
	global $__footResources;
	if(!$__isMasterRendered)
	{
		$__isMasterRendered = true;
		$origin = GetIncludingFile();
		$h = renderTemplate($header, array());
		$f = renderTemplate($footer, array());
		$c = renderTemplate($origin, array());
		
		$hr = "";
		foreach($__headResources as &$r){
			$hr .= $r;
		}

		$fr = "";
		foreach($__footResources as &$r){
			$fr .= $r;
		}
		
		
		echo renderTemplate(dirname(__FILE__)."/../Templates/MasterPage.php", array(
			"HeadResources" => $hr,
			"FootResources" => $fr,
			"Header" => $h,
			"Footer" => $f,
			"Content" => $c
			));
		die;
	}
}