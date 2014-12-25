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

	public static function searchSeperation(&$table, &$element, &$target, &$path=null, $sep=0, $searchSet=null){
		$first = true;
		if(is_array($path))
			$path[] = $element;
		if($element==$target)
			return $sep;
		if($searchSet==null){
			$searchSet = array();
			foreach($table as $k => $v)
				if($k!=$element)
					$searchSet[] = $k;		
		}
		$searchSets = array();
		foreach($searchSet as $key){
			$tvalue = $table[$key];
			$f = false;
			if($key != $element){
				foreach($table[$element] as $pc){
					if(in_array($pc, $tvalue)){
						$f=true;
						if($key==$target){
							if(is_array($path))
								$path[] = $key;
							return $sep+1;
						}
						break;
					}
				}
				if($f){
					$newSearchSet = $searchSet;
					if (($k = array_search($key, $newSearchSet)) !== false) {
						unset($newSearchSet[$k]);
					}
					$searchSets[$key] = $newSearchSet;
				}
			}
		}
		foreach($searchSets as $k => $v){
			$s = self::searchSeperation($table, $k, $target, $path, $sep+1, $v);
			if($s>0)
				return $s;
		}
		if(is_array($path)){
			if (($k = array_search($element, $path)) !== false) {
				unset($path[$k]);
			}
		}
		return -1;
	}

	
}


?>