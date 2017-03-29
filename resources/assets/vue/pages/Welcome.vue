<template>

	<article>
		<header id="header">
			<h1>Sketchpad</h1>
		</header>
		<section class="content">

			<h3>What it is</h3>
			<p>Sketchpad is an interactive front-end for your back-end; a place to write, test, experiment and execute code, or just a place to group useful tools and functions you want easy access to.</p>
			<iframe style="padding:20px;" width="560" height="333" src="https://www.youtube.com/embed/jahhWFd-M_A" frameborder="0" allowfullscreen></iframe>

			<h3>What it does</h3>
			<p>It lists controllers and methods from folders of your choice, and allows you to run them without setting up routing, views, etc.</p>
			<p>You can navigate to, and run any controller method, even modify parameter values, all from a friendly UI.</p>
			<p>It comes with a bunch of useful tools and PHP functions to make it easy to rattle out quick coding tests.</p>

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