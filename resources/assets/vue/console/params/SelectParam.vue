<template>
	<span class="field">
		<select
	        :id="id"
	        v-model="value"
	        class="form-control"
		>
			<option v-for="(text, value) in options" :value="value">{{ text }} &nbsp;&nbsp;</option>
		</select>
	</span>
</template>

<script>

import param        from '../../mixins/param';
import options      from '../../mixins/param-options';

export default
{
	name: 'SelectParam',

	mixins: [param, options],

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