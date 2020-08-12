<?php

namespace Phlatson;

class Template extends DataObject
{
	protected DataObject $owner;

	public function hasField($name)
	{
		$fields = $this->data('fields');

		return isset($fields[$name]);
	}

	public function getField($name): ?Field
	{
		$fields = $this->data('fields');

		return $fields[$name];
	}

	public function setOwner(DataObject $owner): void
	{
		$this->owner = $owner;
	}

	public function view(?array $data = []): View
	{
		// get view file of the same name
		$view = $this->app->getView($this->name());
		if (isset($data)) {
			$view->data($data);
		}

		return $view;
	}

	public function template(): Template
	{
		$template = $this->app->getTemplate('template');

		return $template;
	}
}
