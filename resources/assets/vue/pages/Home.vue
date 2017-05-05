<template>

	<component :is="home" type="home" keep-alive></component>

</template>

<script>

import settings     from '../../js/state/settings'
import Favourites   from '../pages/Favourites.vue'
import Search       from '../pages/Search.vue'
import Welcome      from './Welcome.vue'
import Custom       from './Custom.vue'

export default
{
	name: 'Home',

	data ()
	{
		return {home: settings.site.home}
	},

	components:
	{
		Welcome, Favourites, Search, Custom
	}
}

</script>

<style lang="scss">

</style>