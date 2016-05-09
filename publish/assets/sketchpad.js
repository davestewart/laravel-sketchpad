var vm =
{

	el:'#app',

	data:function()
	{

		var data = JSON.parse($('#data').text());
		data.controller = null;
		data.method = null;
		data._route = '';
		return data;
	},

	ready:function()
	{
		$('#sticky').sticky({topSpacing:20});
		//$('#params').sticky({topSpacing:20});
	},

	computed:{

		route:{
			get:function ()
			{
				return this.$data._route;
			},
			set:function (route)
			{
				this.setRoute(route);
			}
		}

	},

	methods:
	{

		// ------------------------------------------------------------------------------------------------
		// route

			setRoute:function(route)
			{
				this.$data._route 	= route;
				this.controller 	= this.getController(route);
				var method 			= this.getMethod(route);


				if(method)
				{
					this.$broadcast('loadMethod', method);
					this.method = method;
				}
				else if(this.controller)
				{
					this.$broadcast('loadController', this.controller);
				}
			},

			getController:function (route)
			{
				var arr = this.controllers.filter(function(e)
				{
					return route.indexOf(e.route) == 0;
				});
				return arr ? arr[0] : null;
			},

			getMethod:function (route, controller)
			{
				controller = controller || this.controller || this.getController(route);
				if(controller)
				{
					var arr = controller.methods.filter(function(e)
					{
						return route.indexOf(e.route) == 0;
					});
					return arr ? arr[0] : null;
				}
			},


		// ------------------------------------------------------------------------------------------------
		// handlers

			onControllerClick:function(controller)
			{
				hist.pushState(controller.route);
				this.route = controller.route;
			},

			onMethodClick:function(method, element, $http)
			{
				if(event.metaKey || event.ctrlKey)
				{
					return server.open(event.target.href);
				}
				hist.pushState(method.route);
				this.route = method.route;
			},


		// ------------------------------------------------------------------------------------------------
		// utilities

			getLinkHtml:function(route)
			{
				return route
					.replace('/sketchpad/', '')
					.replace(/\/$/, '')
					.split('/')
					.join(' <span class="divider">&#9656;</span> ');
			},

			isActive:function(route)
			{
				return this.$data._route.indexOf(route) == 0;
			}

	}

};

Vue.component('navigation', {

	template:'#navigation-template',

	props:[],

	events:{

	},

	data:function(){

		return{
			method:null,
			params:null
		}
	},

	methods:{

	}

});


Vue.component('params', {
	
	template:'#params-template',
	
	props:['params'],


	methods:
	{

		getType:function(param)
		{
			if(/^-?(\d+|\d+\.\d+|\.\d+)([eE][-+]?\d+)?$/.test(param.value))
			{
				return 'number';
			}
			if(/^true|false$/i.test(param.value))
			{
				return 'checkbox';
			}
			return 'text';
		},

		onParamChange:function()
		{
			this.$dispatch('onParamChange');
		}
	}
	
});
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
		//this.$watch('method.params', this.updateMethod, {deep:true});
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
			this.updateMethod();
		},

		onParamChange:function()
		{
			this.updateMethod(true);
		}

	},

	methods:
	{

		// ------------------------------------------------------------------------------------------------
		// load methods

			updateMethod:function(update)
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

function UserHistory(nav)
{
	this.nav = nav;

	// setup base route
	this.base = $('meta[name="route"]').attr('content');

	// back handler
	window.onpopstate = this.onPopState.bind(this);

	this.updateRoute();

	// history - this isn't really needed
	History.Adapter.bind(window,'statechange',function(){
		var state = History.getState();
		//console.log('state change:', state);
	});

}

UserHistory.prototype =
{
	nav:null,

	base:'',

	pushState:function(route)
	{
		History.pushState(null, this.getTitle(route), route);
	},

	onPopState:function(event)
	{
		var state = History.getStateById(event.state);
		if(state)
		{
			document.title = state.title;
		}
		this.updateRoute();
	},

	// update app
	updateRoute:function(event)
	{
		this.nav.route = window.location.pathname;
	},

	getTitle:function(route)
	{
		return 'Sketchpad - ' + this.getRelativeRoute(route);
	},

	getRelativeRoute:function(route)
	{
		return route.substr(this.base.length);
	}

};
function Server()
{

}

Server.prototype =
{

	call:function(route, onSuccess, onFail)
	{
		var url = this.getCallUrl(location.origin + route);
		return $.get(url, onSuccess).fail(onFail);
	},
	
	open:function(url)
	{
		window.open(server.getCallUrl(url));
	},

	getCallUrl:function(url)
	{
		return url.replace(/\/$/, '') + '?call=1';
	}

};

var vm 		= new Vue(vm);
var server	= new Server();
var hist	= new UserHistory(vm);