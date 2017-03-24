<?php namespace davestewart\sketchpad\help\docs;

use davestewart\sketchpad\config\SketchpadConfig;
use Illuminate\Routing\Controller;
use Illuminate\View\FileViewFinder;
use Illuminate\View\View;
use Illuminate\Http\Request;

/**
 * Get to know Sketchpad's basic functions
 *
 * @package App\Http\Controllers
 */
class BasicsController extends Controller
{

	/**
	 * Call a method just by clicking on its label
	 */
	public function methodCall()
	{
		echo 'This method was called on ' . date(DATE_RFC850);
	}

	/**
	 * Method parameters show in the UI as input fields, and re-call the method each time they're changed
	 *
	 * @param string $name
	 */
	public function parameters($name = 'world')
	{
		p("Hello $name!", true);
		p('Update the parameter to automatically call the method again.');
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
		?>

<p>Your method's parameter types (<code>string</code>, <code>boolean</code>, etc) determine the HTML input control types.</p>
<p>They also enable Sketchpad to cast submitted values to the expected type; no need for type-juggling in your methods:</p>
<?php
	vd(func_get_args());
?>
<p>Should you need to override determined types, type-hint your DocBocks:</p>
<pre class="code php">
@param  string   $string   This is a text field
@param  int      $number   This is a number field
@param  boolean  $boolean  This is a checkbox
@param  mixed    $mixed    This is a text field (but will be converted to the correct type)
</pre><?php
	}

	/**
	 * Sketchpad catches exceptions, shows you the full stack trace, and highlights the method in red until it's corrected and called again. If you're using Gulp to watch the controller or related PHP files, the page will simply reload when the error is fixed.
	 */
	public function exceptionHandling()
	{
		echo $foo * 2;
	}

	/**
	 * Sketchpad intercepts normal HTML page links, so you can link to other methods
	 */
	public function links()
	{
		?>
		<p>Relative paths run other methods:</p>
		<ul>
			<li>This links to the <a href="forms">forms</a> method in the same controller</li>
			<li>This links to one of the <a href="../examples/tools/dumpapp">sample tools</a> in the tools controller</li>
		</ul>

		<p>Absolute paths (outside of sketchpad) run as normal:</p>
		<ul>
			<li>This loads the <a href="/">main app</a></li>
		</ul>

		<p>External links run as normal:</p>
		<ul>
			<li>This links to <a href="http://google.com" target="_blank">Google</a> in a new window</li>
			<li>This runs some <a href="javascript:alert('Sketchpad rocks!')">JavaScript</a></li>
		</ul>

		<?php
	}

	/**
	 * Sketchpad makes using forms easy, by intercepting and `POST`ing action-less forms on your behalf
	 */
	public function forms(Request $request)
	{
		?>
		
		<p>Type something below and submit the form back to the same URL:</p>
		<!-- any form with an empty or missing "action" attribute will be intercepted and submitted by sketchpad -->
		<form class="form form-inline">
			<input  class="form-control" type="text" name="text" placeholder="Type something here...">
			<button class="form-control" type="submit">Submit</button>
		</form>
		<?php

		if($request->isMethod('post'))
		{
			echo '<hr />';
			p('All you need to do is check for a POST submission in the same method, and take action appropriately:');
			dump($request->all());
		}
	}

	/**
	 * The first line of DocBlock comments are shown in the method list and the page heading
	 */
	public function comments()
	{
?>
<p>This makes it easy to see what a method does before calling it:</p>
<pre class="code php">
/**
 * The first line of DocBlock comments are shown in the method list and the page heading
 *
 * This line will not be shown
 */
public function comments()
{

}
</pre>
<?php
	}

	/**
	 * Add index "pages" to controllers by providing an `index()` method and returning text, a view, markdown, etc
	 */
	public function indexPage()
	{
?>
<p>It's as simple as this:</p>
<pre class="code php">
class SomeController extends Controller
{
    public function index()
    {
        return md('path.to.index'); // example uses markdown, but you could just as easily use Blade
    }
}</pre>
<p>When the controller is selected in the left hand menu, it will show an index page.</p>

<p>If you want to cheat, just save a markdown file in the same folder as the controller:</p>

<pre class="code php">
return md(__DIR__ . '/some.md');
</pre>

<p>See the <a href="../output/markdown">markdown</a> example for more info about the <code>md()</code> method.</p>
<?php
	}

	/**
	 * Customise Sketchpad with user scripts and styles
	 */
	public function userAssets(SketchpadConfig $config)
	{
		$route = $config->route;
		$assets = $config->settings->get('paths.assets');
?>

		<p>Sketchpad allows you to add custom assets to the app by way of:</p>
		<ul>
			<li>Editable user asset files</li>
			<li>Loadable asset URLs</li>
		</ul>
		<p>During setup, two starter files are copied to <code><?php echo $assets; ?></code>.</p>
		<p>Both files are then set to load (by default) from the <a href="<?= $route; ?>settings">settings</a> page's user assets section:</p>
		<pre>
/sketchpad/user/scripts.js
/sketchpad/user/styles.css
</pre>

		<p>Sketchpad will then add these URLs as <code>&lt;head&gt;</code> assets, along with any other URLs (such as CDNs) you specify.</p>
		<p>Note the special user assets route <code>/sketchpad/user/</code> which loads the file contents directly, whether or not they are in your <code>public</code> folder.</p>
		<p>View the <a href="<?= $route; ?>settings">settings</a> page to edit these URLs directly.</p>
<?php
	}

}