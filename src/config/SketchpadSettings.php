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
			$config = app(SketchpadConfig::class);
			parent::__construct($config->settings);
		}


	// -----------------------------------------------------------------------------------------------------------------
	// methods

		public function save($data)
		{
			$this->data = $data;
			$this->create();
		}
}