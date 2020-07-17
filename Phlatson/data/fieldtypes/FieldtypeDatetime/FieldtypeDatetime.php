<?php

namespace Phlatson;

use DateTime;

class FieldtypeDatetime extends Fieldtype
{

	public function decode($value) 
	{
		return (new DateTime("@$value"))->format("F j, Y");
	}

}
