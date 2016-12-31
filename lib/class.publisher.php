<?php
/*
 * 
 * This code and all components (c) Copyright 2006 - 2016, Wowza Media Systems, LLC. All rights reserved.
 * This code is licensed pursuant to the Wowza Public License version 1.0, available at www.wowza.com/legal.
 * 
 */

namespace com\wowza;
class Publisher extends Wowza{
	protected $restURI = "";
	protected $name = "";
	protected $password = "";

        protected $_skip = array();
	protected $_additional = array();


	public function __construct($publisherName=null){
		$this->name = $publisherName;
		$this->restURI = $this->getHost()."/servers/".$this->getServerInstance()."/publishers";
	}

	public function create($password){
		$this->restURI = $this->restURI;
		$this->password = $password;
		$response = $this->sendRequest($this->preparePropertiesForRequest($this),array());
		return $response;
	}

	public function getAll(){
		$this->_skip["name"] = true;
		$this->_skip["password"] = true;
		return $this->sendRequest($this->preparePropertiesForRequest(),array(), self::VERB_GET);
	}

	public function remove(){
		$this->restURI = $this->restURI."/".$this->name;
		return $this->sendRequest($this->preparePropertiesForRequest($this),array(), self::VERB_DELETE);
	}

	protected function getAdvancedSettings($urlProps){
		if(is_array($urlProps)){
			$items = array();
			foreach($urlProps as $k=>$v){
				$item = new entities\application\helpers\AdvancedSettingItem();
				$item->name = $k;
				$item->value = $v;
				$items[] = $item;
			}
			return $items;
		}
		else{
			$item = new entities\application\helpers\AdvancedSettingItem();
			$item->value = $urlProps;
			return $item;
		}
	}
}
