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
		echo 'My function name is actually "' . __FUNCTION__ . '"';
	}

	/**
	 * Adds an icon next to the method name
	 *
	 * @icon red bookmark
	 */
	public function icon()
	{

	}

	/**
	 * Makes the label text the specified color
	 *
	 * @color red
	 */
	public function color()
	{

	}

	/**
	 * Renders custom css classes in the method list item. Can be used to add icons, colours, styling etc
	 *
	 * @css user
	 */
	public function css()
	{

	}

	/**
	 * Adds a heading and divider before the method
	 *
	 * @group Some new group
	 */
	public function group()
	{

	}

	/**
	 * Shows a warning triangle next to the method name, and an alert when you select the method for the first time. Additionally, this method is deferred.
	 *
	 * @warning Only run this once you've asked a grown up for permission
	 */
	public function warning()
	{

	}

	/**
	 * Shows the notice text below the method name in the result panel
	 *
	 * @notice This is some more information for the user
	 */
	public function notice()
	{
		// should this just show the existing comment text?
	}

	/**
	 * Defers the calling of a method until parameters are changed or the "Run" button is clicked
	 *
	 * @deferred The thing
	 */
	public function deferred()
	{

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

	}

	/**
	 * Call the method in an iframe, rather than rendering it to the page
	 *
	 * @iframe html
	 */
	public function iframe()
	{
		phpinfo();
	}

	/**
	 * Marks a method as a favourite
	 *
	 * @favourite The thing
	 */
	public function favourite()
	{

	}

	/**
	 * Hides the method from Sketchpad. You might want to do this if you make a public callback, for example
	 *
	 * @label   private
	 * @private The thing
	 */
	public function privateExample()
	{

	}

	/**
	 * Marks the method as archived, which dims the method name in the methods list
	 *
	 * @archived The thing
	 */
	public function archived()
	{

	}

	/**
	 * Marks a particular author as having written this method, which may be used in a future release
	 *
	 * @date The thing
	 */
	public function author()
	{

	}

	/**
	 * Provides date information to Sketchpad, which may be used in a future release
	 *
	 * @date The thing
	 */
	public function date()
	{

	}



}