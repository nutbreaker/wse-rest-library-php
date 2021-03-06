<?php
/*
 * 
 * This code and all components (c) Copyright 2006 - 2016, Wowza Media Systems, LLC. All rights reserved.
 * This code is licensed pursuant to the Wowza Public License version 1.0, available at www.wowza.com/legal.
 * 
 */

namespace com\wowza;
class Server extends Wowza{
	protected $restURI = "";

	// not included
	protected $_skip = array();
	protected $_additional = array();

	public function __construct($host = "http://localhost:8087/v2",
			$serverInstance = "_defaultServer_"
	){
		$this->restURI = "{$host}/servers/{$serverInstance}";
	}

	public function getUsers(){
		$this->restURI .= "/users";
		$entities = $this->getEntites(array(), $this->restURI);
		return $this->sendRequest($this->preparePropertiesForRequest(),array(), self::VERB_GET);
	}

	public function createUser($name, $password, $groups=array()){
		$this->restURI .= "/users/{$name}";
		$this->_additional["name"] = $name;
		$this->_additional["password"] = $password;
		$this->_additional["groups"] = $groups;
		$entities = $this->getEntites(array(), $this->restURI);
		return $this->sendRequest($this->preparePropertiesForRequest(),array());
	}

	public function removeUser($name){
		$this->restURI .= "/users/{$name}";
		return $this->sendRequest($this->preparePropertiesForRequest(),array(), self::VERB_DELETE);
	}

	public function getRestURI(){
		return $this->restURI;
	}
}
