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
							<li><button @click="_load()" class="btn btn-xs" style="outline:none">Run</button></li>
							<li v-for="param in params">
								<param :param="param"></param>
							</li>
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
import Helpers		from '../../js/classes/helpers.js';
import Timer		from '../../js/classes/timer.js';

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
			title		:'Sketchpad',
			info		:'',
			oldMethod	:null
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
		this.$output 	= $('#output');
		this.timer 		= new Timer();
	},

	methods:
	{

		// ------------------------------------------------------------------------------------------------
		// load methods

			load (transition)
			{
				if ( ! this.state.method )
				{
					this.clear();
					return;
				}

				this.transition	= this.state.method !== this.oldMethod;
                this.loading = ! this.defer;

				// load
				if( ! this.defer )
				{
					this._load(transition);
				}
				else
				{
					this.clear();
					if(this.state.method.tags.warning)
					{
						//alert(this.state.method.tags.warning || 'Be careful when running this method!');
					}
				}
			},

			_load (transition)
			{
				// time load process
				this.timer.start();

				server
					.call(this.state.method, this.onLoad, this.onFail, this.onComplete);
			},

			clear ()
			{
				return this.$output.empty();
			},

			loadIframe (xhr)
			{
				var text	= xhr.responseText;
				var type	= xhr.getResponseHeader('Content-Type');
				var src		= 'data:' + type + ',' + encodeURIComponent(text);
				var $iframe = $('<iframe class="error" frameborder="0">');
				//var script	= '<script>var b=document.body,h=document.documentElement;parent.setIframeHeight(Math.max(b.scrollHeight,b.offsetHeight,h.clientHeight,h.scrollHeight,h.offsetHeight));</scr' + 'ipt>';

				this.clear().append($iframe.attr('src', src));
			},



		// ------------------------------------------------------------------------------------------------
		// events

			onLoad (data, status, xhr)
			{
				// variables
				var method = this.state.method;

				//console.log([data, status, xhr.getAllResponseHeaders(), xhr]);
				// properties
				this.transition 	= false;
				if(method)
				{
					method.error = 0;
				}

				// format
				if(method.tags.iframe)
				{
					return this.loadIframe(xhr);
				}

				var contentType = xhr.getResponseHeader('Content-Type');

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
				if(! settings.appendResult && method.tags.append && method !== this.oldMethod)
				{
					this.$output.empty();
				}

				// add content
				settings.appendResult || method.tags.append
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

			onFail (xhr, status, message)
			{
				this.format = 'error';
				this.state.method.error = 1;
				this.loadIframe(xhr);
			},

			onComplete (loaded)
			{
				console.info('Ran "%s" in %d ms', this.state.route, this.timer.stop().time);
				this.loading 	= false;
				this.oldMethod 	= this.state.method;
			}

	}

}

</script>

<style lang="scss">
	
</style>