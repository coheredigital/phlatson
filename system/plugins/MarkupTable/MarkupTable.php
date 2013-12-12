<?php 

// columns should be an array of name retrivable from the data objects passed to the rows
class MarkupTable extends Plugin {

	protected $rows;
	protected $columns;

	public function setColumns(array $array){
		$this->columns = $array;
	}

	public function addRow(array $array){
		$this->rows[] = $array;
	}


	public function render(){

		$output = "";

		// return false if columns or rows data missing, not much of a table without them
		if (!$this->columns || !$this->rows) return false;

		// create the table header 
		$columnsOutput = "";
		foreach ($this->columns as $key => $value) {
			// if $key is string use it for heading label
			$value = is_string($key) ? $key : $value;
			$columnsOutput .= "<th>{$value}</th>";
		}
		$thead = "<thead><tr>{$columnsOutput}<tr></thead>";
		
		$rowsOutput = "";
		foreach ($this->rows as $row) {
			$rowOutput = "";
			foreach ($this->columns as $column) {
				$value = $row["$column"];
				$rowOutput .= "<td>{$value}</td>";
			}
			$rowsOutput .= "<tr>{$rowOutput}</tr>";
		}
		$tbody = "<tbody><tr>{$rowsOutput}<tr></tbody>";

		// wrap $output in "table" markup
		$output = "<table>{$thead}{$tbody}</table>";

		return $output;

	}

}