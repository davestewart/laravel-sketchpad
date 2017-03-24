<template>

	<div id="nav">
		<component :is="mode"></component>
	</div>

</template>

<script>

// state
import Search 	    from './Search.vue';
import Browser 	    from './Browser.vue';
import Favourites 	from './Favourites.vue';

export default
{
	name:'Navigation',

	data ()
	{
		return {
			mode: 'browser'
		}
	},

	components:
	{
		Search,
		Browser,
		Favourites
	}
}

</script>

<style lang="scss">
	
</style>