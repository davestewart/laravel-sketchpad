<?php

if( ! function_exists('p') )
{
	function p($value, $bold = false)
	{
		\davestewart\sketchpad\utils\Html::p($value, $bold);
	}
}

if( ! function_exists('alert') )
{
	function alert($html, $class = 'info')
	{
		\davestewart\sketchpad\utils\Html::alert($html, $class);
	}
}

if( ! function_exists('pr') )
{
	function pr()
	{
		call_user_func_array([\davestewart\sketchpad\utils\Html::class, 'pr'], func_get_args());
	}
}

if( ! function_exists('pd') )
{
	function pd()
	{
		call_user_func_array([\davestewart\sketchpad\utils\Html::class, 'pd'], func_get_args());
	}
}

if( ! function_exists('vd') )
{
	function vd($value)
	{
		\davestewart\sketchpad\utils\Html::vd($value);
	}
}

if( ! function_exists('ls') )
{
	function ls($values, $options = '')
	{
		\davestewart\sketchpad\utils\Html::ls($values, $options);
	}
}

if( ! function_exists('tb') )
{
	function tb($values, $options = '')
	{
		\davestewart\sketchpad\utils\Html::tb($values, $options);
	}
}


if( ! function_exists('vue') )
{
	function vue($path, array $data = null)
	{
		return \davestewart\sketchpad\utils\Html::vue($path, $data);
	}
}

if( ! function_exists('md') )
{
	function md($path)
	{
		return \davestewart\sketchpad\utils\Html::md($path);
	}
}

