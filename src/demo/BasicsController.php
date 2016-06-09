<?php namespace davestewart\sketchpad\examples;

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

	public function index()
	{
		return 'This is an index';
	}

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
	 * Your method's parameter types influence both the parameter UI and the submitted values
	 *
	 * @param string $string    This is a string
	 * @param int    $number    This is a number
	 * @param bool   $bool      This is a bool
	 */
	public function typeCasting($string = 'hello', $number = 1, $bool = true)
	{
		?>

		<p>Your method's optional parameter types (<code>string</code>, <code>boolean</code>, etc) determine the front end input controls.</p>
		<p>Should you need to override determined types, type-hint your DocBocks:</p>
		<pre class="code php">
@param string   $string     This is a text field
@param int      $number     This is a number field
@param bool     $bool       This is a checkbox
</pre>
<p>Note that when you update values, Sketchpad casts them to the expected type; no need for type-juggling!</p>
<?php
	vd(func_get_args());

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
		<p>These links link to other methods, as they resolve to the base route:</p>
		<ul>
			<li>This links to the <a href="../forms/">forms</a> method in the same controller</li>
			<li>This links to one of the <a href="../../tools/viewsession">sample tools</a> in the tools controller</li>
			<li>You can use absolute or relative links</li>
		</ul>

		<p>These links resolve normally, as they don't:</p>
		<ul>
			<li>This links to <a href="http://google.com" target="_blank">Google</a> in a new window</li>
			<li>This runs some <a href="javascript:alert('Well, hello there!')">JavaScript</a></li>
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
		<form class="form" action="" method="post">
			<label for="">Text:</label>
			<input type="text" name="text">
			<button type="submit">Submit</button>
		</form>
		<?php

		if($request->isMethod('post'))
		{
			echo '<hr />';
			p('All you need to do is check for a POST submission in the same method, and take action appropriately:');
			pr($request->all());
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

<p>See the <a href="/sketchpad/demo/output/markdown/">markdown</a> example for more info about the <code>md()</code> method.</p>
<?php
	}

	/**
	 * Customise the look and feel of the app with the user stylesheet
	 */
	public function userCss()
	{
?>
<p>Edit the <code>vendor/sketchpad/user.css</code> stylesheet to:</p>
<ul>
	<li>Add your own styles for custom output</li>
	<li>Override any of the default Sketchpad or Bootstrap styles</li>
	<li>Individually <a href="/sketchpad/demo/tags/css/">target and style</a> controller and method menu items</li>
</ul>
<?php
	}

}