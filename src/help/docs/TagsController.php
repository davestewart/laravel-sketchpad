<?php namespace davestewart\sketchpad\help\docs;
use davestewart\sketchpad\services\Sketchpad;

/**
 * Use custom tags in your controller and method DocBlocks to change Sketchpad's behaviour
 *
 * @package App\Http\Controllers
 */
class TagsController
{

	public function index()
	{
		md('sketchpad::help/tags/index');
	}

	/**
	 * Defines a specific label to use, other than the method name
	 *
	 * @group Formatting
	 * @label Custom label!
	 */
	public function label()
	{
		p('This method\'s name is actually "' . __FUNCTION__ . '"');
		text('@label Custom label!');
	}
	
	/**
	 * Makes the label text the specified color
	 *
	 * @color orange
	 */
	public function color()
	{
		p("You won't miss this in a hurry:");
		text('@color orange');
	}

	/**
	 * Adds a <a href="http://fontawesome.io/icons/" target="_blank">FontAwesome</a> icon next to the method name
	 *
	 * @icon  paper-plane
	 */
	public function icon()
	{
		p('Declare an icon name:');
		text('@icon paper-plane');
		p('Prefix with a color to colorize:');
		text('@icon red paper-plane');
	}

	/**
	 * Apply custom css classes to the method list item
	 *
	 * @css fancy
	 */
	public function css()
	{
		return view('sketchpad::help/tags/css');
	}

	/**
	 * Adds a heading and divider before the method
	 *
	 * @group Organisation
	 */
	public function group()
	{
		p('Mark a method as the start of a group:');
		text('@group I am a new group');
		p('Add as many as you like:');
		text('@group I am another new group!');
	}

	/**
	 * Marks a method as a favourite
	 *
	 * @favourite
	 */
	public function favourite()
	{
		p('Use UK or US spelling:');
		text("@favourite\n@favorite");
	}

	/**
	 * Override controller order within a folder
	 */
	public function order()
	{
		echo view('sketchpad::help/tags/order');
	}

	/**
	 * Override basic HTML inputs with more complex HTML input types and attributes
	 *
	 * @group Input / Output
	 *
	 * @param int       $select
	 * @field select    $select     options:One=1,Two=2,Three=3
	 *
	 * @param int       $range
	 * @field number    $range      min:0|max:100|step:5
	 *
	 * @param string    $date
	 * @field date      $date
	 *
	 * @param string    $color
	 * @field color     $color
	 *
	 */
	public function field($select = 1, $range = 0, $date = '2015-01-01', $color = 'ff0000')
	{
		$splits =
		[
			[
				'operator' => '<code>|</code>',
				'grouping' => 'attributes',
				'example' => '<code>min:0|max:100</code>',
			],
			[
				'operator' => '<code>:</code>',
				'grouping' => 'attribute name / attribute value',
				'example' => '<code>step:5</code>',
			],
			[
				'operator' => '<code>,</code>',
				'grouping' => 'options (<code>select</code> and <code>datalist</code> only)',
				'example' => '<code>foo,bar,baz</code>',
			],
			[
				'operator' => '<code>=</code>',
				'grouping' => 'option text / option value',
				'example' => '<code>Yes=1</code>',
			],
		];

		$format = 'html:example|cols:100,400,200';

		$params = Sketchpad::$params;

		return view('sketchpad::help/tags/field', compact('types', 'attributes', 'format', 'params', 'splits'));
	}

	/**
	 * Append (rather than replace) results to the output panel. Re-run or re-save to see it in action...
	 *
	 * @append
	 */
	public function append()
	{
		p('Some new value: ' . rand(1, 100));
	}

	/**
	 * Load the method in an iframe, rather than rendering it to the page
	 *
	 * @iframe
	 */
	public function iframe()
	{
		return view('sketchpad::help/tags/iframe');
	}

	/**
	 * Defers the calling of a method until the "Run" button is clicked
	 *
	 * @group Behaviour
	 *
	 * @defer
	 * @param int $foo
	 */
	public function defer($foo = 1)
	{
		p('Value <code>$foo</code> was set to ' .$foo. ' at ' . date('H:i:s'));
		text('@defer');
		p('Look also at <a href="../methods/test">test mode</a> for a more interactive way to test then run conditional code');
	}

	/**
	 * Shows a warning indicator next to the method name, highlights the comment in red, and defers calling of the method.
	 *
	 * @warn
	 */
	public function warn()
	{
		return view('sketchpad::help/tags/warn');
	}

	/**
	 * Dims the method name in the methods list
	 *
	 * @archived This method is no longer used
	 */
	public function archived()
	{
		p('Probably best to remove methods you no longer use, but if you want to keep them, you can mark them as archived:');
		text('@archived');
	}

	/**
	 * Hides the controller or method from the Sketchpad front end
	 *
	 * @label   private
	 */
	public function hidden()
	{
		p("Both controllers and methods can be marked as private, meaning they won't be added to Sketchpad's controller list:");
		text('@private');
		p('For situations where you want to hide what might otherwise be a private method (for example, a callback) simply add the private tag.');
	}



}