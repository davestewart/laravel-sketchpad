<template>
	<header>
		<h1>{{ title }}</h1>
		<div :class="{info:true, alert:alert, 'alert-danger':warn }">{{{ info | marked }}}</div>
	</header>
</template>

<script>

const marked    = require('marked')
import Helpers  from '../../js/functions/helpers.js';

export default
{
	name: 'Info',

	props: ['controller', 'method'],

	computed:
	{
		title ()
		{
			return this.method && this.method.name !== 'index'
				? Helpers.getMethodLabel(this.method)
				: this.controller
					? Helpers.getControllerLabel(this.controller)
					: 'Sketchpad';
		},

		info ()
		{
			return this.method && this.method.name !== 'index'
				? this.method.comment.intro || '&hellip;'
				: this.controller
					? this.controller.comment.intro
						? this.controller.comment.intro
						: this.controller.methods.length + ' methods'
					: '';
		},

		defer ()
		{
			if(this.method)
			{
				var tags = this.method.tags;
				return tags.defer || tags.warn;
			}
			return false;
		},

		alert ()
		{
			return this.warn;
		},

		warn ()
		{
			if(this.method)
			{
				var tags = this.method.tags;
				return tags.warn;
			}
			return false;
		},

		archived ()
		{
			if(this.method)
			{
				var tags = this.method.tags;
				return tags.archived;
			}
			return false;
		}
	},

	filters:
	{
		marked      :marked,
		humanize    :Helpers.humanize
	},

}

</script>

<style lang="scss">
	
</style>