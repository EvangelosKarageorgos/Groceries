<?php
class Request
{
	private $_get;
	private $_post;
	private $_pagePath;
	private $_pageUrl;
	private $_pageCleanUrl;
	private $_basePath;
	private $_documentName;
	private $_isInitialized = false;
	public function initialize()
	{
		if(!$this->_isInitialized){
			$this->_post = WebTools::clean($_POST);
			$this->_get = WebTools::clean($_GET);
			$this->_pagePath = WebTools::getPagePath();
			$this->_pageUrl = WebTools::getPageUrl();
			$this->_pageCleanUrl = WebTools::getPageUrl(true);
			$this->_documentName = str_replace($this->_pagePath, "", $this->_pageCleanUrl);
		}
	}
	
	public function setBasePath($path){
		$this->_basePath = WebTools::GetPageDomain().$path;
	}

	public function getBasePath(){
		return $this->_basePath;
	}
	
	public function getGetParam($key, $defaultValue){
		if(!isset($this->_get[$key]))
			return $defaultValue;
		else
			return $this->_get[$key];
	}

	public function getPostParam($key, $defaultValue){
		if(!isset($this->_post[$key]))
			return $defaultValue;
		else
			return $this->_post[$key];
	}
	
	public function getPageUrl(){
		return $this->_pageUrl;
	}
	
	public function getPageCleanUrl(){
		return $this->_pageCleanUrl;
	}	

	public function getPagePath(){
		return $this->_pagepath;
	}
	
	public function getDocumentName(){
		return $this->_documentName;
	}
}