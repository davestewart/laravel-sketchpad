var $output;

Vue.component('result', {

	template:'#result-template',

	data:function(){

		return{
			format		:'',
			loading		:false,
			title		:'Sketchpad',
			info		:'',
			method		:null
		}
	},

	ready:function()
	{
		$output = $('#output');
		//this.$watch('method.params', this.callMethod, {deep:true});
	},

	events:
	{

		loadController:function(controller)
		{
			this.method = null;
			this.$refs.params.params = null;
			this.setTitle(controller.label, controller.comment.intro || controller.methods.length + ' methods');
			$output.empty();
		},

		loadMethod:function(method)
		{
			this.method = method;
			this.$refs.params.params = method.params;
			this.callMethod();
		},

		onParamChange:function()
		{
			this.callMethod(true);
		}

	},

	methods:
	{

		// ------------------------------------------------------------------------------------------------
		// load methods

			callMethod:function(update)
			{
				// properties
				var method = this.method;
				if( ! update )
				{
					this.loading = true;
				}
				this.setTitle(method.label, method.comment ? method.comment.intro : method.label);

				// values
				var values 	= method.params.map(function(e){ return e.value; });
				var url		= method.route + values.join('/');

				// debug
				this.lastUrl = url;
				this.date = new Date();

				// load
				server
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
		// accessors

			setTitle:function(title, info)
			{
				this.title 	= title;
				this.info	= info;
			},


		// ------------------------------------------------------------------------------------------------
		// events

			onLoad:function(data, status, xhr)
			{
				//console.log([data, status, xhr.getAllResponseHeaders(), xhr]);

				// format
				var format 	= (this.format = this.method.comment.tags.format || null);
				if(format == 'html')
				{
					return this.loadIframe(xhr);
				}

				// handle json response
				var contentType = xhr.getResponseHeader('Content-Type');
				if(contentType === 'application/json')
				{
					this.format = 'json';
					$output.JSONView(data);
					return;
				}

				// content
				$output.html(data);

			},

			onFail:function(xhr, status, message)
			{
				this.format = 'error';
				this.loadIframe(xhr);
			},

			onComplete:function()
			{
				console.info('Ran "%s" in %d ms', this.lastUrl, new Date - this.date);
				this.loading = false;
			}

	}

});
