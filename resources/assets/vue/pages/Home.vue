<template>

	<component :is="home" type="home"></component>

</template>

<script>

import settings     from '../../js/state/settings'
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
		Welcome, Custom
	}
}

</script>

<style lang="scss">

</style>