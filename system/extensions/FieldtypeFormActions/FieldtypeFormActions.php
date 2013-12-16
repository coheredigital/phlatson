<?php 

class FieldtypeFormActions extends Fieldtype{

	public $dataObject;

	protected function addStyles()
	{
		var_dump($this->url);
		api('config')->styles->add($this->url."{$this->className}.css");
	}

	public function render()
	{
		$output  = "<div class='row clearfix'>";
			$output  = "<div class='col col-12'>";
				$output  .= "<div class='field-item {$this->className}'>";
					$output .= "<div class='field-content  clearfix'>";		
						$output .= "<button type='submit' class='button {$this->className}-button button-save pull-right'><i class='icon icon-floppy-o'></i></button>";	
						$output .= "<button type='submit' class='button {$this->className}-button button-delete pull-right'><i class='icon icon-trash-o'></i></button>";	
						$output .= "<button type='submit' class='button {$this->className}-button button-soft pull-right'><i class='icon icon-copy'></i></button>";	
						$output .= "<a href='{$this->dataObject->url}' target='_external' class='button button-soft {$this->className}-button pull-right'><i class='icon icon-external-link'></i></a>";	
					$output .= "</div>";		
				$output .= "</div>";
			$output .= "</div>";
		$output .= "</div>";
		return $output;

	}



}