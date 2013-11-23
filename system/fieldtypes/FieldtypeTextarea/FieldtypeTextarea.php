
<?php

class FieldtypeTextarea extends Fieldtype{

	private $purifier;


	protected function setup(){
		// might use html purifier

		// require_once $this->api("config")->paths->system.'vendor/HTMLPurifier/HTMLPurifier.standalone.php';
		// $config = HTMLPurifier_Config::createDefault();
		// $config->set('Core.Encoding', 'UTF-8');
		// $config->set("AutoFormat.RemoveEmpty", true);
		// $config->set('HTML.Doctype', 'HTML 4.01 Transitional');
		// $this->purifier = new HTMLPurifier($config);
	}

	protected function addStyles(){
		api('config')->styles->add(api('config')->urls->fieldtypes."{$this->className}/redactor/redactor.css");
	}
	protected function addScripts(){
		api('config')->scripts->add(api('config')->urls->fieldtypes."{$this->className}/redactor/redactor.js");
		api('config')->scripts->add(api('config')->urls->fieldtypes."{$this->className}/{$this->className}.js");
	}
	

	public function format($value, $format = false){
		// $value = $this->purifier->purify($value);
		$value = htmlspecialchars_decode($value);
		return $value;
	}

	public function saveFormat($value){
		// $value = $this->purifier->purify($value);
		return $value;
	}


	public function render(){

		$attributes = $this->getAttributes();

		$output  = "<div class='col col-{$this->columns}'>";
			$output  = "<div class='field-item'>";
				$output .= "<div class='field-heading'>";
					$output .= "<label for=''>";
					$output .= "{$this->label}";
					$output .= "</label>";
				$output .= "</div>";
				$output .= "<div class='field-content'>";
					$output .= "<textarea {$attributes} name='{$this->name}' id='Input_{$this->name}' cols='30' rows='10'>{$this->value}</textarea>";
				$output .= "</div>";
			$output .= "</div>";
		$output .= "</div>";
		return $output;
	}

}