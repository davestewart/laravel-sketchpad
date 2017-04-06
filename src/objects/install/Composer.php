<?php namespace davestewart\sketchpad\objects\install;

/**
 * Composer class
 */
class Composer extends JSON
{

	// -----------------------------------------------------------------------------------------------------------------
	// PROPERTIES


	// -----------------------------------------------------------------------------------------------------------------
	// INSTANTIATION

	public function __construct()
	{
		parent::__construct('composer.json');
	}


	// -----------------------------------------------------------------------------------------------------------------
	// METHODS

	public function generateAutoload()
	{
		exec('cd ' . base_path() . '; composer dump-autoload 2>&1', $output);
		return trim(implode("\n", $output));
	}

	public function hasPSR4Class($key)
	{
		$path = base_path('vendor/composer/autoload_psr4.php');
		$data = require($path);
		return array_key_exists($key, $data);
	}
}