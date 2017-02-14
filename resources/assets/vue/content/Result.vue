<template>

	<div id="result" :class="{loading:loading, transition:transition}">

		<!-- info -->
		<section id="info">

			<header>
				<h1>{{ title }}</h1>
				<div :class="{info:true, alert:alert, 'alert-danger':warning, 'alert-info':archived }">{{{ info | marked }}}</div>
			</header>

			<!-- parameters -->
			<div id="params" v-if="state.method && state.method.name != 'index'">
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

// objects
import settings		from '../../js/state/settings.js';
import server		from '../../js/services/server/server.js';
import loader		from '../../js/services/loader';
import Helpers		from '../../js/classes/helpers.js';

// components
import Param		from './Param.vue';

export default
{

	components:
	{
		Param
	},

	data ()
	{
		return {
			format		:'',
			loading		:false,
			transition	:false,
			info		:''
		}
	},

	props:
	[
		'state'
	],

	computed:
	{
		title ()
		{
			var state = this.state;
			return state.method && state.method.name != 'index'
					? Helpers.getMethodLabel(state.method)
					: state.controller
						? state.controller.label
						: 'Sketchpad';
		},

		info ()
		{
			var state = this.state;
			return state.method && state.method.name != 'index'
					? state.method.comment.intro || '&hellip;'
					: state.controller
						? state.controller.methods.length + ' methods'
						: '';
		},

		params ()
		{
			return this.state.method
				? this.state.method.params
				: null;
		},

		defer ()
		{
			if(this.state.method)
			{
				var tags = this.state.method.tags;
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
			if(this.state.method)
			{
				var tags = this.state.method.tags;
				return tags.warning;
			}
			return false;
		},

		archived ()
		{
			if(this.state.method)
			{
				var tags = this.state.method.tags;
				return tags.archived;
			}
			return false;
		},

		method ()
		{
			return this.state.method;
		}
	},

	filters:
	{
		marked		:window.marked,
		humanize	:Helpers.humanize
	},

	ready ()
	{
		// output
		this.$output 		= $('#output');

		// loader
		this.loader 		= loader;
		this.loader.state 	= this.state;
		this.loader.$on('start', this.onLoaderStart);
		this.loader.$on('load', this.onLoaderLoad);
		this.loader.$on('error', this.onLoaderError);
		this.loader.$on('params', this.onParamsChange);

		// routing
		this.loader.load()
	},

	methods:
	{
		// ------------------------------------------------------------------------------------------------
		// loading

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

			onParamsChange ()
			{
				router.replace('/run/' + this.state.makeRoute(this.state.method));
			},


		// ------------------------------------------------------------------------------------------------
		// content methods

			setContent (data, contentType = '')
			{
				// properties
				this.transition 	= false;
				this.loading 		= false;

				// variables
				var method 			= this.state.method;

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
				if(! settings.appendResult && method && method.tags.append && method !== this.oldMethod) // need this to clear things
				{
					this.$output.empty();
				}

				// add content
				settings.appendResult || (method && method.tags.append)
					? this.$output.prepend($data.append('<hr>'))
					: this.$output.html($data);

				// format code blocks
				if(settings.formatCode)
				{
					$data.find('pre.code, pre > code').each(function(i, block) {
						hljs.highlightBlock(block);
					});
				}

				// handle forms
				var $form = $data.find('form[action=""]');
				if($form.length)
				{
					var result = this;
					$form
						.attr('action', '')
						.on('submit', function(event)
						{
							event.preventDefault();
							var data =
							{
								type        : 'POST',
								url         : window.location.href,
								data        : $form.serialize()
							};
							$
								.post(data)
								.done(result.onLoad.bind(this));
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

			clear ()
			{
				return this.$output.empty();
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