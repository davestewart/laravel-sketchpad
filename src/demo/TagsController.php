<?php namespace davestewart\sketchpad\demo;

use davestewart\sketchpad\objects\SketchpadConfig;
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
	 * Example of using a different label
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
<p>It's styled (in part) with the following code:</p>
<pre class="code css">
li.fancy{
    padding:3px;
    border:1px dashed red;
    border-radius:7px;
    left:-3px;
    top:-3px;
}</pre>
<p>See the <a href="/sketchpad/demo/basics/usercss/">user css</a> section for more information about the user stylesheet.</p>
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
	 * Marks the method as archived, which dims the method name in the methods list
	 *
	 * @archived This method is no longer used
	 */
	public function archived()
	{
		p('Probably best to remove methods you no longer use, but if you want to keep them, you can mark them as archived:');
		pr('@archived This method has been superseded by someOtherMethod');
	}

	/**
	 * Hides the method from the Sketchpad front end
	 *
	 * @label   private
	 */
	public function privateExample()
	{
		p('Normally, you would declare a method as private, but you might need a public method for something like a callback:');
		pr('@private');
	}

	/**
	 * Allows customisation of parameter options
	 *
	 * The format is @options param blah
	 *
	 * @group Behaviour
	 * @options The thing
	 */
	public function options()
	{
		alert('Not yet implemented', 'warning');
		p('Allows per-field customisation of method parameters. It uses the Laravel validation syntax, and a couple of additional parameters');
		pr('@options {method name} {options}');
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
	 * @deferred
	 */
	public function deferred()
	{
		p('This method was called on ' . date(DATE_RFC850));
		pr('@deferred');
	}

	/**
	 * Shows a warning indicator next to the method name, highlights this text in red, and defers calling of the method.
	 *
	 * @warning
	 */
	public function warning()
	{
		p("Hopefully the big red lozenge didn't put you off too much:");
		pr('@warning');
		p('When the method is finally called, the deferred task, such as sending emails, will be run.');
?>
<p>If you need to pass data to the deferred methods, your other options are:</p>
		<ul>
			<li>Use <a href="../../basics/parameters/">method parameters</a> along with the warning</li>
			<li>Use an <a href="../../basics/forms/">HTML form</a>, which Sketchpad will intercept and submit back to the original method</li>
		</ul>
<?php
	}


}