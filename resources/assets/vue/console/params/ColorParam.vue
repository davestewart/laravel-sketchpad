<template>
	<span class="field">
		<input
	       type="color"
	       :value="value"
	       :id="id"
	       v-field-attrs="param.attrs"
		   @change="onChange"
		>
	</span>

</template>

<script>

import _        from 'underscore';
import param    from '../../mixins/param';

export default
{
	name: 'ColorParam',

	mixins: [param],

	data ()
	{
		return {
			debounce: 200,
		}
	},

	computed:
	{
		value ()
		{
			return '#' + this.param.value;
		}
	},

	methods:
	{
		onChange($event)
		{
			const value = $event.target.value;
			this.update(String(value || '').replace('#', ''));
		}
	}
}

</script>

<style lang="scss">

</style>