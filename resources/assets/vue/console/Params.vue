<template>
	<div id="params" :show="method && method.name !== 'index'">
		<div class="sticky">

			<nav v-show="method && method.name !== 'index'" class="navbar navbar-default">
				<span class="loader"></span>
				<ul class="nav navbar-nav">
					<li><button @click="load()" class="btn btn-default btn-xs">Run</button></li>
					<param v-for="param in params" :param="param"></param>
				</ul>
			</nav>

		</div>
	</div>

</template>

<script>

import Param		from './Param.vue';

export default
{
	name: 'Params',

	props: ['defer', 'method', 'params'],

	components:
	{
		Param
	},

	methods:
	{
		load ()
		{
			this.$emit('load')
		}
	}

}


</script>

<style lang="scss">

</style>