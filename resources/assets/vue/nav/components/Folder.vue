<template>

	<li class="folder">
		{{{ getLabel() }}}
	</li>

</template>

<script>

export default
{
	props: ['route'],

	methods:
	{
		getLabel ()
		{
			var open 	= '<span class="name">';
			var divider	= '<span class="divider">&#9656;</span> ';
			var close	= '</span> ';

			return open + this.route
				.replace(/\/$/, '')
				.split('/')
				.join(close + divider + open) + close;
		}

	}

}

</script>

<style lang="scss">
	
</style>