var App = Vue.extend({

	el:function(){ return '#app'; },

	data:function()
	{
		return {
			store		:this.$options.store,
			state		:this.$options.state,
			options		:
			{
				useLabels:true
			}
		};
	},

	ready:function()
	{
		// objects
		this.server		= this.$options.server;
		this.router 	= new Router({controllers:this.store.controllers, state:this.state});

		// history
		window.onpopstate = this.onHistoryPop;

		// start
		this.state.setRoute(this.router.parseRoute());
		this.$refs.result.method = this.state.method;

		// method update
		this.$watch('state.method.params', this.onParamsChange, {deep:true});

		// links
		$('body').on('click', 'a', this.onLinkClick);

		// front page
		if(this.state.route == '')
		{
			$('#welcome').appendTo('#output').show();
		}
		else
		{
			this.onParamsChange();
		}

		// ui
		//$('#nav .sticky').sticky({topSpacing:20, bottomSpacing:20});
		//$('#params .sticky').sticky({topSpacing:20});
	},

	methods:
	{

		// ------------------------------------------------------------------------------------------------
		// handlers

			onParamsChange:function(value, old)
			{
				this.state.updateHistory();
				this.state.updateRoute();
				this.$refs.result.load(value != old);
			},

			onHistoryPop:function(event)
			{
				if(event.state)
				{
					this.state.setRoute(event.state);
				}
			},

			onLinkClick:function(event)
			{
				// variables
				var url		= event.target.href;
				var route 	= this.router.parseRoute(url);

				// controller
				if(route.controller)
				{
					event.preventDefault();
					route.method && (event.metaKey || event.ctrlKey)
						? this.server.open(url)
						: this.router.go(route);
				}

				// modal
				else if(url.match(/\/:(\w+)\/(\w+)/))
				{
					event.preventDefault();
					this.$refs.modal.load(url);
				}

			}

	}

});

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

	props:
	[
		'controllers',
		'state'
	],

	methods:
	{
		getLinkHtml:function(route)
		{
			var name 	= '<span class="name">';
			var divider	= '<span class="divider">&#9656;</span> ';
			var close	= '</span> ';

			return name + route
				.replace('/sketchpad/', '')
				.replace(/\/$/, '')
				.split('/')
				.join(close + divider + name) + close;
		},

		isActive:function(route)
		{
			return this.state.route && this.state.route.indexOf(route) == 0;
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
			//this.$dispatch('onParamChange');
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

function Timer()
{
	
}

Timer.prototype =
{
	active:false,
	timeStart:0,
	time:0,

	start:function()
	{
		this.reset();
		this.active 	= true;
		this.timeStart 	= this.getTime();
		return this;
	},

	stop:function()
	{
		this.time 		= this.getTime();
		this.active 	= false;
		return this;
	},

	getTime:function()
	{
		return this.active
			? Date.now() - this.timeStart
			: this.time;
	},

	reset:function()
	{
		this.active 	= false;
		this.timeStart 	= this.time = 0;
		return this;
	}
};
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
;function Reloader(app)
{
	// properties
	this.app = app;
	this.paths = app.paths;

	// monkeypatch livereloader
	var reload 	= LiveReload.reloader.reload;
	var monkey	= this;
	LiveReload.reloader.reload = function(path, options)
	{
		if(monkey.reload(path))
		{
			return;
		}
		return reload.call(this, path, options);
	};
}

Reloader.prototype =
{
	app:null,

	paths:[],

	reload:function(path)
	{
		// intercept controller updates
		if (/Controller\.php$/.test(path))
		{
			this.app.reloadController(path);
			return true;
		}

		// php file
		if(/\.php$/.test(path))
		{
			this.app.reloadMethod();
			return true;
		}

		// let LiveReload handle the load
		return false;
	}
};

window.Router = Vue.extend({

	data:function()
	{
		return {
			controllers	:this.$options.controllers,
			state		:this.$options.state
		}
	},

	methods:
	{

		go:function(route)
		{
			// data
			route = route instanceof Route
				? route
				: this.parseRoute(route);

			// assign
			if(route.controller)
			{
				this.state.setRoute(route, true);
			}
		},

		/**
		 * Gets a Route instance from a route string
		 *
		 * @param 	{string}	[route]
		 * @returns {Route}
		 */
		parseRoute:function(route)
		{
			// parameters
			route = route || location.href;
			route = route.replace(location.origin, '');

			// variables
			var controller, method, params;

			// assignments
			controller = this.controllers.filter(function(c) { return route.indexOf(c.route) == 0; }).shift();
			if(controller)
			{
				method = controller.methods.filter(function(m) { return route.indexOf(m.route) == 0; }).shift();
			}
			if(method)
			{
				params = route.replace(method.route, '').match(/[^\/]+/g);
			}

			// return
			return new Route(route, controller, method, params);
		},

		dispatch:function(type, data)
		{
			this.$dispatch('update', {type:type, data:data});
		}

	}

});

function Route(route, controller, method, params)
{
	this.route 		= route || '';
	this.controller	= controller;
	this.method 	= method;
	this.params 	= params;
}

Route.prototype =
{
	route		:null,
	controller	:null,
	method		:null,
	params		:null
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
		 * @param route
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

state =
{

	// ------------------------------------------------------------------------------------------------
	// properties

		route		:'',
		controller	:null,
		method		:null,
		base		:$('meta[name="route"]').attr('content'),


	// ------------------------------------------------------------------------------------------------
	// public setters

		/**
		 * Set multiple values using a Route instance, optionally adding to history
		 *
		 * @param route
		 * @param updateHistory
		 */
		setRoute:function(route, updateHistory)
		{
			// state
			this.route		= route.route;
			this.controller	= route.controller;
			this.method		= route.method;
			if(route.params)
			{
				this.updateParams(route.params);
			}

			// history
			if(updateHistory)
			{
				this.updateHistory(true);
			}

			// route
			this.updateRoute();
		},

		/**
		 * Set params only, and replace history
		 *
		 * @param params
		 */
		setParams:function(params)
		{
			this.updateParams(params);
			this.updateRoute();
			this.updateHistory();
		},

		/**
		 * Get the current state values as an object
		 *
		 * @returns {{route: *, controller: *, method: *}}
		 */
		getState:function()
		{
			return {
				route		:this.route,
				controller	:this.controller,
				method		:this.method
			};
		},

		/**
		 * Gets a route string based on the values of the current controller, method
		 *
		 * @returns {string}
		 */
		getRoute:function()
		{
			return this.method
				? this.method.route + this.method.params.map(function (p) { return p.value; }).join('/')
				: this.controller
					? this.controller.route
					: '';
		},


	// ------------------------------------------------------------------------------------------------
	// internal update properties

		/**
		 * Update the current method's parameters
		 *
		 * @param params
		 */
		updateParams:function(params)
		{
			var method = this.method;
			if(method)
			{
				params.forEach(function (value, index)
				{
					var param = method.params[index];
					if (param)
					{
						param.value = value;
					}
				});
			}
		},

		/**
		 * Update the current route string
		 */
		updateRoute:function()
		{
			// route
			this.route 		= this.getRoute();

			// title
			var title		= 'Sketchpad';
			if(this.route)
			{
				title += ' - ' + this.route.replace(this.base, '');
			}
			document.title = title.replace(/\/$/, '').replace(/\//g, ' ▸ ');
		},

		/**
		 * Update the HTML5 history state
		 *
		 * @param push
		 */
		updateHistory:function(push)
		{
			history[push ? 'pushState' : 'replaceState'](this.getState(), document.title, this.route);
		}

};
;window.Store = Vue.extend({

	data:function()
	{
		// object with single controllers property
		var data 	= JSON.parse($('#data').text());

		// state object
		data.state 	= this.$options.state || state;

		// return
		return data;
	},

	created:function()
	{
		if(LiveReload)
		{
			// server
			this.server = this.$options.server || server;

			// proxies
			var reload 	= LiveReload.reloader.reload;
			var self	= this;

			// monkeypatch livereloader
			LiveReload.reloader.reload = function(path, options)
			{
				if(self.reload(path))
				{
					return;
				}
				return reload.call(this, path, options);
			};
		}
	},

	methods:
	{

		// ------------------------------------------------------------------------------------------------
		// loading

			/**
			 * Delegated livereload function
			 *
			 * @param path
			 * @returns {boolean}
			 */
			reload:function(path)
			{
				// intercept controller updates
				if (/Controller\.php$/.test(path))
				{
					var controller = this.getControllerByPath(path);
					if(controller)
					{
						this.server.load(':load/' + path, this.onLoad);
					}
					return true;
				}

				// php file
				if(/\.php$/.test(path))
				{
					this.dispatch('file');
					return true;
				}

				// let LiveReload handle the load
				return false;
			},

			/**
			 * Called when AJAX response with new controller data comes back from server
			 *
			 * @param data
			 */
			onLoad:function(data)
			{
				if(data && data.path)
				{
					// check for existing controller
					var controller = this.getControllerByPath(data.path);

					// insert if the controller exists
					if(controller)
					{
						// update store
						var index = this.controllers.indexOf(controller);
						this.controllers.$set(index, data);

						// update state if current controller was reloaded
						if(this.state.controller == controller)
						{
							var methodIndex = this.state.controller.methods.indexOf(this.state.method);
							this.state.controller = data;
							if(methodIndex > -1)
							{
								this.state.method = this.state.controller.methods[methodIndex];
							}
						}
					}

					// append and sort if not
					else
					{
						this.controllers.push(data);
						this.controllers.sort(function(a, b)
						{
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

					// dispatch
					this.dispatch('controller', data.path);
				}
			},


		// ------------------------------------------------------------------------------------------------
		// utilities

			getControllerByPath:function(path)
			{
				return this.controllers.filter(function(c){ return c.path == path; }).shift();
			},

			dispatch:function(type, path)
			{
				this.$dispatch('load', {type:type, path:path});
			}

	}

});


var server 	= new Server();

var store 	= new Store({
	server:server,
	state:state});

var app 	= new App({
	server:server,
	state:state,
	store:store});
