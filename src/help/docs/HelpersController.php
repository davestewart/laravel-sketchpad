<?php namespace davestewart\sketchpad\help\docs;

use davestewart\sketchpad\utils\Code;
use davestewart\sketchpad\utils\Options;
use Illuminate\Translation\Translator;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Use various techniques and helpers to print and format text, html and data
 *
 * @package App\Http\Controllers
 */
class HelpersController
{

	public function index()
	{
		md('sketchpad::help/helpers/index');
	}

	/**
	 * Output text in HTML paragraphs tags
	 *
	 * @group Text
	 */
	public function paragraph()
	{
		?>
		<p>The format of the function is:</p>
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

		p('See the <a href="../setup/assets">assets</a> section on how to customise the supplied styles.');
	}

	/**
	 * Output preformatted text
	 */
	public function text()
	{
		?>
		<p>The format of the function is:</p>
		<pre class="code php">text($text);</pre>
		<p>This is a paragraph...</p>
		<?php
		text('...and this is some text');
	}

	/**
	 * Output code with formatting and html entities converted
	 *
	 * @field select $arguments options:Raw PHP=php,Raw JavaScript=js,File=file,Section=section,Section (without indent)=sectionu,Class=class,Class (with doc-comment)=classc,Method=method,Method (with doc-comment)=methodc
	 */
	public function code($arguments = 'php')
	{
		$params =
		[
			'php'      => [
				'desc' => 'raw PHP',
				'func' => 'output',
				'args' => ['$a + $b === 100 ? true : false']
			],
			'js'       => [
				'desc' => 'raw JavaScript',
				'func' => 'output',
				'args' => ['a + b === 100 ? true : false', 'javascript']
			],
			'file'     => [
				'desc' => 'this file',
				'func' => 'file',
				'args' => [__FILE__]
			],
			'section'  => [
				'desc' => 'lines 17 - 44 of this file',
				'func' => 'section',
				'args' => [__FILE__, 18, 45]
			],
			'sectionu' => [
				'desc' => 'lines 17 - 44 of this file (without indent)',
				'func' => 'section',
				'args' => [__FILE__, 18, 45, true]
			],
			'class'    => [
				'desc' => 'this class',
				'func' => 'classfile',
				'args' => [__CLASS__]
			],
			'classc'   => [
				'desc' => 'this class (with doc-comment)',
				'func' => 'classfile',
				'args' => [__CLASS__, true]
			],
			'method'   => [
				'desc' => 'this method',
				'func' => 'method',
				'args' => [__CLASS__, 'code']
			],
			'methodc'  => [
				'desc' => 'this method (with doc-comment)',
				'func' => 'method',
				'args' => [__CLASS__, 'code', true]
			],
		];
		$data = $params[$arguments];
		$data['signature'] = Code::signature($data['args']);
		return view('sketchpad::help/helpers/code', $data);
	}

	/**
	 * Use `alert()` to print Bootstrap "alert" message boxes to the page
	 */
	public function alert()
	{
		?>
		<p>The format of the function is:</p>
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
	 * Create a Font Awesome icon simply and easily
	 */
	public function icon()
	{
		$input = [
			[icon('plane'),                  "icon('plane');",               ],
			[icon('bolt'),                   "icon('bolt');",                ],
			[icon('bolt', '#FF00FF'),        "icon('bolt', '#FF00FF');",     ],
			[icon('info', 'info'),           "icon('info', 'info');",        ],
			[icon(true),                     "icon(true);",                  ],
			[icon(false),                    "icon(false);",                 ],
		];
		$data = array_reduce($input, function ($output, $arr)
		{
			$output[] = (object) ['code' => $arr[1], 'icon' => $arr[0]];
			return $output;
		}, []);
		return view('sketchpad::help/helpers/icon', compact('data'));
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
	 * Use `ls()` to output any Object or Array in list format
	 *
	 * @label list
	 * @param string $options
	 */
	public function ls($options = '')
	{
		$rows =
		[
			["option","description","example"],
			["pre","Preformats the value column","pre"],
			["wide","Sets the width of the table to be 100%","wide"],
			["class","Sets the table class attribute","class:fancy"],
			["style","Sets the table style attribute","style:color:blue"]
		];

		$keys   = array_shift($rows);
		$opts   = array_map(function($values) use ($keys) {
			return array_combine($keys, $values);
		}, $rows);

		foreach ($opts as $index => $value)
		{
			$example = $opts[$index]['example'];
			$opts[$index]['example'] = implode(' ', array_map(function($value){ return "<code>$value</code>";}, explode('|', $example)));
		}

		$data   = \App::make(Translator::class)->get('validation');

		return view('sketchpad::help/helpers/list', compact('data', 'opts', 'options'));
	}

	/**
	 * Use `tb()` to output Collections or Arrays in table format
	 *
	 * @param string $options
	 */
	public function table($options = 'caption:Example table|icon:state,icon|html:html')
	{
		function make($rows)
		{
			$keys   = array_shift($rows);
			$data   = array_map(function($values) use ($keys) {
				return array_combine($keys, $values);
			}, $rows);
			return $data;
		}

		$preview = make([
			['state','icon','value','text','html'],
			[true,'plane',1,'one','<strong>I am bold</strong>'],
			[false,'certificate',2,'two','<em>I am italic</em>'],
			[true,'bolt',3,'three','<code>true</code>'],
			[false,'wrench',4,'four','<a href="?options=index">Add an index</a>']
		]);

		$data = make([
			["option","description","example"],
			["index","Adds a numeric index column to the table","index"],
			["icon","Specifies which columns to output as icons","icon:state|icon:icon"],
			["html","Specifies which columns to output as HTML","html:html|html:text,html"],
			["keys","Specify which columns to build","keys:value,text|keys:value,icon,*"],
			["cols","Sets the width of individual columns","cols:50,100,150,150,400|cols:10%,60%,30%"],
			["type","Sets the table type ","type:text|type:data"],
			["pre","Preformats the entire table, or selected columns","pre|pre:html"],
			["caption","Adds a caption to the table","caption:Example table"],
			["id","Adds an id to the table","id:figures"],
			["class","Sets the table class attribute","class:fancy"],
			["style","Sets the table style attribute","style:transform:rotate(-5deg)"],
			["width","Sets the width of the table","width:700|width:100%"],
		]);

		foreach ($data as $index => $value)
		{
			$example = $data[$index]['example'];
			$data[$index]['example'] = implode(' ', array_map(function($value)
			{
				$options = str_replace('%', '%25', $value);
				return "<a href='?options=$options'><code>$value</code></a>";
			}, explode('|', $example)));
		}

		return view('sketchpad::help/helpers/table', compact('data', 'preview', 'options'));
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