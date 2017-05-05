<template>
	<span class="field">
		<span class="sizer">{{{ val }}}</span>
		<input
	       type="number"
	       :id="id"
	       v-model="value"
	       v-field-attrs="param.attrs"
	       @input="setSize()"
	       @change="setSize()"
	       @keyup.enter="$emit('run')"
		>
	</span>

</template>

<script>

import param        from '../../mixins/param';
import autosize     from '../../mixins/param-autosize';

export default
{
	name: 'NumberParam',

	mixins: [param, autosize],

	computed:
	{
		value:
		{
			get ()
			{
				return Number(this.param.value);
			},
			set (value)
			{
				this.val = value;
				this.update(value);
			}
		}
	}

}

</script>
<style lang="scss">

</style>