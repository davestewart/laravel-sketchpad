<template>

	<article>
		<header>
			<h1>Favourites</h1>
		</header>
		<section id="favourites">
			<favourites></favourites>
		</section>
	</article>

</template>

<script>

import settings     from '../../js/state/settings'
import Favourites   from '../navigation/Favourites.vue'

export default
{
	components:
	{
		Favourites
	},

	route:
	{
		canActivate()
		{
			return !!settings.site.favourites;
		}
	}
}

</script>

<style lang="scss">
	
</style>