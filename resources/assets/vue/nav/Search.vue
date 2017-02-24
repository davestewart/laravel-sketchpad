<template>

	<article>
		<header>
			<h1>Search</h1>
			<input class="form-control" v-model="term" placeholder="Start typing to filter">
		</header>
		<section id="favourites">
			<controller-list :filter="filter"></controller-list>
		</section>
	</article>

</template>

<script>

import ControllerList  from './components/ControllerList.vue'

export default
{
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