var $output;

Vue.component('result', {

	template:'#result-template',

	data:function(){

		return{
			format		:'',
			loading		:false,
			transition	:false,
			title		:'Sketchpad',
			info		:''
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
			return state.method
					? Helpers.methodLabel(state.method)
					: state.controller
						? state.controller.label
						: 'Sketchpad';
		},

		info:function()
		{
			var state = this.state;
			return state.method
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
			return !! (this.state.method && 'deferred' in this.state.method.comment.tags);
		}
	},

	filters:
	{
		marked:marked,
		humanize:Helpers.humanize
	},

	ready:function()
	{
		$output 		= $('#output');
		this.timer 		= new Timer();
	},

	methods:
	{

		// ------------------------------------------------------------------------------------------------
		// load methods

			load:function(transition)
			{
				if ( ! this.method )
				{
					$output.empty();
					return;
				}
				this.transition	= transition;
				this.loading	= true;

				// load
				if( ! this.deferred )
				{
					this._load(transition);
				}
			},

			_load:function(transition)
			{
				// variables
				this.timer.start();

				// run
				this.$root.server
					.call(this.state.route, this.onLoad, this.onFail)
					.always(this.onComplete);
			},

			clear:function()
			{
				$output.empty();
			},

			loadIframe:function(xhr)
			{
				var text	= xhr.responseText;
				var type	= xhr.getResponseHeader('Content-Type');
				var src		= 'data:' + type + ',' + encodeURIComponent(text);
				var $iframe = $('<iframe class="error" frameborder="0">');
				//var script	= '<script>var b=document.body,h=document.documentElement;parent.setIframeHeight(Math.max(b.scrollHeight,b.offsetHeight,h.clientHeight,h.scrollHeight,h.offsetHeight));</script>';

				$output.empty().append($iframe.attr('src', src));
			},



		// ------------------------------------------------------------------------------------------------
		// events

			onLoad:function(data, status, xhr)
			{
				//console.log([data, status, xhr.getAllResponseHeaders(), xhr]);
				// properties
				this.transition 	= false;
				this.method.error 	= 0;

				// format
				if(this.method.comment.tags.iframe)
				{
					return this.loadIframe(xhr);
				}

				var contentType = xhr.getResponseHeader('Content-Type');

				// handle json response
				if(contentType === 'application/json')
				{
					this.format = 'json';
					$output.JSONView(data);
					return;
				}

				// handle md response
				if(contentType.indexOf('text/markdown') > -1)
				{
					var html		= marked(data);
					this.format 	= 'markdown';
					$output.html(html);
					return;
				}

				// content
				$output.html(data);

			},

			onFail:function(xhr, status, message)
			{
				this.format = 'error';
				this.method.error = 1;
				$output.empty();
				this.loadIframe(xhr);
			},

			onComplete:function()
			{
				console.info('Ran "%s" in %d ms', this.state.route, this.timer.stop().time);
				this.loading 	= this.$root.server.count != 0;
			}

	},

	events:
	{
		run:function()
		{
			this._load();
		}
	}

});
