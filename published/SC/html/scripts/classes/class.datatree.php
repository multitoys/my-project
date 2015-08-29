<?php

class DataTree{
	var $root = array('id'=>null,'children' => array(),'data'=>null);
	private $pNodes = array();
	private $maxNodeId = null;

	function __destruct(){
		unset($this->root);
		unset($this->pNodes);
		unset($this->callbackHandler);
	}

	function setData($data,$nodeId = null,$parentId = null){

		$node = &$this->getNode($nodeId,$parentId);
		$node['data'] = $data;
	}

	function getData($nodeId){
		$node = &$this->getNode($nodeId);
		return $node['data'];
	}
	
	function sortNodes($callback,$nodeId = null)
	{
		if(isset($this->pNodes[$nodeId])){
			uasort($this->pNodes[$nodeId]['children'],$callback);
			foreach($this->pNodes[$nodeId]['children'] as $childNode){
				$this->sortNodes($callback,$childNode['id']);
			}
		}
	}

	function plainData($level = 0,$exportEmpty = false,&$result = null,$node = null){
		if(!is_array($result)){
			$result = array();
		}
		if(!is_array($node)){
			$node = $this->root;
		}elseif($exportEmpty||$node['data']){
			$result[] = array('id'=>$node['id'],'level'=>$level,'data'=>$node['data']);
		}
		foreach($node['children'] as $childNode){
				
			$this->plainData($level+1,$exportEmpty,$result,$childNode);
		}

		return $result;
	}

	function &getNode($id = null,$parentId = null){
		if(is_null($id)){
			return $this->root;
		}
		if(isset($this->pNodes[$id])){
			return $this->pNodes[$id];
		}else{
			$node = &$this->addNode($id,$parentId);
			return $node;
		}
	}

	private function &addNode($id,$parentId = null){
		$node = array('id'=>$id,'children'=>array(),'data'=>null);
		$parentNode = &$this->getNode($parentId);
		$parentNode['children'][] = &$node;
		$this->pNodes[$id] = &$node;
		if(!$this->maxNodeId||$id>$this->maxNodeId){
			$this->maxNodeId = $id;
		}
		return $node;
	}
	function getMaxNodeId(){
		return $this->maxNodeId;
	}
}

?>