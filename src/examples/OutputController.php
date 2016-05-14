<?php namespace davestewart\sketchpad\examples;

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
	 */
	public function text()
	{
		echo 'Hello there';
	}

	/**
	 * Use `vd()`, `pr()` and `pd()` to output object structures. All functions take variadic parameters
	 */
	public function print_r()
	{
		pr($this->data());
		vd($this->data());
	}

	/**
	 * Return (not echo) objects to convert to JSON and have Sketchpad format interactive output
	 */
	public function json()
	{
		return $this->data();
	}

	/**
	 * Use `dump()` and `dd()` to format data in an interactive tree
	 */
	public function tree()
	{
		p('Use dump()...');
		dump($this->data());
		p('And dd()...');
		dd(app());
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
	 * Use `tb()` to output any Collection or Array of Objects in table format (nested `foreach` loop). Pass a boolean 2nd argument to preformat values
	 *
	 * @param bool $pre
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
				'path'  => $route->getPath(),
			    'parameters' => implode(', ', $route->parameterNames()),
			    'methods' => implode(', ', $route->getMethods()),
				'name'  => $route->getName(),
			    'prefix' => $route->getPrefix(),
			];
		}
		tb($array, $pre);

	}

	/**
	 * Use `p()` and `alert()` to print basic HTML out to the page. Alert takes optional bootstrap alert classes as the 2nd argument
	 */
	public function html()
	{
		p('This is a paragraph');
		alert('This is an info box');
		alert('This is a warning box', 'warning');
		alert('This is a danger box', 'danger');
		alert('This is a success box', 'success');
	}

	/**
	 * Use `md()` to load markdown `.md` documents from your views folder, which will be rendered client-side
	 */
	public function markdown()
	{
		echo md('sketchpad::examples.md.text');
	}

	/**
	 * Use `vue()` to load Vue `.vue` templates from your views folder, even passing data (with no need to escape!)
	 */
	public function vue()
	{
		echo vue('sketchpad::examples.vue.form', ['name' => 'World']);
	}

	protected function data()
	{
		return [
			'number'    => 1,
			'string'    => 'Sketchpad',
			'array'     => [1, 2, 3],
			'object'    => (object) ['a' => 1, 'b' => 2, 'c' => 3],
		];
		
	}
}