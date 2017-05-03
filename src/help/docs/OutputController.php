<?php namespace davestewart\sketchpad\help\docs;

use Illuminate\Translation\Translator;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use davestewart\sketchpad\services\Sketchpad;
use davestewart\sketchpad\config\SketchpadConfig;

/**
 * Use various techniques and helpers to print and format text, html and data
 *
 * @package App\Http\Controllers
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
	 * @label echo
	 * @group Text
	 */
	public function echooo()
	{
		echo 'Hello there';
	}

	/**
	 * Output text in HTML paragraphs tags
	 */
	public function paragraph()
	{
		?>
		<p>The format of the method is:</p>
		<pre class="code php">p($text, $class = '');</code></pre>
		<p>You can print <strong>normal</strong>, <strong>note</strong> and <strong>custom</strong>-classed paragraphs:</p>
		<div style="margin: 20px 25px 30px;">

		<?php
			p('I am normal');
			p('I am a note; I passed a boolean true as my 2nd argument', true);
			p('I am custom; I passed the string "special" as my 2nd argument', 'special');
			?>
		</div>
		<?php
		p('See the <a href="../basics/userassets">user assets</a> section on how to customise the supplied styles.');
	}

	/**
	 * Output preformatted text
	 */
	public function text()
	{
		?>
		<p>The format of the method is:</p>
		<pre class="code php">text($text);</pre>
		<p>This is a paragraph...</p>
		<?php
		text('...and this is some text');
	}

	/**
	 * Output code with formatting and html entities converted
	 */
	public function code()
	{
		?>
		<p>The format of the method is:</p>
		<pre class="code php">code($text, $format = 'php');</pre>
		<p>As an example, the contents of this file is:</p>
		<?php
		code(file_get_contents(__FILE__));
	}

	/**
	 * Use `alert()` to print Bootstrap "alert" message boxes to the page
	 */
	public function alert()
	{
		?>
		<p>The format of the method is:</p>
		<pre class="code php">alert($html, $class = 'info', $icon = '');</pre>
		<?php
		p('Pass text only to output a basic Bootstrap "info" alert box:');
		alert('Just text passed');

		p('Pass a 2nd argument of a Bootstrap <a href="http://getbootstrap.com/components/#alerts" target="_blank">alert</a> message class:');
		alert('Passed with "success"', 'success');
		alert('Passed with "warning"', 'warning');
		alert('Passed with "danger"', 'danger');

		p('Pass a 2nd argument of a boolean state to render tick or cross icons:');
		alert('Passed with true', true);
		alert('Passed with false', false);

		p('Pass a 3rd argument of a string to render a custom <a href="http://fontawesome.io/icons/" target="_blank">Font Awesome</a> icon:');
		alert('Passed with "info" and "info"', 'info', 'info');
		alert('Passed with "warning" and "bolt"', 'warning', 'bolt');
	}

	/**
	 * Sketchpad intercepts normal HTML page links, so you can link to other methods
	 *
	 * @group HTML
	 */
	public function links()
	{
		echo view('sketchpad::help.output.links');
	}

	/**
	 * Sketchpad makes using forms easy, by intercepting and `POST`ing action-less forms on your behalf
	 */
	public function forms()
	{
		echo view('sketchpad::help.output.form', ['form' => Sketchpad::$form]);
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
		return view('sketchpad::help.output.pagination', compact('items', 'paginator'));

	}

	/**
	 * Use `vd()`, `pr()` and `pd()` to output object structures with HTML `pre` tag and some basic syntax highlighting
	 *
	 * @label print_r
	 * @group Data
	 */
	public function print_r()
	{
		p('Use <code>pr()</code> to format and <code>print_r()</code>:');
		pr($this->data());

		p('Use <code>vd()</code> to format and <code>var_dump()</code>, with a slightly tweaked structure to bring it more into line with <code>pr()</code>:');
		vd($this->data());
		p('Note that all functions take variadic parameters, so you do the following:');
		echo '<pre class="code php">pr($foo, $bar, $baz);</pre>';
	}

	/**
	 * Use `dump()` and `dd()` to format data in an interactive tree
	 */
	public function dump()
	{

		p('Use <code>dump()</code> to format and dump:');
		dump($this->data());
		p('And <code>dd()</code> to format and dump and die:');
		dd(app());
	}

	/**
	 * Output objects as JSON and have Sketchpad render them interactively
	 */
	public function json()
	{
		p('Use <code>json()</code> to output objects inline as JSON:');
		json($this->data());
		p('Alternatively, you can simply <i>return</i> any complex object, and Sketchpad will format it for you.');
	}

	/**
	 * Use `ls()` to output any Object or Array in list format (single `foreach` loop)
	 *
	 * @label list
	 * @param string $options
	 */
	public function ls($options = '')
	{
		?>
		<p>The format of the method is:</p>
		<pre class="code php">ls($values, $options = '');</pre>
		<p>The options are the same as the <a href="table">table</a> function.</p>
		<p>This is the validation config array, formatted as a list:</p>
		<?php
		$data   = \App::make(Translator::class)->get('validation');
		ls($data, $options);
	}

	/**
	 * Use `tb()` to output any Collection or Array of Objects in table format (nested `foreach` loop)
	 *
	 * @param string $options
	 */
	public function table($options = 'html:example')
	{

		$rows =
		[
			["option","description","example"],
			["index","Adds a numeric index column to the table","index"],
			["pre","Preformats the entire table, or selected columns","pre, pre:example"],
			["html","Specifies which columns to output as HTML","html:example|html:description,example"],
			["label","Adds a label to the table","label:Formatting options"],
			["width","Sets the width of the table","width:100%"],
			["cols","Sets the width of individual columns","cols:50,400,200|cols:10%,60%,30%"],
			["class","Sets the table class attribute","class:fancy"],
			["style","Sets the table style attribute","style:border:1px solid red; background:blue"]
		];

		$keys   = array_shift($rows);
		$data   = array_map(function($values) use ($keys){
			return array_combine($keys, $values);
		}, $rows);

		foreach ($data as $index => $value)
		{
			$example = $data[$index]['example'];
			$data[$index]['example'] = implode(' ', array_map(function($value){ return "<code>$value</code>";}, explode('|', $example)));
		}

		return view('sketchpad::help.output.table', compact('data', 'options'));
	}

	/**
	 * Use the `sketchpad::` view shortcut to load sketchpad-specific blade views
	 *
	 * @group File
	 */
	public function blade(SketchpadConfig $config)
	{
		return view('sketchpad::help.output.blade', ['views' => $config->views]);
	}

	/**
	 * Use `md()` to load markdown `.md` documents from your views folder, which will be rendered client-side
	 */
	public function markdown()
	{
		p('Pass absolute paths or a <code>sketchpad::path.to.view</code> reference.');
		md('sketchpad::help.output.text');
		p('Here is some general markdown formatting:');
		md('sketchpad::help.output.formatting');
		echo '<style>.markdown { margin:25px; }</style>';
	}

	/**
	 * Use `vue()` to load Vue `.vue` templates from your views folder, and even pass data from PHP.
	 */
	public function vue($name = 'World')
	{
		vue('sketchpad::help.output.vue', ['name' => $name]);
	}

	protected function data()
	{
		return [
			'number'    => 1,
			'boolean'   => true,
			'string'    => 'Sketchpad',
			'array'     => [1, 2, 3],
			'object'    => (object) ['a' => 1, 'b' => 2, 'c' => 3],
		];
		
	}
}