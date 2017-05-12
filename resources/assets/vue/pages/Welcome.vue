<template>

	<article>
		<header>
			<h1>Sketchpad</h1>
		</header>
		<section class="content">

			<h3>Introduction</h3>
			<p>Sketchpad is an innovative live-coding, development and admin environment for your existing Laravel site.
			<p>It's a place to write, test, experiment and execute code, or just a place to group useful tools and functions you want easy access to.</p>

			<iframe style="padding:20px;" width="560" height="333" src="https://www.youtube.com/embed/dQ-OL8d5uak?rel=0&showinfo=0" frameborder="0" allowfullscreen></iframe>

			<h3>Overview</h3>
			<p>Sketchpad provides an alternative structure of controllers, views, assets and routing that lives alongside your main Laravel installation.</p>
			<p>You can navigate methods, interactively modify parameters, and even <a href="run/help/docs/setup/livereload">live-reload</a> the code you're working on, all from a friendly UI.</p>
			<p>Additional tooling and functionality is designed to make it quick and easy to develop or debug new tools, features and code.</p>

			<h3>Next steps&hellip;</h3>
			<p>Navigate the menu on the left, or view the <a v-link="'/help'">help</a> for more info.</p>

		</section>
	</article>

</template>

<script>
	
export default
{
	name: 'Welcome'
}

</script>

<style lang="scss">
	
</style>