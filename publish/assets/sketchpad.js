var vm =
{

	el:'#app',

	data:function()
	{
		// controllers
		var data = JSON.parse($('#data').text());

		// props
		data._route = '';
		data.controller = null;
		data.method = null;
		data.modal = {};

		// return
		return data;
	},

	ready:function()
	{
		// objects
		this.server		= new Server();
		this.history 	= new UserHistory(this);

		// front page
		if(this.history.isHome())
		{
			$('#welcome').appendTo('#output').show();
		}

		// ui
		//$('#nav .sticky').sticky({topSpacing:20, bottomSpacing:20});
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
				// params
				route 				= route.replace(/\/*$/, '/');

				// properties
				this.$data._route 	= route;
				this.controller 	= this.getController(route);
				var method 			= this.getMethod(route);

				// take action
				if(method)
				{
					this.method = method;
					this.$broadcast('loadMethod', method);
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
		// dom event handlers

			onLinkClick:function(event)
			{
				// variables
				var url		= event.target.href;
				var matches = url.match(/\/:(\w+)\/(\w+)/);
				if(matches)
				{
					event.preventDefault();
					this.$refs.modal.load(url);
				}
			},

			onControllerReload:function(data)
			{
				if(data)
				{
					var filtered = this.controllers.filter(function(c){ return c.path == data.path; });
					if(filtered.length)
					{
						var found = filtered[0];
						var index = this.controllers.indexOf(found);
						this.controllers.$set(index, data);
					}
					else
					{
						this.controllers.push(data);
						this.controllers.sort(function(a, b){
							if(a.path < b.path)
							{
								return -1;
							}
							if(a.path > b.path)
							{
								return 1;
							}
							return 0;
						});
					}
					this.setRoute(this.route);
				}
			},


		// ------------------------------------------------------------------------------------------------
		// other

			reloadController:function(file)
			{
				this.server.load(':load/' + file, this.onControllerReload);
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

			onMethodClick:function(method)
			{
				if(event.metaKey || event.ctrlKey)
				{
					return this.$root.server.open(event.target.href);
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
			transition	:false,
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
			this.loading 	= true;
			this.transition = this.method && this.method.route !== method.route;
			this.method 	= method;
			this.$refs.params.params = method.params;
			this.callMethod();
		},

		onParamChange:function()
		{
			this.callMethod();
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
				this.setTitle(method.label, method.comment ? method.comment.intro : method.label);

				// values
				var values 	= method.params.map(function(e){ return e.value; });
				var url		= method.route + values.join('/');

				// debug
				this.lastUrl = url;
				this.date = new Date();

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
				// properties
				this.transition = false;

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
				this.loading 	= this.$root.server.count != 0;
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

		requestId:0,

		count:0,


	// ------------------------------------------------------------------------------------------------
	// methods

		/**
		 * Calls a sketchpad route and returns the result
		 *
		 * @param route			The full route, including the '/sketchpad/' portion
		 * @param onSuccess
		 * @param onFail
		 * @returns {*}
		 */
		call:function(route, onSuccess, onFailure)
		{
			var url = location.origin + this.getCallUrl(route);
			this.count++;
			return $
				.get(url)
				.done(this.getSuccessCallback(onSuccess))
				.fail(this.getFailureCallback(onFailure));
		},

		/**
		 * Opens a sketchpad route in a new window
		 *
		 * @param url
		 */
		open:function(route)
		{
			window.open(this.getCallUrl(route));
		},

		/**
		 * Requests information from the server
		 *
		 * @param route			The partial route, from '/sketchpad/' onwards
		 * @param onSuccess
		 * @returns {*}
		 */
		load:function(route, onSuccess)
		{
			var url = this.base + route;
			return $.get(url, onSuccess);
		},

	// ------------------------------------------------------------------------------------------------
	// utilities

		isLastRequest:function(xhr)
		{
			this.count--;
			return this.requestId == xhr.getResponseHeader('X-Request-ID');
		},

		getSuccessCallback:function(callback)
		{
			return function(data, status, xhr)
			{
				if(this.isLastRequest(xhr))
				{
					callback(data, status, xhr);
				}
			}.bind(this);
		},

		getFailureCallback:function(callback)
		{
			return function(xhr, status, message)
			{
				if(this.isLastRequest(xhr))
				{
					callback(xhr, status, message);
				}
			}.bind(this);
		},

		getCallUrl:function(url)
		{
			return url.replace(/\/$/, '') + '?call=1&requestId=' + (++this.requestId);
		}

};

$(function(){

	// ------------------------------------------------------------------------------------------------
	// app
	
		var app = window.app = new Vue(vm);


	// ------------------------------------------------------------------------------------------------
	// livereload

		// store original callback
		window.__onLiveReloadFileChanged = window._onLiveReloadFileChanged;
	
		// proxy livereload function
		window._onLiveReloadFileChanged = function(file)
		{
			// intercept controller updates
			if (/Controller\.php/.test(file.path)) 
			{
				app.reloadController(file.path);
				return false;
			}
			else 
			{
				window.__onLiveReloadFileChanged(file);
			}
		}

});

