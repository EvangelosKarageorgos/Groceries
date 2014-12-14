<?php
class WebTools
{
	public static function clean($elem)
	{
		if(!is_array($elem))
			$elem = htmlentities($elem,ENT_QUOTES,"UTF-8");
		else
			foreach ($elem as $key => $value)
				$elem[$key] = self::clean($value);
		return $elem;
	}
	
	public static function redirect($url, $statusCode = 303)
	{
	   header('Location: ' . $url, true, $statusCode);
	   die();
	}
	
	public static function getPageUrl($clean=false)
	{
		$pageURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
		if ($_SERVER["SERVER_PORT"] != "80")
		{
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} 
		else 
		{
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $clean==false ? $pageURL : explode('?', $pageURL)[0];
	}
	
	public static function getClientIP() {
		$ip = '127.0.0.1';
		if (isset($_SERVER)) {

			if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
				$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
			else if (isset($_SERVER["HTTP_CLIENT_IP"]))
				$ip = $_SERVER["HTTP_CLIENT_IP"];
			else
				$ip= $_SERVER["REMOTE_ADDR"];
		}

		else if (getenv('HTTP_X_FORWARDED_FOR'))
			$ip = getenv('HTTP_X_FORWARDED_FOR');
		else if (getenv('HTTP_CLIENT_IP'))
			$ip = getenv('HTTP_CLIENT_IP');
		else
			$ip = getenv('REMOTE_ADDR');
		return filter_var($ip, FILTER_VALIDATE_IP);
	}

	public static function getPagePath()
	{
		$pageUrl = self::getPageUrl(false);
		return substr($pageUrl, 0, strripos($pageUrl, "/")+1);
	}

    public static function getPageDomain()
    {
        $url = self::getPageUrl();
        $l = strlen($url);
        $sl = strrpos($url, "/");
        $sl = $sl === false ? l : $sl;
        $qu = strrpos($url, "?");
        $qu = $qu === false ? l : $qu;
        $index = $sl<$qu?$sl:$qu;
        return substr($url, 0, $index);
    }

    public static function simpleXML2Array($xml){
        $array = (array)$xml;

        //recursive Parser
        foreach ($array as $key => $value){
            if(strpos(get_class($value),"SimpleXML")!==false){
                $array[$key] = self::simpleXML2Array($value);
            }
        }

        return $array;
    }
}


?>