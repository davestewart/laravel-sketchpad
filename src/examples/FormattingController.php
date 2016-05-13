<?php namespace davestewart\sketchpad\examples;

use Illuminate\Routing\Controller;
use Illuminate\Translation\Translator;
use Illuminate\View\FileViewFinder;
use Illuminate\View\View;


/**
 * Use the supplied functions to output and format data nicely
 *
 * @package App\Http\Controllers
 */
class FormattingController extends Controller
{

	/**
	 * No need to return data; just `echo` or use `pr()` to dump it as soon as you're ready
	 */
	public function text()
	{
		pr($this->data());
	}

	/**
	 * Returned objects are converted to JSON and formatted by Sketchpad
	 */
	public function json()
	{
		return $this->data();
	}

	/**
	 * Use `dump()` and `dd()` to format data in an interactive tree
	 */
	public function dump()
	{
		p('Use dump()...');
		dump($this->data());
		p('Ad dd()...');
		dd($this->data());
	}

	/**
	 * Use `tb($data)` to format objects or arrays in table format
	 *
	 * @label   table (as text)
	 * @param   Translator  $translator
	 */
	public function tableText(Translator $translator)
	{
		$data = $translator->get('validation');
		tb($data);
	}

	/**
	 * Use `tb($data, true)` to format the `value` column as code
	 *
	 * @label   table (as code)
	 * @param   string      $config
	 */
	public function tableCode($config = '')
	{
		$data = $config
			? config($config)
			: app()->config->all();
		tb($data, true);
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