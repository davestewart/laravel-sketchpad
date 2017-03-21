<template>
	<header id="header">
		<h1>{{ title }}</h1>
		<div :class="{info:true, alert:alert, 'alert-danger':warning, 'alert-info':archived }">{{{ info | marked }}}</div>
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
			return this.method && this.method.name != 'index'
				? Helpers.getMethodLabel(this.method)
				: this.controller
					? this.controller.label
					: 'Sketchpad';
		},

		info ()
		{
			return this.method && this.method.name != 'index'
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
				return tags.defer || tags.warning;
			}
			return false;
		},

		alert ()
		{
			return this.warning || this.archived;
		},

		warning ()
		{
			if(this.method)
			{
				var tags = this.method.tags;
				return tags.warning;
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