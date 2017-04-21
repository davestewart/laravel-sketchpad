<template>

	<component :is="homepage" keep-alive></component>

</template>

<script>

import settings     from '../../js/state/settings'
import Favourites   from '../pages/Favourites.vue'
import Search       from '../pages/Search.vue'
import Welcome      from './Welcome.vue'
import Custom       from './CustomHome.vue'

export default
{
	name: 'Home',

	data ()
	{
		return {homepage: settings.ui.homepage}
	},

	components:
	{
		Welcome, Favourites, Search, Custom
	}
}

</script>

<style lang="scss">

</style>