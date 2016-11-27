<template>

	<li v-if="tags.group" class="folder">
		<span class="name">{{ tags.group }}</span>
	</li>

	<li
		:style="listStyle"
		:class="listClass"
		>
		<a
			:class="{method:true, error:error}"
			:style="linkStyle"
			title="{{ comment.intro }}"
			href="{{ state.makeRoute(method) }}"
			>
			{{{ label }}}
		</a>
		<p
			v-if="comment.intro && settings.showComments"
			class="comment"
			>{{ comment.intro }}</p>
	</li>

</template>

<script>

import Helpers		from '../js/classes/helpers.js';
import settings 	from '../js/services/settings.js';

export default
{

	data:function()
	{
		return {
			settings:settings
		};
	},

	props:'method state'.split(' '),

	computed:
	{
		listClass:function()
		{
			var tags 	= this.tags;
			var state 	= this.state;
			var data 	=
			{
				active		:state.route && state.route.indexOf(this.route) == 0,
				favourite	:tags.favourite,
				icon		:tags.favourite || tags.icon
			};

			if(tags.css) {
				data[tags.css] = true;
			}
			if(tags.defer) {
				data.icon = true;
				data['defer'] = true;
			}
			if(tags.warning) {
				data.icon = true;
				data['warning'] = true;
			}
			if(tags.archived) {
				data['archived'] = true;
			}
			if(tags.icon) {
				var parts = tags.icon.split(/\s+/);
				data['fa-' + parts.pop()] = true;
			}
			return data;
		},

		listStyle:function()
		{
			var tags	= this.tags;
			var data 	= {};
			if(tags.icon) {
				var parts = tags.icon.split(/\s+/);
				if(parts.length == 2)
				{
					data.color = parts.shift();
				}
			}
			return data;
		},

		linkStyle:function()
		{
			var tags	= this.tags;
			var data 	= {};
			if(tags.color){
				data.color = tags.color;
			}
			return data;
		},

		name:function() { return this.method.name; },
		label:function() { return Helpers.getMethodLabel(this.method); },
		route:function() { return this.method.route; },
		error:function() { return this.method.error; },
		comment:function() { return this.method.comment; },
		tags:function() { return this.method.tags; }
	}

}

</script>

<style lang="scss">
	
</style>