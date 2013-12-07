<?php 

class Helpers {


	public static function dump_nodelist(DOMNodeList $list){
		$dom = new DOMDocument();
		foreach($list as $n) $dom->appendChild($dom->importNode($n,true));
		var_dump($dom->saveXML());

	}
	public static function dump_node($node){
		$dom = new DOMDocument();
		$dom->appendChild($dom->importNode($node,true));
		var_dump($dom->saveXML());

	}

}