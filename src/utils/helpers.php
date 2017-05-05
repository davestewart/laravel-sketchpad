<?php

if( ! function_exists('p') )
{
	function p($value, $class = null)
	{
		\davestewart\sketchpad\utils\Html::p($value, $class);
	}
}

if( ! function_exists('text') )
{
	function text($text)
	{
		\davestewart\sketchpad\utils\Html::text($text);
	}
}

if( ! function_exists('code') )
{
	function code($text, $format = 'php')
	{
		\davestewart\sketchpad\utils\Html::code($text, $format);
	}
}

if( ! function_exists('alert') )
{
	function alert($html, $class = 'info', $icon = '')
	{
		\davestewart\sketchpad\utils\Html::alert($html, $class, $icon);
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


if( ! function_exists('json') )
{
	function json($data)
	{
		return \davestewart\sketchpad\utils\Html::json($data);
	}
}

if( ! function_exists('md') )
{
	function md($path, $data = null)
	{
		return \davestewart\sketchpad\utils\Html::md($path, $data);
	}
}

if( ! function_exists('vue') )
{
	function vue($path, array $data = null)
	{
		return \davestewart\sketchpad\utils\Html::vue($path, $data);
	}
}

