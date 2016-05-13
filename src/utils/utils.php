<?php

use Illuminate\Contracts\Support\Arrayable;

if( ! function_exists('tb') )
{
	function tb($values, $pre = false)
	{
		$data =
		[
			'values'    => $values instanceof Arrayable
							? $values->toArray()
							: (array) $values,
			'keys'      => array_keys( (array) $values[0]),
			'class'     => $pre ? 'pre' : 'table-striped',
		];
		echo view('sketchpad::utils.table', $data);
	}
}

if( ! function_exists('ls') )
{
	function ls($values, $pre = false)
	{
		$data =
		[
			'values' => $values,
			'class' => $pre ? 'pre' : 'table-striped',
		];
		echo view('sketchpad::utils.list', $data);
	}
}

if( ! function_exists('pr') )
{
	function pr()
	{
		echo '<pre style="font-size:11px">';
		$args = func_get_args();
		print_r( count($args) === 1 ? $args[0] : $args);
		echo '</pre>';
	}
}

if( ! function_exists('pd') )
{
	function pd()
	{
		call_user_func_array('pr', func_get_args());
		exit;
	}
}

if( ! function_exists('vd') )
{
	function vd($value)
	{
		var_dump($value);
	}
}

if( ! function_exists('p') )
{
	function p($value)
	{
		echo "<p>$value</p>";
	}
}

