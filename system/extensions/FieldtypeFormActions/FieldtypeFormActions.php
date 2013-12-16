<?php 

class FieldtypeFormActions extends Fieldtype{

	public function render()
	{
		$output  = "<div class='row clearfix'>";
			$output  = "<div class='col col-12'>";
				$output  .= "<div class='field-item'>";
					$output .= "<div class='field-content  clearfix'>";		
						$output .= "<button type='submit' class='button {$this->className}-button button-save pull-right'><i class='icon icon-floppy-o'></i></button>";	
						$output .= "<button type='submit' class='button {$this->className}-button button-delete pull-right'><i class='icon icon-trash-o'></i></button>";	
						$output .= "<button type='submit' class='button {$this->className}-button pull-right'><i class='icon icon-copy'></i></button>";	
					$output .= "</div>";		
				$output .= "</div>";
			$output .= "</div>";
		$output .= "</div>";
		return $output;

	}



}