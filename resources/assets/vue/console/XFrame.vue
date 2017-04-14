<template>
	<div id="x-frame">
		<form v-el:form :action="action" method="post" target="iframe">
			<input type="hidden" name="_token" :value="csrf">
			<input type="hidden" name="data" :value="params">
		</form>
		<iframe v-el:iframe id="iframe" name="iframe" src="" frameborder="0"></iframe>
	</div>
</template>

<script>

import server from '../../js/services/server'

export default
{
	name: 'XFrame',

	data ()
	{
		return {
			csrf: $('meta[name="csrf-token"]').attr('content'),
			action: '',
			params: ''
		}
	},

	methods:
	{
		load (method)
		{
			this.action = server.getRunUrl(method);
			this.params = server.getData(method);
			this.$nextTick(() => this.$els.form.submit());
		},

		clear ()
		{
			this.$els.iframe.src = ''
		}
	}
}

</script>

<style lang="scss">

</style>