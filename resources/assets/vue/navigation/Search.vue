<template>

	<div id="search">
		<input class="form-control" v-model="value" placeholder="Type to filter...">
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

	props: ['term'],

	data ()
	{
		return {
			filter: method =>
			{
				const term = (this.term || '').toLowerCase().replace(/^\s*|\s$/g, '');
				if (term === '*') return true;
				if (term === '') return false;
				return (method.label + method.comment.intro)
					.toLowerCase()
					.indexOf(this.term) > -1
			}
		}
	},

	computed:
	{
		value:
		{
			get ()
			{
				return this.term || '';
			},
			set (value)
			{
				this.term = value;
			}
		}
	},

	ready ()
	{
		$('#search').find('input').get(0).focus();
	},

	watch:
	{
		value (value)
		{
			router.replace('/search?term=' + value);
		}
	}

}

</script>

<style lang="scss">
	
</style>