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
					? Helpers.getMethodLabel(state.method)
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
				var tags = this.state.method.tags;
				return tags.deferred || tags.warning;
			}
			return false;
		},

		alert:function()
		{
			return this.warning || this.archived;
		},

		warning:function()
		{
			if(this.state.method)
			{
				var tags = this.state.method.tags;
				return tags.warning;
			}
			return false;
		},

		archived:function()
		{
			if(this.state.method)
			{
				var tags = this.state.method.tags;
				return tags.archived;
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
					if(this.state.method.tags.warning)
					{
						//alert(this.state.method.tags.warning || 'Be careful when running this method!');
					}
				}
			},

			_load:function(transition)
			{
				// time load process
				this.timer.start();

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
					$data.html($('<pre class="code json">').JSONView(data));
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
				this.oldMethod 	= this.state.method;


			}

	}

});
