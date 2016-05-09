var vm =
{

	el:'#app',

	data:function()
	{

		var data = JSON.parse($('#data').text());
		data.controller = null;
		data.method = null;
		data._route = '';
		data.modal = {};
		return data;
	},

	ready:function()
	{
		// data
		this.$refs.navigation.controllers = this.controllers;

		// history
		this.history = new UserHistory(this);

		// front page
		if(this.history.isHome())
		{
			$('#welcome').appendTo('#output').show();
		}
		
		// ui
		$('#nav .sticky').sticky({topSpacing:20});
		//$('#params .sticky').sticky({topSpacing:20});

		// links
		$('body').on('click', 'a', this.onLinkClick);
	},

	computed:
	{
		route:
		{
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

	events:
	{
		onNavClick:function(route)
		{
			this.history.pushState(route);
			this.setRoute(route);
		}

	},

	methods:
	{


		// ------------------------------------------------------------------------------------------------
		// route

			setRoute:function(route)
			{
				// properties
				this.$data._route 	= route;
				var controller 		= this.getController(route);
				var method 			= this.getMethod(route);

				// controller
				this.controller = this.$refs.navigation.controller = controller;
				
				// take action
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
		// pages

			onLinkClick:function(event)
			{
				// variables
				var url		= event.target.href;
				var matches = url.match(/\/:(\w+)/);
				if(matches)
				{
					event.preventDefault();
					this.$refs.modal.load(url);
				}
			}

	}

};

Vue.component('modal', {

	template:'#modal-template',
	
	props:['title', 'body', 'save'],

	methods:
	{
		load:function(url)
		{

			$.get(url, function (html)
			{
				var $body 	= $('<div>').append(html);
				this.title 	= $body.find('h1').remove().text();
				this.save	= $body.find('form').length;
				this.body 	= $body.html();
				this.show();
			}.bind(this))
		},

		show:function()
		{
			console.log('show');
			$('#modal').modal('show');
		},

		hide:function()
		{
			$('#modal').modal('hide');
		}

	}
	
});
Vue.component('navigation', {

	template:'#navigation-template',

	props:['controllers', 'controller'],

	methods:
	{

		// ------------------------------------------------------------------------------------------------
		// handlers

			onControllerClick:function(controller)
			{
				this.$dispatch('onNavClick', controller.route);
			},

			onMethodClick:function(method, element, $http)
			{
				if(event.metaKey || event.ctrlKey)
				{
					return server.open(event.target.href);
				}
				this.$dispatch('onNavClick', method.route);
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
				return this.$parent.$data._route.indexOf(route) == 0;
			}

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

function UserHistory(app)
{
	this.app = app;

	// setup base route
	this.base = $('meta[name="route"]').attr('content');

	// back handler
	window.onpopstate = this.onPopState.bind(this);

	this.updateRoute();
}

UserHistory.prototype =
{
	app:null,

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
		this.app.route = window.location.pathname;
	},

	getTitle:function(route)
	{
		return 'Sketchpad - ' + this.getRelativeRoute(route);
	},

	getRelativeRoute:function(route)
	{
		route = route || this.app.route;
		return route.substr(this.base.length);
	},

	isHome:function()
	{
		return this.getRelativeRoute() == '';
	}

};
function Server()
{
	// setup base route
	this.base = $('meta[name="route"]').attr('content');
}

Server.prototype =
{

	base:'',

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
	},

	load:function(route, onSuccess)
	{
		var url = this.base + route;
		console.log(url);
		return $.get(url, onSuccess);
	}

};

var server	= new Server();
var vm 		= new Vue(vm);