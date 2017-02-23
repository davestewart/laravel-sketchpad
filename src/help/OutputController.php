<?php namespace davestewart\sketchpad\help;

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
		p('This is a paragraph with <code>"special"</code> passed as the second argument; the user css class <code>special</code> is added', 'special');
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
	 * Use `ls()` to output any Object or Array in list format (single `foreach` loop)
	 *
	 * @label list
	 * @param string $options
	 */
	public function ls($options = '')
	{
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

		?>
		<p>This function takes an optional formatting string, with a syntax similar to Laravel validation:</p>
		<pre>tb($data, '<?php echo $options; ?>');</pre>
		<p>The following table outlines the available options:</p>

		<?php
		tb($data, $options);
?>

		<p>Within the options string, you can:</p>
		<ul>
			<li>add multiple options, using the pipe character</li>
			<li>specify arguments, using a colon</li>
			<li>add multiple arguments, separated with commas</li>
		</ul>

		<p>Experiment with updating the options string above, or click the links below to explore some presets:</p>
<ul>
	<li><a href="?options=html:example|index">Add an index</a></li>
	<li><a href="?options=html:example|index|cols:100,400,300">Set column widths</a></li>
	<li><a href="?options=html:example|index|cols:100,400,300|index|style:background:white;z-index:1000;transform:rotate(10deg)">Set the style</a></li>
	<li><a href="?options=html:example|label:Table formatting options">Set a table caption</a></li>
	<li><a href="?options=html:example">Reset the table</a></li>
	<li><a href="?options=">Clear all settings</a></li>
</ul>
<?php
	}

	/**
	 * Use `md()` to load markdown `.md` documents from your views folder, which will be rendered client-side
	 */
	public function markdown()
	{
		echo md('sketchpad::help.md.text');
	}

	/**
	 * Use `vue()` to load Vue `.vue` templates from your views folder, even passing data (with no need to escape!)
	 */
	public function vue()
	{
		echo vue('sketchpad::help.vue.form', ['name' => 'World']);
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