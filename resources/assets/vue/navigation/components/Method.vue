<template>

		<li
			:style="listStyle"
			:class="listClass"
			>
			<a
				:class="{method:true}"
				:style="linkStyle"
				:title="comment"
				v-link="getLink()"
				>
				{{{ getLabel() }}}
			</a>
			<p
				v-if="comment"
				class="comment"
				>{{{ comment }}}</p>
		</li>

</template>

<script>

import Helpers		from '../../../js/functions/helpers.js';

import settings 	from '../../../js/state/settings.js';
import state 	    from '../../../js/state/state.js';

export default
{

	props: ['method'],

	computed:
	{
		listClass ()
		{
			var tags 	= this.tags;
			var data 	=
			{
				active		:this.route === this.$route.params.route,
				favourite	:tags.favourite,
				icon		:tags.favourite || tags.icon
			};

			if (tags.css) {
				data[tags.css] = true;
			}
			if (tags.defer) {
				data.icon = true;
				data.defer = true;
			}
			if (tags.warn) {
				data.icon = true;
				data.warn = true;
			}
			if (tags.archived) {
				data.archived = true;
			}

			if (this.error) {
				data.icon = true;
				data.error = true;
			}
			else if (tags.icon) {
				var parts = tags.icon.split(/\s+/);
				data['fa-' + parts.pop()] = true;
			}
			return data;
		},

		listStyle ()
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

		linkStyle ()
		{
			var tags	= this.tags;
			var data 	= {};
			if (tags.color){
				data.color = tags.color;
			}
			return data;
		},

		name () { return this.method.name; },
		label () { return this.getLabel(this.method); },
		route () { return this.method.route; },
		error () { return this.method.error; },
		comment () { return this.method.comment.intro.replace(/<.+?>/g, ''); },
		tags () { return this.method.tags; }
	},

	methods:
	{
		getLabel:function()
		{
			return Helpers.getMethodLabel(this.method);
		},

		getLink()
		{
			return '/run/' + state.makeRoute(this.method)
		}

	}

}

</script>

<style lang="scss">
	
</style>