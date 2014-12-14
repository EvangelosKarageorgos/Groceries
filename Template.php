<?php 
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