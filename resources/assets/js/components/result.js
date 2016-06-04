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
					? state.method.name
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
		}
	},

	filters:
	{
		marked:marked
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

				// variables
				var url			= this.state.getRoute();
				this.transition	= transition;
				this.loading	= true;
				this.timer.start();

				// load
				this.$root.server
					.call(url, this.onLoad, this.onFail)
					.always(this.onComplete);
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
				console.info('Ran "%s" in %d ms', this.state.getRoute(), this.timer.stop().time);
				this.loading 	= this.$root.server.count != 0;
			}

	}

});
