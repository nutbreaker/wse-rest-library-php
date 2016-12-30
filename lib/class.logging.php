<?php
/*
 * 
 * This code and all components (c) Copyright 2006 - 2016, Wowza Media Systems, LLC. All rights reserved.
 * This code is licensed pursuant to the Wowza Public License version 1.0, available at www.wowza.com/legal.
 * 
 */

namespace com\wowza;
class Logging extends Wowza{
	private $restURI = "";

	private $_skip = array();
	private $_additional = array();


	public function __construct(){
		$this->restURI = $this->getHost()."/servers/".$this->getServerInstance()."/logfiles";
	}

	public function getNewestFirst(){
		$this->restURI = $this->restURI."?order=newestFirst";
		return $this->sendRequest($this->preparePropertiesForRequest(),array(), self::VERB_GET);
	}

	public function getLineCount($num){
		$this->restURI = $this->restURI."/wowzastreamingengine_access.log?lineCount={$num}";
		return $this->sendRequest($this->preparePropertiesForRequest(),array(), self::VERB_GET);
	}

	public function search($str){
		$this->restURI = $this->restURI."/wowzastreamingengine_access.log?search=".$str;
		return $this->sendRequest($this->preparePropertiesForRequest($this),array(), self::VERB_GET);
	}
}
