<template>

	<article>
		<h1>Help</h1>

		<h3>Setup</h3>
		<p>To properly configure your Sketchpad setup, consider:</p>
		<ul>
			<li>Using LiveReload or BrowserSync to <a target="_help" href="http://github.com/davestewart/laravel-sketchpad/wiki/livereload">live-update</a> the UI when you save</li>
			<li>Configuring folders and Git to make <a target="_help" href="http://github.com/davestewart/laravel-sketchpad/wiki/teams">working in teams</a> easier</li>
			<li>Adding additional <a target="_help" href="http://github.com/davestewart/laravel-sketchpad/wiki/config">controllers folders</a></li>
		</ul>

		<h3>Usage</h3>
		<p>The menus on the left show the contents of your configured controllers folders, and the methods within.</p>
		<ul>
			<li>To execute a method, navigate to the controller, then the method</li>
			<li>Once loaded you can update the method's parameters by using the on-screen controls</li>
		</ul>

		<h3>Features</h3>
		<p>Sketchpad provides a variety of features and functionality to make testing code easier, such as:</p>
		<ul>
			<li>Output functions such as <a href="/sketchpad/demo/output/ls">list</a>, <a href="/sketchpad/demo/output/table">table</a> and <a href="/sketchpad/demo/output/alert">alert</a></li>
			<li>Support for <a href="/sketchpad/demo/output/json">json</a>, <a href="/sketchpad/demo/output/markdown">markdown</a> and <a href="/sketchpad/demo/output/vue">vue</a></li>
			<li>Built-in <a href="/sketchpad/demo/basics/exception">exception handling</a></li>
			<li>Custom <a href="/sketchpad/demo/basics/indexpage">index pages</a>, <a href="/sketchpad/demo/basics/userassets">custom CSS</a> and transparent ajax <a href="/sketchpad/demo/basics/forms">form submission</a></li>
			<li>Custom <a href="/sketchpad/demo/tags/label">tags</a> to modify presentation and behaviour</li>
		</ul>


		<h3>Learn by example</h3>
		<p>The demo code which runs when you click the links on the left is all written in, and runs from standard Laravel controllers.
			You can check out the code at <code>vendor/davestewart/sketchpad/src/demo/</code>.</p>

		<h3>More...</h3>
		<p>See the <a href="http://github.com/davestewart/laravel-sketchpad/wiki">Sketchpad Wiki</a> for more information.</p>
	</article>

</template>

<script>
	
export default
{

}

</script>

<style lang="scss">
	
</style>