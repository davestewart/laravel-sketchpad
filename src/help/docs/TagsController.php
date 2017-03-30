<?php namespace davestewart\sketchpad\help\docs;

use Illuminate\Routing\Controller;
use Illuminate\View\FileViewFinder;
use Illuminate\View\View;

/**
 * Use custom tags in your controller and method DocBlocks to change Sketchpad's behaviour
 *
 * @package App\Http\Controllers
 */
class TagsController extends Controller
{

	/**
	 * Defines a specific label to use, other than the method name
	 *
	 * @group Formatting
	 * @label Custom label!
	 */
	public function label()
	{
		p('This method\'s name is actually "' . __FUNCTION__ . '"');
		pr('@label Custom label!');
	}
	
	/**
	 * Makes the label text the specified color
	 *
	 * @color orange
	 */
	public function color()
	{
		p("You won't miss this in a hurry:");
		pr('@color orange');
	}

	/**
	 * Adds a <a href="http://fontawesome.io/icons/" target="_blank">FontAwesome</a> icon next to the method name
	 *
	 * @icon  bookmark
	 */
	public function icon()
	{
		p('Declare an icon name:');
		pr('@icon bookmark');
		p('Prefix with a color to colorize:');
		pr('@icon red bookmark');
	}

	/**
	 * Apply custom css classes to the method list item
	 *
	 * @css fancy
	 */
	public function css()
	{
?>
<p>The current method item <code>&lt;li&gt;</code> has a suitably over-the-top class <code>.fancy</code> added to it:</p>
<pre>@css fancy</pre>
<p>It's styled (in part) with the following code in the user <code>styles.css</code> stylesheet (click <a href="javascript:toggleUserStyles()">here</a> to disable it):</p>
<pre class="code css">
li.fancy{
    border:1px solid #333;
    background: repeating-linear-gradient(
        -45deg,
        #222,
        #222 5px,
        #333 5px,
        #333 10px
    );
}</pre>
<p>See the <a href="../basics/userassets">user assets</a> section for more information.</p>
<script>function toggleUserStyles()
{
	var $link = $('link[href$="user/styles.css"]');
	$link.is('[disabled]')
		? $link.removeAttr('disabled')
		: $link.attr('disabled', 'disabled');
}</script>
<?php
	}

	/**
	 * Adds a heading and divider before the method
	 *
	 * @group Organisation
	 */
	public function group()
	{
		p('Mark a method as the start of a group:');
		pr('@group I am a new group');
		p('Add as many as you like:');
		pr('@group I am another new group!');
	}

	/**
	 * Marks a method as a favourite
	 *
	 * @favourite
	 */
	public function favourite()
	{
		p('Use UK or US spelling:');
		pr("@favourite\n@favorite");
	}

	/**
	 * Prioritize a method or controller towards the top of a group
	 */
	public function order()
	{
		alert('Not yet implemented', false);
		?>
		<p>The tag takes a single numeric integer:</p>
		<pre>@order 1</pre>
		<p>Lower numbers appear first (so the same as natural numbering) with unordered members appearing after, in their given order:</p>
		<ul>
			<li>Groups will appear in the order they are listed in config</li>
			<li>Controllers will appear in filesystem order</li>
			<li>Methods will appear in the order they are written</li>
		</ul>
		<?php
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
		phpinfo();
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
		pr('@defer');
	}

	/**
	 * Shows a warning indicator next to the method name, highlights the comment in red, and defers calling of the method.
	 *
	 * @warn
	 */
	public function warn()
	{
		p("Hopefully the big red lozenge didn't put you off too much:");
		pr('@warn');
		p('When the method is finally called, the deferred task, such as sending emails, will be run.');
?>
<p>If you need to pass data to the deferred methods, your other options are:</p>
		<ul>
			<li>Use <a href="../../basics/parameters/">method parameters</a> along with the warning</li>
			<li>Use an <a href="../../basics/forms/">HTML form</a>, which Sketchpad will intercept and submit back to the original method</li>
		</ul>
<?php
	}

	/**
	 * Dims the method name in the methods list
	 *
	 * @archived This method is no longer used
	 */
	public function archived()
	{
		p('Probably best to remove methods you no longer use, but if you want to keep them, you can mark them as archived:');
		pr('@archived');
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
		pr('@private');
	}



}