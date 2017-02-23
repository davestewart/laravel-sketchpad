<template>

	<article>
		<header>
			<h1>Favourites</h1>
		</header>
		<section id="favourites">
			<controllers :filter="filter"></controllers>
		</section>
	</article>

</template>

<script>

import Controllers  from './components/ControllerList.vue'

export default
{
	components:
	{
		Controllers
	},

	data ()
	{
		return {
			filter: method => method.tags.favourite
		}
	}

}

</script>

<style lang="scss">
	
</style>