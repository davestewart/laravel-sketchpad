<template>
	<div id="x-frame">
		<form v-el:form :action="action" method="post" target="iframe">
			<input type="hidden" name="_token" :value="csrf">
			<input
				v-for="field in fields"
				type="hidden"
				:name="field.name"
				:value="field.value">
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
			fields: []
		}
	},

	methods:
	{
		load (method)
		{
			// variables
			const keys = ['name', 'type', 'value'];
			const fields = [];

			// build fields array
			method.params
				.forEach((param, index) => {
					keys.forEach(key => {
						const name = 'data[' +index+ '][' +key+ ']';
						const value = param[key];
						fields.push({name:name, value:value});
					})
				});

			// assign values
			this.action = server.getRunUrl(method);
			this.fields = fields

			// load iframe
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