<?php

if( ! function_exists('tb') )
{
	function tb($data)
	{
		echo view('sketchpad::elements.table', ['data' => $data]);
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

