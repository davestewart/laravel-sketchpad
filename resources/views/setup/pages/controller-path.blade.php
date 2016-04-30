@extends('sketchpad::setup.layout')

@section('content')

	<h1>Hello again...</h1>

	<h2>We're nearly there with the installation</h2>

	<h3 class="text-danger">Sketchpad couldn't find your Sketchpad controller folder</h3>

	<h4>Installation</h4>

	<p>If you've not finished the installation procedure complete it now by running the following terminal command, then refresh the page:</p>

	<pre>cd "{{ base_path() }}"
php artisan vendor:publish --provider="davestewart\sketchpad\SketchpadServiceProvider"</pre>

	<h4>Not installing?</h4>

	<p>If you're seeing this page and you're not installing, it means you don't have any controllers set up.</p>

	<p>The controller folder is listed in your config file as:</p>
	<pre>{{ $config->path }}</pre>

	<p>Check your paths and config file, make any fixes you need to, then refresh the page.</p>

@endsection


