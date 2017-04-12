<?php namespace davestewart\sketchpad\help\docs;

use DateTime;
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

	public function index()
	{
		?>
		<p class="alert alert-info">Note that for <strong>all</strong> demo methods, you can simply look at the source code!</p>
		<p>All demos are just <strong>regular Laravel controller methods</strong>, that return or echo output.</p>
		<p>You can view all demo code in <code>vendor/davestewart/sketchpad/src/help</code>.</p>
		<?php
	}

	/**
	 * Run a method just by clicking on its label
	 *
	 * @group Execution
	 */
	public function runMethod()
	{
		$d = new DateTime();
		$date = $d->format('H:i:s.u');
		p('Method called at:');
		echo "<pre>$date</pre>";
		p('Run again by clicking the <strong>Run</strong> button');
	}

	/**
	 * Method parameters show in the UI as input fields, and re-call the method each time they're changed
	 *
	 * @param string $name
	 */
	public function parameters($name = 'world')
	{
?>
<p>The result of this call is:</p>
<pre>Hello <?php echo $name ?></pre>
<p>Optional parameters are exposed as editable front-end inputs:</p>
<pre class="code php">
public function parameters($name = 'world')
{
    echo "Hello $name";
}
</pre>
<p>Update the parameter to automatically call the method again</p>
<?php
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
<pre class="code php">
public function typeCasting($string = 'hello', $number = 1, $boolean = true, $mixed = null)
{
    // do something with parameters
}
</pre>
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
	 * Declare a special optional parameter to test code before running it
	 *
	 * @param int $id
	 * @param bool $run
	 */
	public function testMode($id = 1, $run = false)
	{
		$mode = $run ? true : 'info';
		$status = !!$run
			? "Action taken for user $id !"
			: "Showing user $id";
		alert($status, $mode);
		?>
		<p>There are often occasions where you want to <strong>test</strong> code before running it.</p>
		<p>Sketchpad allows you to set an additional boolean parameter <code>$run</code> which creates a special <code>Test / Run</code> toggle <i class="fa fa-bolt"></i> on the front end:</p>
		<pre class="code php">public function emailUser($id = 1, $run = false) { ... }</pre>
		<p>If you prefer, use parameter names <code>$save</code> or <code>$update</code> (which also affect the button label):</p>
		<pre class="code php">public function addColumn($name = 1, $save = false) { ... }
public function editUser($id = 1, $udpate = false) { ... }</pre>
		<p>This allows you to preview output and <em>only</em> run additional code when happy with the results:</p>
		<pre class="code php">
public function emailUser($id = 1, $run = false)
{
    // always get and show user
    $user = User::find($id);
    ls($user);

    // conditionally send email
    if ($run)
    {
        // email the user...
    }
}
</pre>
		<p>Note that each time parameters are updated or the the method is called, the mode is reset to "Test".</p>
		<?php
	}

	/**
	 * Sketchpad catches framework exceptions, displays the output, and highlights the method until it's corrected and called again. If you're using Sketchpad Reload to watch the controller or related PHP files, the page will simply reload when the error is fixed.
	 */
	public function exceptions()
	{
		echo $foo * 2;
	}

	/**
	 * The first line of DocBlock comments are shown in the method list and the page heading
	 *
	 * @group Organisation
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
        md('path.to.index'); // example uses markdown, but you could just as easily use Blade
    }
}</pre>
<p>When the controller is selected in the left hand menu, it will show an index page.</p>

<p>If you want to cheat, just save a markdown file in the same folder as the controller:</p>

<pre class="code php">
md(__DIR__ . '/some.md');
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
			<li>Custom user asset files</li>
			<li>Loadable asset URLs</li>
		</ul>
		<p>During setup, two starter files were copied to your installation's <code>assets/</code> folder.</p>
		<pre>
<?php echo base_path($assets . 'scripts.js'); ?>

<?php echo base_path($assets . 'styles.css'); ?>
</pre>
		<p>These files are set to load with Sketchpad by default, along with any other URLs you add (for example <a href="https://momentjs.com/" target="_blank">Moment.js</a>):</p>
		<pre>
https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.0/moment.js
$assets/scripts.js
$assets/styles.css
</pre>

		<p>Note the special "user assets" route <code>$assets/</code> which loads the static file contents directly – they do not need to be in your app's <code>/public/</code> folder!</p>
		<p>Feel free to <a href="../tags/css">edit these files</a> or update asset URLs on the <a href="<?= $route; ?>settings">settings</a> page.</p>
<?php
	}

}