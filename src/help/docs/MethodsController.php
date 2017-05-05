<?php namespace davestewart\sketchpad\help\docs;

use davestewart\sketchpad\config\SketchpadConfig;
use davestewart\sketchpad\services\Sketchpad;
use Illuminate\View\FileViewFinder;
use Illuminate\View\View;
use Illuminate\Http\Request;

/**
 * Get started running code through controllers and methods
 *
 * @order 2
 */
class MethodsController
{

	public function index()
	{
		md('sketchpad::help/methods/index');
	}

	/**
	 * Run a method just by clicking on its label
	 *
	 * @group Execution
	 */
	public function run()
	{
		list($s, $m) = explode(".", microtime(true));
		$date = date('H:i:s', $s) . '.' . $m;
		return view('sketchpad::help/methods/run', compact('date'));

	}

	/**
	 * Declare a special optional parameter to test code before running it
	 *
	 * @param bool $run
	 */
	public function test($run = false)
	{
		$mode = $run ? true : 'info';
		$status = !!$run
			? "Files processed OK!"
			: "Files are ready to process...";
		alert($status, $mode, 'info-circle');
		echo view('sketchpad::help/methods/test');
	}

	/**
	 * Sketchpad catches framework exceptions, displays the output, and highlights the method until it's corrected and saved / called again.
	 *
	 * If you're using Sketchpad Reload to watch the controller or related PHP files, the page will simply reload when the error is fixed.
	 */
	public function exceptions()
	{
		echo 'Foo is : ' . $foo;
	}

	/**
	 * Method parameters show in the UI as input fields, and re-call the method each time they're changed
	 *
	 * @group Code
	 *
	 * @param string $name
	 */
	public function parameters($name = 'World')
	{
		return view('sketchpad::help/methods/parameters', compact('name'));
	}

	/**
	 * Your method's parameter types determine the parameter UI and the submitted values
	 *
	 * @param string $string    This is a string
	 * @param int    $number    This is a number
	 * @param bool   $boolean   This is a boolean
	 * @param mixed  $mixed     This could be any type
	 */
	public function typeCasting($string = 'hello', $number = 1, $boolean = true, $mixed = null)
	{
		return view('sketchpad::help/methods/typecasting', ['params' => func_get_args()]);
	}

	/**
	 * Sketchpad controllers can be type hinted
	 */
	public function typeHinting(SketchpadConfig $config)
	{
		return view('sketchpad::help/methods/typehinting', compact('config'));
	}

	/**
	 * Sketchpad makes a few classes and variables available to you
	 */
	public function variables(SketchpadConfig $config)
	{
		$route = $config->route;
		$views = $config->views;
		$fullroute = Sketchpad::$route;
		return view('sketchpad::help/methods/variables', compact('route', 'fullroute', 'views'));
	}

	/**
	 * The first line of DocBlock comments are shown in the method list and the page heading
	 *
	 * @group Organisation
	 */
	public function comments()
	{
		return view('sketchpad::help/methods/comments');
	}

	/**
	 * Add a contents page to controllers so you don't see empty space
	 */
	public function contents()
	{
		return view('sketchpad::help/methods/contents');
	}

}