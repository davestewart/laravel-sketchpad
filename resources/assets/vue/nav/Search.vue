<template>

	<div id="search">
		<input class="form-control" v-model="term" placeholder="Start typing to filter">
		<controller-list :filter="filter"></controller-list>
	</div>

</template>

<script>

import ControllerList  from './components/ControllerList.vue'

export default
{
	name: 'Search',

	components:
	{
		ControllerList
	},

	data ()
	{
		return {
			term: '',
			filter: method =>
			{
				const term = this.term.toLowerCase().replace(/^\s*|\s$/g, '');
				if (term === '') return true;
				return (method.label + method.comment.intro)
					.toLowerCase()
					.indexOf(this.term) > -1
			}
		}
	}

}

</script>

<style lang="scss">
	
</style>