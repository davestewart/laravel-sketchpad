@extends('sketchpad::setup.layout')

@section('content')

	<h1>Howdy...</h1>

	<h2>Welcome to the Sketchpad installer</h2>

	<h3 class="text-danger">Sketchpad couldn't find a configuration file</h3>
	<p>That suggests this is a new install, so <span class="text-warning">you'll need to decide how to proceed</span>, depending on:</p>

	<ul>
		<li>your Laravel project setup</li>
		<li>where you want files installed</li>
	</ul>
	<p>Look at the following two setup types, and follow the instructions for the one that suits you.</p>

	<hr>

	<h4>Standard</h4>
	<p>Choose this setup if:</p>
	<ul>
		<li>You haven't changed your controllers folder or your app namespace from the defaults</li>
		<li>You're happy for Sketchpad to install files to your <code>public/vendor/sketchpad/</code> folder</li>
		<li>You're happy to run Sketchpad from the <code>sketchpad/</code> route in your browser</li>
	</ul>

	<p>If that floats your boat, then run the following terminal command, and reload this page:</p>
	<pre>cd "{{ base_path() }}"
php artisan vendor:publish --provider="davestewart\sketchpad\SketchpadServiceProvider"</pre>

	<hr>

	<h4>Custom</h4>
	<p>If any of the above isn't true, you'll need to do the installation in 2 parts:</p>
	<ul>
		<li>Part 1: publish and edit the config file</li>
		<li>Part 2: publish asset and example files</li>
	</ul>

	<p>If this setup is for you, run the following terminal command from your project root:</p>
	<pre>cd "{{ base_path() }}"
php artisan vendor:publish --provider="davestewart\sketchpad\SketchpadServiceProvider" --tag="config"</pre>

	<p>Then, edit the following (newly-created) config file, <span class="text-info">paying attention to the comments within</span>:</p>
	<pre>{{ config_path('sketchpad.php') }}</pre>

	<p>Finally, when happy with your edits, refresh this page.</p>

@endsection

