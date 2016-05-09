var $output;

Vue.component('result', {

	template:'#result-template',

	data:function(){

		return{
			format	:'',
			loading	:false,
			title	:'Sketchpad',
			info	:'',
			method	:null,
		}
	},

	ready:function()
	{
		$output = $('#output');
	},

	events:
	{

		loadController:function(controller)
		{
			this.method = null;
			this.setTitle(controller.label, controller.methods.length + ' methods');
			$output.empty();
		},

		loadMethod:function(method)
		{
			this.method = method;
			this.updateMethod();
		}

	},

	methods:
	{

		// ------------------------------------------------------------------------------------------------
		// events

			updateMethod:function()
			{
				// properties
				var method		= this.method;
				//this.loading 	= true;
				this.setTitle(method.label, method.comment ? method.comment.intro : method.label);

				// values
				var values 	= this.method.params.map(function(e){ return e.value; });
				var url		= method.route + values.join('/');

				// load
				//$output.empty();
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

			getParamType:function(param)
			{
				if(/^\d+(\.\d+)?$/.test(param.value))
				{
					return 'number';
				}
				if(/^true|false$/i.test(param.value))
				{
					return 'checkbox';
				}
				return 'text';
			},

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
				this.loading = false;
			}

	}

});
