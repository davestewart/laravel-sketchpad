<template>

	<component :is="homepage"></component>

</template>

<script>

import settings     from '../../js/state/settings'
import Welcome      from './home/Welcome.vue'
import Favourites   from './home/Favourites.vue'
import Search       from './home/Search.vue'

export default
{
	data ()
	{
		return {homepage: settings.ui.homepage}
	},

	components:
	{
		Welcome, Favourites, Search
	}
}

</script>

<style lang="scss">

</style>