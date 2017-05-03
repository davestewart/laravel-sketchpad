<template>
	<div id="params" :class="{enabled:enabled}">
		<div class="sticky">

			<nav class="navbar navbar-default">
				<span class="loader"></span>
				<ul class="nav navbar-nav">
					<li>
						<div class="btn-group">
							<button
								v-if="runIf"
								id="runState"
								:data-run="runState ? 1 : 0"
								@click="toggleRunState"
								class="btn btn-default btn-xs"><i class="fa fa-bolt" aria-hidden="true"></i></button>
							<button
								:disabled="!enabled"
								id="run"
								@click="run()"
								class="btn btn-default btn-xs">{{{ runLabel }}}</button>
						</div>
					</li>
					<li v-for="param in params">
						<label
							:for="'param-' + param.name"
							:title="param.text"
						>{{ param.name }}</label>
						<component
							:is="getParam(param)"
							:param="param"
							@run="run"
						></component>
					</li>
				</ul>
			</nav>

		</div>
	</div>

</template>

<script>

import TextParam        from './params/TextParam.vue';
import InputParam       from './params/InputParam.vue';
import ColorParam       from './params/ColorParam.vue';
import NumberParam      from './params/NumberParam.vue';
import CheckboxParam    from './params/CheckboxParam.vue';
import SelectParam      from './params/SelectParam.vue';
import DatalistParam    from './params/DatalistParam.vue';

export default
{
	name: 'Params',

	props: ['defer', 'method', 'params', 'runIf', 'runState'],

	components:
	{
		TextParam,
		InputParam,
		ColorParam,
		NumberParam,
		CheckboxParam,
		SelectParam,
		DatalistParam
	},

	computed:
	{
		enabled ()
		{
			return !!this.method;
		},

		runLabel ()
		{
			let label = 'Run';
			if(this.runIf)
			{
				label = this.runState
					? String(this.runIf).replace(/\w/, char => char.toUpperCase())
					: 'Test';
			}
			return label
		},

	},

	methods:
	{
		run ()
		{
			this.$emit('run')
		},

		toggleRunState ()
		{
			this.$emit('runState')
		},

		getParam (param)
		{
			// variables
			let field   = param.field;
			let type    = param.type;

			// field
			if(field)
			{
				if (/^(select|datalist)$/.test(field))
				{
					return field + '-param'

				}
				if (/^(text|number|color|date|datetime|month|time|week)$/.test(field))
				{
					return field === 'color'
						? 'color-param'
						: 'input-param';
				}
			}

			// checkbox
			if(/^bool/.test(type))
			{
				return 'checkbox-param';
			}

			if(type === 'number')
			{
				return 'number-param';
			}

			return 'text-param';
		}
	}

}


</script>

<style lang="scss">

</style>