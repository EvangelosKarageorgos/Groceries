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
	
}


?>