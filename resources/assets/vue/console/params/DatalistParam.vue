<template>
	<span class="field">
		<span class="sizer">{{{ value }}}</span>
		<input
			type="text"
			:list="id"
			v-model="value"
			v-field-attrs="param.attrs"
			@input="setSize()"
			@change="setSize()"
			@keyup.enter="$emit('run')"
		>
		<datalist :id="id">
			<option v-for="(text, value) in options" :value="value">
		</datalist>
	</span>
</template>

<script>

import param        from '../../mixins/param';
import options      from '../../mixins/param-options';
import autosize     from '../../mixins/param-autosize';

export default
{
	name: 'DatalistParam',

	mixins: [param, options, autosize]

}

</script>

<style lang="scss">

</style>