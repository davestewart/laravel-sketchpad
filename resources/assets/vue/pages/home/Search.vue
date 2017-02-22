<template>

	<article>
		<header>
			<h1>Search</h1>
			<input class="form-control" v-model="term">
		</header>
		<section id="favourites">
			<controllers :filter="filter"></controllers>
		</section>
	</article>

</template>

<script>

import Controllers  from '../../components/ControllerList.vue'

export default
{
	components:
	{
		Controllers
	},

	data ()
	{
		return {
			term: '',
			filter: method =>
			{
				const term = this.term.toLowerCase().replace(/^\s*|\s$/g, '');
				if (term === '*') return true;
				if (term === '') return false;
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