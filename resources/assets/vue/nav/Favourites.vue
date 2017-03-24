<template>

	<div id="favourites">
		<controller-list :filter="filter"></controller-list>
	</div>

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