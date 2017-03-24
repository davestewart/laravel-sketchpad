<?php namespace davestewart\sketchpad\config;

use davestewart\sketchpad\objects\install\JSON;

/**
 * Settings class
 */
class SketchpadSettings extends JSON
{

	// -----------------------------------------------------------------------------------------------------------------
	// properties


	// -----------------------------------------------------------------------------------------------------------------
	// instantiation

		public function __construct()
		{
			$paths = app(Paths::class);
			parent::__construct($paths->storage('settings.json'));
		}


	// -----------------------------------------------------------------------------------------------------------------
	// methods

		public function save($data)
		{
			$this->data = $data;
			$this->create();
		}
}