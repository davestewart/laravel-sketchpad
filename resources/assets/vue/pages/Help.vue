<template>

	<article>
		<header>
			<h1>Help</h1>
		</header>
		<section class="content">

			<template v-if="admin.settings">
				<h3>Setup</h3>
				<p>To complete your Sketchpad setup, consider:</p>
				<ul>
					<li>Installing <a target="_help" href="https://github.com/davestewart/laravel-sketchpad-reload">Sketchpad Reload</a> to update the UI as you save, add or delete files</li>
					<li>Giving the <a href="settings">settings page</a> a once-over</li>
				</ul>
			</template>

			<h3>Usage</h3>
			<p>The menus on the left show the contents of your configured controllers folders, and the methods within.</p>
			<ul>
				<li>To execute a method, navigate to the controller, then the method</li>
				<li>Once loaded you can update the method's parameters by using the on-screen controls</li>
			</ul>


			<h3>Features</h3>
			<p>Sketchpad provides a variety of features and functionality to make developing new functionality easier, such as:</p>
			<ul>
				<li>Output functions such as <a href="run/help/docs/output/ls">list</a>, <a href="run/help/docs/output/table">table</a> and <a href="run/help/docs/output/alert">alert</a></li>
				<li>Support for <a href="run/help/docs/output/json">json</a>, <a href="run/help/docs/output/markdown">markdown</a> and <a href="run/help/docs/output/vue">vue</a></li>
				<li>Built-in <a href="run/help/docs/basics/exceptions">exception handling</a></li>
				<li>Custom <a href="run/help/docs/basics/indexpage">index pages</a>, <a href="run/help/docs/basics/userassets">custom CSS</a> and transparent ajax <a href="run/help/docs/basics/forms">form submission</a></li>
				<li>Custom <a href="run/help/docs/tags/label">tags</a> to modify presentation and behaviour</li>
			</ul>

			<p>Sketchpad also comes with some example <a href="run/help/demo/tools">tools</a> to show you the kinds of things you could develop.</p>

			<h3>More...</h3>
			<p>See the <a target="_help" href="http://github.com/davestewart/laravel-sketchpad/wiki">Sketchpad Wiki</a> for more information.</p>

		</section>
	</article>

</template>

<script>

import admin from '../../js/state/admin';

export default
{
	name: 'Help',
	data () {
		return { admin }
	}
}

</script>

<style lang="scss">
	
</style>