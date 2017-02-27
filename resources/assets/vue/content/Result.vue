<template>

	<div id="result" :class="{loading:loading, transition:transition}">

		<!-- info -->
		<section id="info">

			<header id="header">
				<h1>{{ title }}</h1>
				<div :class="{info:true, alert:alert, 'alert-danger':warning, 'alert-info':archived }">{{{ info | marked }}}</div>
			</header>

			<!-- parameters -->
			<div id="params" v-if="method && method.name !== 'index'">
				<div class="sticky">

					<nav v-if="params" class="navbar navbar-default">
						<span class="loader"></span>
						<ul class="nav navbar-nav">
							<li><button @click="loader.load()" class="btn btn-xs" style="outline:none">Run</button></li>
							<template v-for="param in params">
								<param :param="param"></param>
							</template>
							<!--<li v-if="! defer && params.length == 0"><span>No parameters</span></li>-->
						</ul>
					</nav>

				</div>
			</div>
		</section>

		<!-- output -->
		<section id="output" :data-format="format"></section>

	</div>

</template>

<script>

// libs
import _                from 'underscore'

// objects
import state		    from '../../js/state/state.js';
import settings		    from '../../js/state/settings.js';

import Helpers		    from '../../js/functions/helpers.js';
import server		    from '../../js/services/server.js';
import loader           from '../../js/services/loader';
import {LoaderEvent}    from '../../js/services/loader';

// components
import Param		from './Param.vue';

export default
{
	name: 'Result',

	components:
	{
		Param
	},

	data ()
	{
		return {
			format		:'',
			loading		:false,
			transition	:false
		}
	},

	computed:
	{
		title ()
		{
			return state && state.method && state.method.name != 'index'
					? Helpers.getMethodLabel(state.method)
					: state.controller
						? state.controller.label
						: 'Sketchpad';
		},

		info ()
		{
			return state && state.method && state.method.name != 'index'
					? state.method.comment.intro || '&hellip;'
					: state.controller
						? state.controller.methods.length + ' methods'
						: '';
		},

		params ()
		{
			return state && state.method
				? state.method.params
				: null;
		},

		defer ()
		{
			if(state && state.method)
			{
				var tags = state.method.tags;
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
			if(state && state.method)
			{
				var tags = state.method.tags;
				return tags.warning;
			}
			return false;
		},

		archived ()
		{
			if(state && state.method)
			{
				var tags = state.method.tags;
				return tags.archived;
			}
			return false;
		},

		method ()
		{
			return state && state.method;
		}
	},

	filters:
	{
		marked		:window.marked,
		humanize	:Helpers.humanize
	},

	created ()
	{
		this.loader = loader;
		this.loader.$on(LoaderEvent.START, this.onLoaderStart);
		this.loader.$on(LoaderEvent.LOAD, this.onLoaderLoad);
		this.loader.$on(LoaderEvent.ERROR, this.onLoaderError);
	},

	ready ()
	{
		this.$output = $('#output');
	},

	route:
	{
		activate (transition)
		{
			//console.log('activate')
			transition.next();
		},

		data (transition)
		{
			this.unwatch();

			const route = transition.to;
			//console.log('data', route.params, route.query)

			let updated = state.setRoute(route.params.route, route.query);
			transition.next();

			this.load()
		},

		deactivate (transition)
		{
			// console.log('deactivate', this.unwatch)
			this.unwatch()
			this.$nextTick(() => {
				state.reset()
				transition.next()
			})
		},

		canReuse ()
		{
			return true;
		}
	},

	methods:
	{
		// ------------------------------------------------------------------------------------------------
		// loading

			watch ()
			{
				this.unwatch = state.$watch('method', this.onParamsChange, {deep:true});
			},

			unwatch ()
			{
				console.log('unwatch not yet implemented');
			},

			onParamsChange:_.debounce(method =>
			{
				//console.log('params:', (method ? clone(method.params) : 'no method'));
				if(method)
				{
					router.replace('/run/' + state.makeRoute(state.method));
				}
			}, 400),

			onLoaderStart (clear)
			{
				this.loading = true;
				if(clear)
				{
					this.transition = true
					this.clear()
				}
			},

			onLoaderLoad (data, type)
			{
				this.setContent(data, type)
			},

			onLoaderError (data, type)
			{
				this.setError(data, type)
			},


		// ------------------------------------------------------------------------------------------------
		// content methods

			load ()
			{
				this.watch();
				state.method
					? this.loader.load()
					: this.clear()
			},

			clear ()
			{
				return this.$output && this.$output.empty();
			},

			setContent (data, contentType = '')
			{
				// variables
				var method 			= state.method;
				var transition      = this.loader.transition;

				// properties
				this.transition 	= false;
				this.loading 		= false;

				// format
				if(method && method.tags.iframe)
				{
					return this.loadIframe(data, contentType);
				}

				var $data = $('<div class="data">' + data + '</div>');

				// handle json response
				if(contentType == 'application/json')
				{
					this.format = 'json';
					$data.html($('<div class="code json">').JSONView(data));
				}

				// handle md response
				else if(contentType.indexOf('text/markdown') > -1)
				{
					var html		= marked(data);
					this.format 	= 'markdown';
					$data.html(html);
					//return;
				}

				// clear
				if(transition)
				{
					this.clear();
				}

				// add content
				(method && method.tags.append) || settings.ui.appendOutput
					? this.$output.prepend($data.append('<hr>'))
					: this.$output.html($data);

				// format code blocks
				if(settings.ui.formatCode)
				{
					$data.find('pre.code, pre > code').each(function(i, block) {
						hljs.highlightBlock(block);
					});
				}

				// handle forms
				var $form = $data.find('form[action=""],form:not([action])');
				if($form.length)
				{
					var result = this;
					$form
						.attr('action', '')
						.on('submit', event =>
						{
							event.preventDefault();
							var data =
							{
								type        : 'POST',
								url         : server.getRunUrl(method),
								data        : $form.serialize()
							};
							$
								.post(data)
								.done(this.loader.onLoad);
						});
				}

			},

			setError (text, type)
			{
				this.transition 	= false;
				this.loading 		= false;
				this.format 		= 'error';
				this.loadIframe (text, type);
			},

			loadIframe (text, type)
			{
				var src		= 'data:' + type + ',' + encodeURIComponent(text);
				var $iframe = $('<iframe class="error" frameborder="0">');
				this.clear().append($iframe.attr('src', src));
			}

	}

}

</script>

<style lang="scss">
	
</style>