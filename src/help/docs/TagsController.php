<?php namespace davestewart\sketchpad\help\docs;

/**
 * Use custom tags in your controller and method DocBlocks to change Sketchpad's behaviour
 *
 * @package App\Http\Controllers
 */
class TagsController
{

	public function index()
	{
		md(__DIR__ . '/tags.md');
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
		return view('sketchpad::help.tags.css');
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
	 * Prioritize a method or controller towards the top of a group
	 */
	public function order()
	{
		alert('Not yet implemented', false);
		echo view('sketchpad::help.tags.order');
	}

	/**
	 * Allows customisation of parameter fields
	 *
	 * The format is @options param blah
	 *
	 * @group Output
	 *
	 * @field number min:10|max:20
	 * @field date date
	 * @field select options:foo,bar,baz
	 *
	 * @param string $date
	 * @param string $select
	 * @param int    $range
	 */
	public function field($date = '2015-01-01', $select = 'foo', $range = 10)
	{
		$types =
		[
			[
				'attribute' => 'text',
				'description' => 'HTML text input',
				'example' => '<code>text</code>',
			],
			[
				'attribute' => 'number',
				'description' => 'HTML number input',
				'example' => '<code>number</code>',
			],
			[
				'attribute' => 'date',
				'description' => 'HTML date input',
				'example' => '<code>date</code>',
			],
			[
				'attribute' => 'select',
				'description' => 'HTML select input (use with options to specify values)',
				'example' => '<code>select</code>',
			],
		];

		$attributes =
		[
			[
				'attribute' => 'min',
				'description' => 'set the minimum value',
				'example' => '<code>min:0</code>',
			],
			[
				'attribute' => 'max',
				'description' => 'set the maximum value',
				'example' => '<code>max:10</code>',
			],
			[
				'attribute' => 'step',
				'description' => 'set the step size',
				'example' => '<code>step:0.1</code>',
			],
			[
				'attribute' => 'size',
				'description' => 'set the field size',
				'example' => '<code>size:10</code>',
			],
			[
				'attribute' => 'width',
				'description' => 'set the field width, in pixels',
				'example' => '<code>width:100</code>',
			],
			[
				'attribute' => 'maxlength',
				'description' => 'set the maximum character length',
				'example' => '<code>maxlength:10</code>',
			],
			[
				'attribute' => 'pattern',
				'description' => 'set a regex pattern to check the input value against',
				'example' => '<code>pattern:\d{3}</code>',
			],
			[
				'attribute' => 'options',
				'description' => 'set options for select element',
				'example' => '<code>options:foo,bar,baz</code>',
			],
		];

		$format = 'html:example|cols:100,400,200';
        alert('Not yet implemented', false);
		?>
		<p>Allows per-field customisation of method parameters. It uses the Laravel validation syntax, and a couple of additional parameters</p>
<pre>
@field x numeric|min:10|max:20
@field y numeric|min:20|max:30
@field email email
@field date date
</pre>

		<p>The following field types / coercions are available:</p>
		<?php tb($types, $format); ?>

		<p>The following attributes are available:</p>
		<?php tb($attributes, $format); ?>

<?php
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
		return view('sketchpad::help.tags.iframe');
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
		p('Look also at <a href="../basics/testmode">test mode</a> for a more interactive way to test then run conditional code');
	}

	/**
	 * Shows a warning indicator next to the method name, highlights the comment in red, and defers calling of the method.
	 *
	 * @warn
	 */
	public function warn()
	{
		return view('sketchpad::help.tags.warn');
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
	 * Hides the method from the Sketchpad front end
	 *
	 * @label   private
	 */
	public function privateExample()
	{
		p("Both controllers and methods can be marked as private, meaning they won't be added to Sketchpad's controller list");
		p('Normally, you would declare a method as private, but you might need a public method for something like a callback:');
		text('@private');
	}



}