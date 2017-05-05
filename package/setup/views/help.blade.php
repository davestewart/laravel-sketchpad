<?php
	// we don't have a controller, so set any variables here
	$config = app(\davestewart\sketchpad\config\SketchpadConfig::class);
?>
<header>
	<h1>Help</h1>
</header>
<section>
	<h3>Hello</h3>
	<p>This is your custom help page, located at <code>{{ $config->views . 'help.blade.php' }}</code>.</p>
	<p>Edit it to suit your needs!</p>
</section>
