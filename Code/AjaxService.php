<?php
class AjaxServiceClass
{
	private $_callbacks;
	public function __construct()
	{
		$this->_callbacks = array();
	}
	
	public function addService($serviceCode, $callback)
	{
		if(array_key_exists($serviceCode, $this->_callbacks))
			return false;
		$this->_callbacks[$serviceCode] = $callback;
		return true;
	}
	
	public function serve()
	{
		$serviceCode = $_POST["service"];
		$requestData = json_decode($_POST["data"], true);
		if(array_key_exists($serviceCode, $this->_callbacks))
		{
			try{
		        	$responseData = $this->_callbacks[$serviceCode]($requestData);
			} catch (Exception $e){
				echo '{"status":"error", "data":"'.$this->escapeJsonString($e->getMessage()).'", "error":"'.$this->escapeJsonString($e->getTraceAsString()).'"}';
				exit();
			}
			if(is_string($responseData))
				$responseData='"'.$this->escapeJsonString($responseData).'"';
			else if(is_array($responseData))
					$responseData = json_encode($responseData);
			echo '{"status":"ok", "data":'.$responseData.', "error":""}';
			exit();
		} else
		{
			echo '{"status":"error", "data":"Service not found", "error":""}';
			exit();
		}
	}
	

	private function escapeJsonString($value) {
		$escapers =     array("\\",     "/",   "\"",  "\n",  "\r",  "\t", "\x08", "\x0c");
		$replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t",  "\\f",  "\\b");
		$result = str_replace($escapers, $replacements, $value);
		return $result;
	}
}

$_globalAjaxService = new AjaxServiceClass();

class AjaxService
{
	public static function addService($serviceCode, $callback)
	{
		global $_globalAjaxService;
		$_globalAjaxService->addService($serviceCode, $callback);
	}
	
	public static function serve()
	{
		global $_globalAjaxService;
		$_globalAjaxService->serve();
	}	
}
?>