<?php namespace davestewart\sketchpad\help\docs;

use Illuminate\Translation\Translator;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use davestewart\sketchpad\services\Sketchpad;
use davestewart\sketchpad\config\SketchpadConfig;

/**
 * Learn how to display richly-formatted views and leverage front-end features
 *
 * @order 3
 */
class OutputController
{

	public function index()
	{
		md('sketchpad::help/output/index');
	}

	/**
	 * No need to return data or views; just `echo` directly to the page
	 *
	 * @group Views
	 */
	public function noView()
	{
		echo "Hello there! I'm just an <code>echo</code> statement in a method...";
	}

	/**
	 * Load Sketchpad-specific Blade files using the `sketchpad::` view namespace
	 *
	 */
	public function blade(SketchpadConfig $config)
	{
		return view('sketchpad::help/output/blade', ['views' => $config->views]);
	}

	/**
	 * Load Markdown documents from your views folder, even pass data from PHP
	 */
	public function markdown()
	{
		echo view('sketchpad::help/output/markdown');
	}

	/**
	 * Load and run Vue apps from your views folder, even pass data from PHP
	 */
	public function vue($name = 'World')
	{
		?>
<p>The format of the function is:</p>
<pre class="code php">vue($path, $data = []);</pre>

<p>Pass absolute paths or a <code>sketchpad::</code> path along with any data <code>array</code> like so:</p>
<pre><code class="php">vue('sketchpad::help/vue/form', ['name' => 'World']);</code></pre>

<p>The data is injected into the view as a local variable <code>$data</code>, so just reference it in your view like so:</p>
		<?php
		code(base_path('vendor/davestewart/sketchpad/package/views/help/output/vue.vue'), 'html');
		echo '<hr>';
		vue('sketchpad::help/output/vue', ['name' => $name]);
	}

	/**
	 * Sketchpad's is built around Bootstrap, with various tweaks to make it suitable for debugging and admin
	 *
	 * @group HTML
	 */
	public function formatting()
	{
		md('sketchpad::help/output/formatting');
	}
	/**
	 * Sketchpad intercepts normal HTML page links, so you can link to other methods
	 */
	public function links()
	{
		echo view('sketchpad::help/output/links');
	}

	/**
	 * Sketchpad makes using forms easy, by intercepting and `POST`ing action-less forms on your behalf
	 */
	public function forms()
	{
		echo view('sketchpad::help/output/form', ['form' => Sketchpad::$form]);
	}

	/**
	 * Sketchpad is designed to work invisibly with pagination, by preserving URL variables between front and back end
	 *
	 * @param int $start
	 * @param int $length
	 */
	public function pagination($start = 1, $length = 10)
	{
		// manually create paginator
		$data   = array_map(function ($num) { return ['id' => $num, 'value' => "Item $num"]; }, range(1, 100));
		$page   = Paginator::resolveCurrentPage('page');
		$path   = Paginator::resolveCurrentPath();
		$items  = array_slice($data, abs($start - 1) + (($page - 1) * $length), $length);
		$paginator  = new LengthAwarePaginator($items, count($data), $length, $page, [
			'path' => $path,
			'pageName' => 'page',
		]);

		// append existing parameters
		$paginator->appends(Sketchpad::$params);

		// output
		return view('sketchpad::help/output/pagination', compact('items', 'paginator'));

	}

}