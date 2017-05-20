<?php

if( ! function_exists('p') )
{
	function p($value, $class = null)
	{
		echo \davestewart\sketchpad\utils\Html::p($value, $class);
	}
}

if( ! function_exists('text') )
{
	function text($text)
	{
		echo \davestewart\sketchpad\utils\Html::text($text);
	}
}

if( ! function_exists('code') )
{
	function code()
	{
		echo call_user_func_array('\davestewart\sketchpad\utils\Html::code', func_get_args());
	}
}

if( ! function_exists('alert') )
{
	function alert($html, $class = 'info', $icon = '')
	{
		echo \davestewart\sketchpad\utils\Html::alert($html, $class, $icon);
	}
}

if( ! function_exists('icon') )
{
	function icon($name, $color = '')
	{
		return \davestewart\sketchpad\utils\Html::icon($name, $color);
	}
}

if( ! function_exists('pr') )
{
	function pr()
	{
		echo call_user_func_array([\davestewart\sketchpad\utils\Html::class, 'pr'], func_get_args());
	}
}

if( ! function_exists('pd') )
{
	function pd()
	{
		echo call_user_func_array([\davestewart\sketchpad\utils\Html::class, 'pr'], func_get_args());
		exit;
	}
}

if( ! function_exists('vd') )
{
	function vd($value)
	{
		echo \davestewart\sketchpad\utils\Html::vd($value);
	}
}

if( ! function_exists('ls') )
{
	function ls($values, $options = '')
	{
		echo \davestewart\sketchpad\utils\Html::ls($values, $options);
	}
}

if( ! function_exists('tb') )
{
	function tb($values, $options = '')
	{
		echo \davestewart\sketchpad\utils\Html::tb($values, $options);
	}
}


if( ! function_exists('json') )
{
	function json($data)
	{
		echo \davestewart\sketchpad\utils\Html::json($data);
	}
}

if( ! function_exists('md') )
{
	function md($path, $data = null)
	{
		echo \davestewart\sketchpad\utils\Html::md($path, $data);
	}
}

if( ! function_exists('vue') )
{
	function vue($path, array $data = null)
	{
		echo \davestewart\sketchpad\utils\Html::vue($path, $data);
	}
}

