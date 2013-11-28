
<?php

class FieldtypeTextarea extends Fieldtype{



	

	public function format($value, $format = false){
//		$value = htmlspecialchars_decode($value);
		return $value;
	}

	public function saveFormat($value, $name = null){

		$dom =  new DomDocument;
        $root = $dom->createElement("$name");
        $node = $dom->createCDATASection($value);
        $root->appendChild($node);
        $dom->appendChild($root);

		return $dom->documentElement;

	}


	protected function renderInput(){
		$attributes = $this->getAttributes();
		$output = "<textarea {$attributes} name='{$this->name}' id='{$id}' cols='30' rows='10'>{$this->value}</textarea>";
		return $output;
	}

}