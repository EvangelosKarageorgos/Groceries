<?php
class GroceriesTools
{
	public static function getDatePickerParam($paramName, $defaultDate){
		$date = Application::getRequest()->getGetParam($paramName, "");
		$date = strlen($date)==0?date("m/d/Y", $defaultDate) : $date;
		return $date;
	}
	public static function getSqlDate($date){
		list($month, $day, $year) = explode('/', $date);
		return $year.'-'.$month.'-'.$day;
		
	}
	public static function getIntegerParam($paramName, $defaultValue){
		$value = Application::getRequest()->getGetParam($paramName, "");
		$value = intval(strlen($value)==0?$defaultValue : $value);
		return $value;
	}
	
	public static function getAdminBasicQueryControls(){
		$dateFrom = GroceriesTools::getDatePickerParam("date-from", time() - 3600 * 24*7);
		$dateTo = GroceriesTools::getDatePickerParam("date-to", time());
		$resultsCount = GroceriesTools::getIntegerParam("results-count", 10);
		$model = array('dateFrom' => $dateFrom, 'dateTo' => $dateTo, 'resultsCount' => $resultsCount);
		$markup = renderTemplate(dirname(__FILE__)."/../Templates/Admin/basicQueryControls.php", $model);
		$model['dateFrom'] = self::getSqlDate($model['dateFrom']);
		$model['dateTo'] = self::getSqlDate($model['dateTo']). ' 23:59:59';
		return array('markup' => $markup, 'model' => $model);
	}

	public static function searchSeperation(&$table, $searchSet, &$element, &$target, $sep){
	$first = true;
	$nextSearch = 0;
	var_dump($searchSet);
	//var_dump($element);
	foreach($searchSet as $key){
		if($first){
			$nextSearch = $key;
			$first = false;
		}
		$tvalue = $table[$key];
		$f = false;
		var_dump($element);
		var_dump($tvalue);
		foreach($element as $pc){
			if(in_array($pc, $tvalue)){
				return $sep+1;
			}
		}
		$newSearchSet = $searchSet;
		if (($k = array_search($key, $newSearchSet)) !== false) {
			unset($newSearchSet[$k]);
		}
		var_dump($newSearchSet);
		$s = self::searchSeperation($table, $newSearchSet, $tvalue, $target, $sep+1);
		if($s>0)
			return $s;
		return -1;
	}
	
}

	
}


?>