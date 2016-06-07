<?php namespace davestewart\sketchpad\examples;

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
	 * @label Custom label!
	 */
	public function label()
	{
		p('This method\'s name is actually "' . __FUNCTION__ . '"');
		pr('@label Custom label!');
	}
	
	/**
	 * Adds a <a href="http://fontawesome.io/icons/" target="_blank">FontAwesome</a> icon next to the method name
	 *
	 * @icon red bookmark
	 */
	public function icon()
	{
		pr('@icon tick (no need to add the fa- prefix)');
		p('Prefix with a color to colorize:');
		pr('@icon green tick');
	}

	/**
	 * Makes the label text the specified color
	 *
	 * @color red
	 */
	public function color()
	{
		pr('@color red');
	}

	/**
	 * Renders custom css footage in the method list item. Can be used to add icons, colours, styling etc
	 *
	 * @css user
	 */
	public function css()
	{
		pr('@css important');
	}

	/**
	 * Adds a heading and divider before the method
	 *
	 * @group Some new group
	 */
	public function group()
	{
		pr('@group These methods');
		pr('@group Those methods');
	}

	/**
	 * Shows a warning triangle next to the method name, and an alert when you select the method for the first time. Additionally, this method is deferred.
	 *
	 * @warning Only run this once you've asked a grown up for permission
	 */
	public function warning()
	{
		pr('@warning This method sends emails, so be sure you have the right addresses before sending');
	}

	/**
	 * Defers the calling of a method until parameters are changed or the "Run" button is clicked
	 *
	 * @deferred
	 */
	public function deferred()
	{
		pr('@deferred');
	}

	/**
	 * Allows customisation of parameter options
	 *
	 * The format is @options param blah
	 *
	 * @options The thing
	 */
	public function options()
	{
		p('Allows per-field customisation of method parameters. It uses the Laravel validation syntax, and a couple of additional parameters');
		pr('@options {method name} {options}');
	}

	/**
	 * Call the method in an iframe, rather than rendering it to the page
	 *
	 * @iframe
	 */
	public function iframe()
	{
		pr('@iframe');
		phpinfo();
	}

	/**
	 * Marks a method as a favourite
	 *
	 * @favourite
	 */
	public function favourite()
	{
		pr('@favourite');
		pr('@favorite');
	}

	/**
	 * Marks the method as archived, which dims the method name in the methods list
	 *
	 * @archived This method is no longer used
	 */
	public function archived()
	{
		pr('@archived This method has been superseded by someOtherMethod');
	}

	/**
	 * Hides the method from Sketchpad. You might want to do this if you make a public callback, for example
	 *
	 * @label   private
	 * @private
	 */
	public function privateExample()
	{
		pr('@private');
	}

	/**
	 * Marks a particular author as having written this method, which may be used in a future release
	 *
	 * @author Dave Stewart
	 */
	public function author()
	{
		pr('@author Dave Stewart');
	}

	/**
	 * Provides date information to Sketchpad, which may be used in a future release
	 *
	 * @date 2016-05-01
	 */
	public function date()
	{
		pr('@date 20/05/2016');
	}



}