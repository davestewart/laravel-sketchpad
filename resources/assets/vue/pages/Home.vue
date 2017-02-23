<template>

	<component :is="homepage"></component>

</template>

<script>

import settings     from '../../js/state/settings'
import Favourites   from '../nav/Favourites.vue'
import Search       from '../nav/Search.vue'
import Welcome      from './Welcome.vue'

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