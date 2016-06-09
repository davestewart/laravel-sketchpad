<?php namespace davestewart\sketchpad\demo;

use Illuminate\Routing\Controller;
use Illuminate\Translation\Translator;


/**
 * Use the supplied functions to output and format data nicely
 *
 * @package App\Http\Controllers
 */
class OutputController extends Controller
{


	/**
	 * No need to return data or views; just `echo` directly to the page
	 *
	 * @label echo
	 */
	public function text()
	{
		echo 'Hello there';
	}

	/**
	 * Use `p()` to print HTML paragraphs tags
	 */
	public function paragraph()
	{
		p('This is a paragraph');
		p('This is a paragraph with <code>true</code> passed as the second argument; the css class <code>note</code> is added', true);
	}

	/**
	 * Use `alert()` to print Bootstrap "alert" message boxes to the page
	 */
	public function alert()
	{
		p('Just text passed; defaults to "info" class:');
		alert('Just text passed');

		p('Passed with a 2nd argument of a Bootstrap <a href="http://getbootstrap.com/components/#alerts" target="_blank">alert</a> message class:');
		alert('Passed with "warning"', 'warning');
		alert('Passed with "danger"', 'danger');
		alert('Passed with "success"', 'success');

		p('Passed with 2nd argument of a boolean state, renders tick and cross icons:');
		alert('Passed with true', true);
		alert('Passed with false', false);
	}

	/**
	 * Use `vd()`, `pr()` and `pd()` to output object structures with HTML `pre` tag. All functions take variadic parameters
	 *
	 * @label print_r
	 */
	public function print_r()
	{
		p('Use <code>pr()</code> to format and <code>print_r()</code>:');
		pr($this->data());

		p('Use <code>vd()</code> to format and <code>var_dump()</code>:');
		vd($this->data());
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
	 * Return (*not* echo) objects to convert to JSON and have Sketchpad format interactive output
	 */
	public function json()
	{
		return $this->data();
	}

	/**
	 * Use `ls()` to output any Object or Array in list format (single `foreach` loop). Pass a boolean 2nd argument to preformat values
	 *
	 * @label list
	 *
	 * @param bool         $pre
	 */
	public function ls($pre = false)
	{
		$pre    = $pre == 'true';
		$data   = \App::make(Translator::class)->get('validation');
		ls($data, $pre);
	}

	/**
	 * Use `tb()` to output any Collection or Array of Objects in table format (nested `foreach` loop). Pass a boolean 2nd argument to preformat values*
	 */
	public function table($pre = false)
	{
		$pre    = $pre == 'true';
		$routes = \Route::getRoutes();
		$array  = [];
		foreach ($routes as /** @var Route */ $route)
		{
			$array[] =
			[
			    'methods' => implode(', ', $route->getMethods()),
				'path'  => $route->getPath(),
			    'parameters' => implode(', ', $route->parameterNames()),
			];
		}
		tb($array, $pre);

	}

	/**
	 * Use `md()` to load markdown `.md` documents from your views folder, which will be rendered client-side
	 */
	public function markdown()
	{
		echo md('sketchpad::demo.md.text');
	}

	/**
	 * Use `vue()` to load Vue `.vue` templates from your views folder, even passing data (with no need to escape!)
	 */
	public function vue()
	{
		echo vue('sketchpad::demo.vue.form', ['name' => 'World']);
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