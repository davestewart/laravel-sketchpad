Vue.component('result', {

	template:'#result-template',

	data:function(){

		return{
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
		'state', 
		'method'
	],

	computed:
	{
		title:function()
		{
			var state = this.state;
			return state.method && state.method.name != 'index'
					? Helpers.methodLabel(state.method)
					: state.controller
						? state.controller.label
						: 'Sketchpad';
		},

		info:function()
		{
			var state = this.state;
			return state.method && state.method.name != 'index'
					? state.method.comment.intro || '&hellip;'
					: state.controller
						? state.controller.methods.length + ' methods'
						: '';
		},

		params:function()
		{
			return this.state.method
				? this.state.method.params
				: null;
		},

		deferred:function()
		{
			if(this.state.method)
			{
				var tags = this.state.method.comment.tags;
				return tags.deferred || tags.warning;
			}
			return false;
		},

		warning:function()
		{
			if(this.state.method)
			{
				var tags = this.state.method.comment.tags;
				return tags.warning;
			}
			return false;
		},

		method:function()
		{
			return this.state.method;
		}
	},

	filters:
	{
		marked:marked,
		humanize:Helpers.humanize
	},

	ready:function()
	{
		this.$output 	= $('#output');
		this.timer 		= new Timer();
	},

	methods:
	{

		// ------------------------------------------------------------------------------------------------
		// load methods

			load:function(transition)
			{
				if ( ! this.state.method )
				{
					this.clear();
					return;
				}

				this.transition	= this.state.method !== this.oldMethod;
				this.loading	= true;

				// load
				if( ! this.deferred )
				{
					this._load(transition);
				}
				else
				{
					this.clear();
					if(this.state.method.comment.tags.warning)
					{
						//alert(this.state.method.comment.tags.warning || 'Be careful when running this method!');
					}
				}
			},

			_load:function(transition)
			{
				// time load process
				this.timer.start();

				// store old method
				this.oldMethod = this.state.method;

				// run
				this.$root.server
					.call(this.state.route, this.onLoad, this.onFail)
					.always(this.onComplete);
			},

			clear:function()
			{
				return this.$output.empty();
			},

			loadIframe:function(xhr)
			{
				var text	= xhr.responseText;
				var type	= xhr.getResponseHeader('Content-Type');
				var src		= 'data:' + type + ',' + encodeURIComponent(text);
				var $iframe = $('<iframe class="error" frameborder="0">');
				//var script	= '<script>var b=document.body,h=document.documentElement;parent.setIframeHeight(Math.max(b.scrollHeight,b.offsetHeight,h.clientHeight,h.scrollHeight,h.offsetHeight));</script>';

				this.clear().append($iframe.attr('src', src));
			},



		// ------------------------------------------------------------------------------------------------
		// events

			onLoad:function(data, status, xhr)
			{
				//console.log([data, status, xhr.getAllResponseHeaders(), xhr]);
				// properties
				this.transition 	= false;
				if(this.state.method)
				{
					this.state.method.error = 0;
				}

				// format
				if(this.state.method.comment.tags.iframe)
				{
					return this.loadIframe(xhr);
				}

				var contentType = xhr.getResponseHeader('Content-Type');

				// handle json response
				if(contentType === 'application/json')
				{
					this.format = 'json';
					this.$output.JSONView(data);
					return;
				}

				// handle md response
				if(contentType.indexOf('text/markdown') > -1)
				{
					var html		= marked(data);
					this.format 	= 'markdown';
					this.$output.html(html);
					return;
				}

				// content
				this.$output.html(data);

				// format code blocks
				if(settings.formatCode)
				{
					this.$output.find('pre.code').each(function(i, block) {
						hljs.highlightBlock(block);
					});
				}

				// handle forms
				var $form = this.$output.find('form[action=""]');
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

			onFail:function(xhr, status, message)
			{
				this.format = 'error';
				this.state.method.error = 1;
				this.loadIframe(xhr);
			},

			onComplete:function()
			{
				console.info('Ran "%s" in %d ms', this.state.route, this.timer.stop().time);
				this.loading 	= this.$root.server.count != 0;
			}

	}

});
