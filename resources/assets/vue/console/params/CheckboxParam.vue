<template>
	<span class="field">
		<input
	       :id="id"
	       type="checkbox"
	       v-model="value"
	       v-field-attrs="param.attrs"
		>
	</span>
</template>

<script>

import param        from '../../mixins/param';

export default
{
	name: 'CheckboxParam',

	mixins: [param],

	data ()
	{
		return {
			debounce: 0
		}
	}

}

</script>

<style lang="scss">

</style>