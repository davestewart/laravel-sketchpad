@extends('sketchpad::setup.layout')

@section('content')

	<h1>Back again?</h1>

	<h2>It looks like something didn't go as planned...</h2>

	<h3 class="text-danger">Sketchpad didn't find any controllers</h3>
	<p>In a normal installation, Laravel would copy the correct assets from the package folder to the correct installation folders, and you wouldn't see this page.</p>

	<h4>Missing files</h4>
	<p>Did you accidentally move or delete the controllers?</p>
	<p>Check your configured Sketchpad controllers folder to see what's there:</p>
	<ul>
		<li><code>{{ $config->path }}</code></li>
	</ul>


	<h4>Bad config</h4>
	<p>Did you accidentally edit your config to point towards a folder where there are no controllers?</p>
	<p>Take a look at your config file and see where the <code>path</code> variable points to:</p>

	<ul>
		<li><code>{{ str_replace(base_path() . '/', '', config_path('sketchpad.php')) }}</code></li>
	</ul>

	<h4>Permissions</h4>
	<p>It may be that you have a permissions problems with the destination folders, so make sure the following (configured) folders are writable:</p>

	<ul>
		<li><code>{{ $config->path }}</code></li>
		<li><code>{{ str_replace(base_path() . '/', '', public_path($config->assets)) }}</code></li>
	</ul>


	<h4>If all else fails...</h4>
	<p>You can force a re-installation of the example controllers with the following terminal command:</p>
	<pre>cd "{{ base_path() }}"
php artisan vendor:publish --provider="davestewart\sketchpad\SketchpadServiceProvider" --tag="examples" --force</pre>


	<p>You can force a completely new (default) installation with the following terminal command:</p>
	<pre>cd "{{ base_path() }}"
php artisan vendor:publish --provider="davestewart\sketchpad\SketchpadServiceProvider" --force</pre>

@endsection

