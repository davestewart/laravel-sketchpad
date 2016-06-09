<h1>Help</h1>

<h3>Usage</h3>
<p>The menus on the left show the contents of your configured controllers folders, and the methods within.</p>
<ul>
	<li>To execute a method, navigate to the controller, then the method</li>
	<li>Once loaded you can update the method's parameters by using the on-screen controls</li>
</ul>



<h3>Features</h3>
<p>Sketchpad provides a variety of features and functionality to make testing code easier, such as:</p>
<ul>
	<li>Output functions such as <a href="/sketchpad/demo/output/ls/">list</a>, <a href="/sketchpad/demo/output/table/">table</a> and <a href="/sketchpad/demo/output/alert/">alert</a></li>
	<li>Support for <a href="/sketchpad/demo/output/json/">json</a>, <a href="/sketchpad/demo/output/markdown/">markdown</a> and <a href="/sketchpad/demo/output/vue/">vue</a></li>
	<li>Built-in <a href="/sketchpad/demo/basics/exception/">exception handling</a></li>
	<li>Custom <a href="/sketchpad/demo/basics/indexpage/">index pages</a>, <a href="/sketchpad/demo/basics/usercss/">custom CSS</a> and transparent ajax <a href="/sketchpad/demo/basics/forms/">form submission</a></li>
	<li>Custom <a href="/sketchpad/demo/tags/">tags</a> to modify presentation and behaviour</li>
</ul>



<h3>Set-up</h3>
<p>To add your own controllers and methods, create a normal Laravel controller file, in one of your configured controllers folders:</p>
<ul>
	@foreach(config('sketchpad.paths') as $name => $folder)
		@if( ! strstr($folder, 'davestewart/sketchpad') !== false )
		<li><code>{{ $folder }}</code></li>
		@endif
	@endforeach
</ul>

<p>It should extend a standard Illuminate Controller, and the filename must end in <code>Controller.php</code>.</p>
<ul>
	<li>Add <strong>public</strong> methods for each task you wish to run</li>
	<li>Add <strong>optional parameters</strong> to methods to gain access to on-screen controls</li>
	<li>Feel free to type-hint arguments to take advantage of Laravel's <strong>dependency injection</strong></li>
	<li>The first line of any DocBlock will show as the <strong>introduction</strong></li>
	<li>Controllers may be placed in <strong>subfolders</strong> if you wish</li>
</ul>


<h3>Live Reload</h3>
<p>To have controllers, methods, and code reload <strong>live</strong> in the browser, set up your Gulp file using <code>gulp-watch</code> to watch any folders that contain PHP files you want to monitor for changes:</p>
<pre lang="javascript">
var livereload	= require('gulp-livereload');
gulp.task('default', function(){
    livereload({start: true});
    gulp.watch(['sketchpad/**/*', 'app/**/*']).on('change', livereload.changed);
}
</pre>


<h3>Configuration</h3>
<p>The configuration settings for Sketchpad are stored in <code>config/sketchpad.php</code>.</p>
<p>Generally, you can leave them alone, but you may:</p>
<ul>
	<li>Add or remove controller paths, the contents of which will display on the left</li>
	<li>Add middleware, which will be run before loading any sketchpad route</li>
</ul>


<h3>More help</h3>
<p>Take a look at the <a href="/sketchpad/demo/basics/">demo</a> controllers to see all features running in the browser.</p>
<p>Check controller source code in <code>vendor/davestewart/sketchpad/src/demo/</code> to see how it's done.</p>
