<?php
/*
 * 
 * This code and all components (c) Copyright 2006 - 2016, Wowza Media Systems, LLC. All rights reserved.
 * This code is licensed pursuant to the Wowza Public License version 1.0, available at www.wowza.com/legal.
 * 
 */

namespace com\wowza;
class StreamFile extends Wowza{
	protected $restURI = "";
	protected $name = "";

	// not included in json generated as indicated by _[varname]
	protected $_applicationName = "live";
	protected $_mediaCasterType = "rtp";
	protected $_applicationInstance = "_definst_";
	protected $_skip = array();
	protected $_additional = array();


	public function __construct($appName=null, $streamFileName=null,
			$serverInstance = "_defaultServer_",
			$vhostInstance = "_defaultVHost_"){
		$this->restURI = $this->getHost()."/servers/".$this->getServerInstance()."/vhosts/".$this->getVHostInstance()."/streamfiles";

		if(!is_null($appName))
			$this->_applicationName = $appName;

		if(!is_null($streamFileName))
			$this->name = $streamFileName;
	}

	public function get(){
		$this->_skip["name"] = true;
		$this->restURI .= "/".$this->name;

		return $this->sendRequest($this->preparePropertiesForRequest(),array(), self::VERB_GET);
	}

	public function getAll(){
		$this->_skip["name"] = true;
		return $this->sendRequest($this->preparePropertiesForRequest(),array(), self::VERB_GET);
	}

	public function create($urlProps, $mediaCasterType="rtp", $applicationInstance = "_definst_"){
		$sf = new entities\application\StreamFiles();
		$sf->id = "connectAppName=".$this->_applicationName."&appInstance={$applicationInstance}&mediaCasterType={$mediaCasterType}";
		$sf->href = $this->restURI."/streamfiles/".$sf->id;

		$entities = $this->getEntites(array($sf), null);
		$this->restURI = $this->restURI."/".$this->name;
		$response = $this->sendRequest($this->preparePropertiesForRequest(),$entities);
		if($response->success){
			$items = $this->getAdvancedSettings($urlProps);
			return $this->addURL($items);
		}
		return $response;
	}

	private function addURL($advancedSettings){
		$this->_skip["name"]=1;
		$this->_additional["version"]="1430601267443";
		$this->restURI = $this->restURI."/adv";
		if(is_array($advancedSettings)){
			$this->_additional["advancedSettings"]=$advancedSettings;
		}
		else
			$this->_additional["advancedSettings"]=array($advancedSettings);

		$entities = $this->getEntites(func_get_args(), null);
		return $this->sendRequest($this->preparePropertiesForRequest(),array(), self::VERB_PUT);
	}

	private function getAdvancedSettings($urlProps){
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

	public function update($urlProps){
		$this->restURI = $this->restURI."/".$this->name;
		$items = $this->getAdvancedSettings($urlProps);
		return $this->addURL($items);
	}

	public function remove(){
		$this->_skip["name"]=1;
		$this->restURI = $this->restURI."/".$this->name;
		return $this->sendRequest($this->preparePropertiesForRequest(),array(), self::VERB_DELETE);
	}

	public function connect($subFolder=""){
		$this->_skip["name"]=1;
// 		$this->_additional["connectAppName"]=$this->_applicationName;
// 		$this->_additional["appInstance"]=$this->_applicationInstance;
// 		$this->_additional["mediaCasterType"]=$this->_mediaCasterType;
		$streamFilePath = (!empty($subFolder))?urlencode($subFolder."/".$this->name):$this->name;
		$this->restURI = $this->restURI."/".$streamFilePath."/actions/connect";
		return $this->sendRequest($this->preparePropertiesForRequest(),array(), self::VERB_PUT,"connectAppName=".$this->_applicationName."&appInstance=".$this->_applicationInstance."&mediaCasterType=".$this->_mediaCasterType);
	}

	public function disconnect(){
		/*
		 * curl -X PUT --header 'Accept:application/json; charset=utf-8' --header 'Content-type:application/json; charset=utf-8'
		 * "http://localhost:8087/v2/servers/_defaultServer_/vhosts/_defaultVHost_/applications/[YOUR-APP-NAME]/instances/_definst_/incomingstreams/[STREAM-FILE-NAME]/actions/disconnectStream"
		 *
		 *
		 * "http:\/\/127.0.0.1:8087\/v2\/servers\/_defaultServer_\/vhosts\/_defaultVHost_\/applications\/live\/instances\/_definst_\/incomingstreams\/bolton_mass\/actions\/disconnectStream"
		 */
		$this->_skip["name"]=1;
// 		$this->_additional["connectAppName"]=$this->_applicationName;
// 		$this->_additional["appInstance"]=$this->_applicationInstance;
// 		$this->_additional["mediaCasterType"]=$this->_mediaCasterType;

		$this->restURI = $this->getHost()."/servers/".$this->getServerInstance()."/vhosts/".$this->getVHostInstance()."/applications/".$this->_applicationName."/instances/";
		$this->restURI .= $this->_applicationInstance."/incomingstreams/".$this->name.".stream/actions/disconnectStream";
		return $this->sendRequest($this->preparePropertiesForRequest(),array(), self::VERB_PUT);
	}  
        
        //Setters and Getters
        public function setApplicationName($applicationName){
                $this->_applicationName = $applicationName;
                
                return $this;
        }
        public function getApplicationName(){
                return $this->_applicationName;
        }
        
        public function setMediaCasterType($mediaCasterType){
                $this->_mediaCasterType = $mediaCasterType;
                
                return $this;
        }
        public function getMediaCasterType(){
                return $this->_mediaCasterType;
        }
        
        public function setApplicationInstance($applicationInstance){
                $this->_applicationInstance = $applicationInstance;
                
                return $this;
        }
        public function getApplicationInstance(){
                return $this->_applicationInstance;
        }
        
        public function setSkip(array $skip){
                $this->_skip = $skip;
                
                return $this;
        }
        public function getSkip(){
                return $this->_skip;
        }
        
        public function setAdditional(array $additional){
                $this->_additional = $additional;
                
                return $this;
        }
        public function getAdditional(){
                return $this->_additional;
        }
}
