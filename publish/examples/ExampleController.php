<?php namespace App\Http\Controllers\Sketchpad;

use App\Http\Controllers\Controller;

/**
 * An example controller to demonstrate some of Sketchpad's functionality 
 *
 * @package App\Http\Controllers
 */
class ExampleController extends Controller
{

	/**
	 * Simple "Hello World" example
	 */
	public function helloWorld()
	{
		echo "Hello World";
	}

	/**
	 * Formats the result of an object as JSON
	 *
	 * Note the use of the @output tag to format the result
	 *
	 * @format json
	 */
	public function formatJSON()
	{
		$data =
			[
				'number'    => 1,
				'string'    => 'Sketchpad',
				'array'     => [1, 2, 3],
				'object'    => (object) ['a' => 1, 'b' => 2, 'c' => 3],
			];
		return $data;
	}

	/**
	 * Dumps the contents of app out to the screen
	 */
	public function dumpApp()
	{
		dd(app());
	}

	/**
	 * Prints the app's current config array
	 * 
	 * Note the use of the @output tag to format the result
	 * 
	 * @format text
	 */
	public function printConfig()
	{
		print_r(app()->config->all());
	}

	/**
	 * Uses the html format to instruct Sketchpad to show the content in an iframe
	 *
	 * @format html
	 */
	public function iframe()
	{
		phpinfo();
	}

	/**
	 * Output the result of phpinfo()
	 *
	 * Note the use of escaping into HTML to output the style tag
	 */
	public function phpInfo($key = 'all')
	{

		$sections =
		[
			'general' => 1,
			'credits' => 2,
			'configuration' => 4,
			'modules' => 8,
			'environment' => 16,
			'variables' => 32,
			'license' => 64,
			'all' => -1,
		];
		$section = isset($sections[$key])
			? $sections[$key]
			: -1;

		?>
		<style type="text/css">
			#output pre {margin: 0; font-family: monospace;}
			#output a:link {color: #009; text-decoration: none; background-color: #fff;}
			#output a:hover {text-decoration: underline;}
			#output table {border-collapse: collapse; border: 0; width: 934px; box-shadow: 1px 2px 3px #ccc;}
			#output .center {text-align: center;}
			#output .center th {text-align: center !important;}
			#output td, th {border: 1px solid #666; font-size: 75%; vertical-align: baseline; padding: 4px 5px;}
			#output h1 {font-size: 150%;}
			#output h2 {font-size: 125%;}
			#output .p {text-align: left;}
			#output .e {background-color: #ccf; width: 300px; font-weight: bold;}
			#output .h {background-color: #99c; font-weight: bold;}
			#output .v {background-color: #ddd; max-width: 300px; overflow-x: auto;}
			#output .v i {color: #999;}
			#output img {float: right; border: 0;}
			#output hr {width: 934px; background-color: #ccc; border: 0; height: 1px;}
		</style>
		<?php

		ob_start();
		phpinfo($section);
		$contents = ob_get_contents();
		ob_end_clean();

		$contents = preg_replace('/^[\s\S]+?body>/', '', $contents);
		$contents = preg_replace('/<\/body>[\s\S]+$/', '', $contents);
		echo $contents;

	}

	/**
	 * This method throws an exception
	 */
	public function exception()
	{
		throw new \Exception('Random exception!');
	}
}