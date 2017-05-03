<template>
	<span class="field">
		<span class="sizer">{{{ val }}}</span>
		<input
	       type="text"
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
	name: 'TextParam',

	mixins: [param, autosize]

}

</script>

<style lang="scss">

</style>