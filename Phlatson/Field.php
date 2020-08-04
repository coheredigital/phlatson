<?php

namespace Phlatson;

class Field extends DataObject
{
	public function type(): Fieldtype
	{
		$name = $this->data->get('fieldtype');
		$fieldtype = $this->app->getFieldtype($name);
		// TODO: reevaluate return the default base Fieldtype
		if (!$fieldtype) {
			$fieldtype = new Fieldtype();
		}

		return $fieldtype;
	}

	public function template(): Template
	{
		$template = $this->finder->get('Template', 'template');

		return $template;
	}
}
