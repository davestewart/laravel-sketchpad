<template>

	<article>
		{{{ content }}}
	</article>

</template>

<script>

import settings from '../../js/state/settings'
import watcher  from '../../js/services/watcher';
import server   from '../../js/services/server';

export default
{
	name: 'Custom',

	props: ['type'],

	data ()
	{
		const template = document.getElementById('page-' + this.type);
		return {
			template: template,
			content: template.innerText
		}
	},

	ready ()
	{
		watcher.addHandler(this.onFileChange, file => {
			return file === settings.paths.views + this.type + '.blade.php';
		});
	},

	destroyed ()
	{
		watcher.removeHandler(this.onFileChange);
	},

	methods:
	{
		onFileChange ()
		{
			server.load('api/page/' + this.type, data => {
				this.template.innerText = data;
				this.content = data
			});
		}
	}

}

</script>

<style lang="scss">
	
</style>