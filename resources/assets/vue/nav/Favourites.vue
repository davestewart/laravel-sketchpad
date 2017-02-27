<template>

	<article>
		<header id="header">
			<h1>Favourites</h1>
		</header>
		<section id="favourites">
			<controller-list :filter="filter"></controller-list>
		</section>
	</article>

</template>

<script>

import ControllerList  from './components/ControllerList.vue'

export default
{
	name: 'Favourites',

	components:
	{
		ControllerList
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