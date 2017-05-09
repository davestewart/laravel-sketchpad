<template>

	<article>
		<header>
			<h1>Search</h1>
		</header>
		<section id="search">
			<search :term="term"></search>
		</section>
	</article>

</template>

<script>

import settings     from '../../js/state/settings'
import Search       from '../navigation/Search.vue'

export default
{
	components:
	{
		Search
	},

	data ()
	{
		return {
			term: ''
		}
	},

	route:
	{
		canActivate()
		{
			return !!settings.site.search;
		},

		data (transition)
		{
			this.term = this.$route.query.term;
			transition.next();
		}
	}

}

</script>

<style lang="scss">
	
</style>