<?php


class Pages extends ObjectArray{

	protected $allowRootRequest = true; // allows a "root" or null request to check for a data file in the "root"
	protected $root = "pages/";
	protected $singularName = "Page";

}
