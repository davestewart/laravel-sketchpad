<template>

	<article>

		<custom v-if="settings.site.help === 'custom'" type="help"></custom>
		<template v-else>
			<header>
				<h1>Help</h1>
			</header>
			<section class="content">

				<template v-if="admin.settings">
					<h3>Setup</h3>
					<p>To complete your Sketchpad setup, consider:</p>
					<ul>
						<li>Set up <a href="run/help/docs/setup/livereload">Sketchpad Reload</a> to update the UI as you save, add or delete files</li>
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
					<li>Helper functions such as <a href="run/help/docs/helpers/ls">list</a>, <a href="run/help/docs/helpers/table">table</a> and <a href="run/help/docs/helpers/alert">alert</a></li>
					<li>Support for <a href="run/help/docs/output/json">json</a>, <a href="run/help/docs/output/markdown">markdown</a> and <a href="run/help/docs/output/vue">vue</a></li>
					<li>Transparent <a href="run/help/docs/output/pagination">pagination</a> and <a href="run/help/docs/output/forms">form submission</a></li>
					<li>Built-in <a href="run/help/docs/output/formatting">formatting</a> and <a href="run/help/docs/methods/exceptions">exception handling</a></li>
					<li>Custom <a href="run/help/docs/setup/customisation">home page</a>, <a href="run/help/docs/methods/indexpage">index pages</a> and <a href="run/help/docs/setup/assets">assets</a></li>
					<li>Custom <a href="run/help/docs/tags">tags</a> to modify presentation and behaviour</li>
				</ul>

				<p>Sketchpad also comes with some example <a href="run/help/demo/tools">tools</a> to show you the kinds of things you could develop.</p>

				<h3>More...</h3>
				<p>See the <a target="_help" href="http://github.com/davestewart/laravel-sketchpad/wiki">Sketchpad Wiki</a> for more information.</p>

			</section>
		</template>
	</article>

</template>

<script>

import admin from '../../js/state/admin';
import settings from '../../js/state/settings';
import Custom from './Custom.vue';

export default
{
	name: 'Help',

	components: {Custom },

	data () {
		return { settings, admin }
	},

	route:
	{
		canActivate (transition)
		{
			if (settings.site.help === 'none')
			{
				transition.abort();
			}
		}
	}
}

</script>

<style lang="scss">
	
</style>