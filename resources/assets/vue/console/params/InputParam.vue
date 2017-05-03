<template>
	<span class="field">
		<input
	       :type="type"
	       :id="id"
	       v-model="value"
	       v-field-attrs="param.attrs"
	       @keyup.enter="$emit('run')"
		>
	</span>

</template>

<script>

import param        from '../../mixins/param';

export default
{
	name: 'InputParam',

	mixins: [param],

	computed:
	{
		type ()
		{
			return this.param.field || this.param.type;
		}
	}
}

</script>

<style lang="scss">

</style>