<template>

	<article>
		{{{ content }}}
	</article>

</template>

<script>

import server from '../../js/services/server';

export default
{
	name: 'Welcome',

	data ()
	{
		return {content: document.getElementById('home').innerText}
	}
}

</script>

<style lang="scss">
	
</style>