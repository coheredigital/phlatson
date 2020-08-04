<?php

namespace Phlatson;

class FieldtypeDatetime extends Fieldtype
{
	public function decode($value)
	{
		return (new \DateTime("@$value"))->format('F j, Y');
	}
}
