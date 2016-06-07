Helpers =
{
	methodLabel:function(method)
	{
		return settings.useLabels
			? method.label
			? method.label
			: this.humanize(method.name)
			: method.name + '()';
	},

	humanize:function(input)
	{
		return input
			.replace(/_/g, ' ')
			.replace(/([a-z])([A-Z0-9])/g, '$1 $2')
			.toLowerCase();
	}

};

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

;window.State = Vue.extend(
{

	// ------------------------------------------------------------------------------------------------
	// properties

		data:function()
		{
			return {
				baseUrl		:$('meta[name="route"]').attr('content'),
				store		:this.$options.store,
				controller	:null,
				method		:null
			};
		},

		computed:
		{
			route:function()
			{
				return this.makeRoute(this.method, this.controller);
			}
		},

		props:['store'],


	// ------------------------------------------------------------------------------------------------
	// methods

		methods:
		{

			// ------------------------------------------------------------------------------------------------
			// public methods

				/**
				 * Set values from route string
				 *
				 * @param route
				 */
				setRoute:function(route)
				{
					// data
					var data 				= this.parseRoute(route);

					// state
					this.controller 		= data.controller;
					this.method 			= data.method;
					if(data.method && data.params)
					{
						data.params.forEach(function (value, index)
						{
							var param = data.method.params[index];
							if (param)
							{
								param.value = value;
							}
						});
					}

					// page
					var title		= 'Sketchpad - ' + this.route.replace(this.baseUrl, '');
					document.title 	= title.replace(/\/$/, '').replace(/\//g, ' ▸ ');
				},

				/**
				 * Rest all values
				 */
				reset:function()
				{
					this.controller = null;
					this.method 	= null;
				},


			// ------------------------------------------------------------------------------------------------
			// private methods

				/**
				 * Gets a Route instance from a route string
				 *
				 * @param 	{string}	[route]
				 * @returns {object}
				 */
				parseRoute:function(route)
				{
					// parameters
					route = route || location.href;
					route = route.replace(location.origin, '');

					// variables
					var controller, method, params;

					// assignments
					controller = this.store.controllers.filter(function(c) { return route.indexOf(c.route) == 0; }).shift();
					if(controller)
					{
						method = controller.methods.filter(function(m) { return route.indexOf(m.route) == 0; }).shift();
					}
					if(method)
					{
						params = route.replace(method.route, '').match(/[^\/]+/g);
					}

					// return
					return {controller:controller, method:method, params:params};
				},

				makeRoute:function(method, controller)
				{
					return method
						? method.route + method.params.map(function (p) { return p.value; }).join('/')
						: controller
							? controller.route
							: '';
				},

				getRoute:function(route)
				{

				}
		}


});
;window.Store = Vue.extend({

	data:function()
	{
		// object with single controllers property
		return JSON.parse($('#data').text());
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
					var index;

					// insert if the controller exists
					if(controller)
					{
						// update store
						index = this.controllers.indexOf(controller);
						this.controllers.$set(index, data);
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
					this.dispatch('controller', data.path, index);
				}
			},


		// ------------------------------------------------------------------------------------------------
		// utilities

			getControllerByPath:function(path)
			{
				return this.controllers.filter(function(c){ return c.path == path; }).shift();
			},

			dispatch:function(type, path, index)
			{
				this.$dispatch('load', {type:type, path:path, index:index});
			}

	}

});


var App = Vue.extend({

	el:function(){ return '#app'; },

	data:function()
	{
		return {
			store		:this.$options.store,
			state		:this.$options.state,
			settings	:this.$options.settings
		};
	},

	ready:function()
	{
		// objects
		this.server		= this.$options.server;

		// reloading
		this.store.$on('load', this.onStoreLoad);

		// links
		$('body').on('click', 'a[href^="/sketchpad/"]', this.onLinkClick);

		// routes
		this.router = new Router();
		this.router.route('/sketchpad/', this.onHome);
		this.router.route('/sketchpad/~/:view', this.onView);
		this.router.route('/sketchpad/*path', this.onRoute);
		//this.router.start();

		// page load
		this.run(location.pathname);

		// ui
		//$('#nav .sticky').sticky({topSpacing:20, bottomSpacing:20});
		//$('#params .sticky').sticky({topSpacing:20});
	},

	methods:
	{

		// ------------------------------------------------------------------------------------------------
		// methods

			run:function(route)
			{
				this.unwatch();
				this.state.setRoute(route);
				this.$refs.result.method = this.state.method;
				this.update(true);
				if(this.state.method)
				{
					this.watch();
				}
			},

			update:function(run)
			{
				this.state.method
					? this.$refs.result.load(run)
					: this.$refs.result.clear();
			},

			watch:function()
			{
				this.unwatch = this.$watch('state.method.params', this.onParamsChange, {deep:true});
			},

			unwatch:function()
			{
				// will be populate by $watch
			},


		// ------------------------------------------------------------------------------------------------
		// handlers

			onLinkClick:function(event)
			{
				event.preventDefault();
				var href = $(event.target).attr('href');
				this.router.navigate(href);
			},

			onRoute:function(route)
			{
				this.run(this.state.baseUrl + route);
			},

			onParamsChange:function()
			{
				var route = this.state.route;
				this.router.navigate(route, false, true);
				this.update();
			},

			onHome:function()
			{
				// need to do this with reactive properties
				$('#welcome').appendTo('#output').show();
				this.state.reset();
			},

			onView:function(params)
			{
				console.log('view:', params);
			},

			onStoreLoad:function(event)
			{
				if(this.state.controller && this.state.controller.path == event.path)
				{
					var cIndex 	= event.index;
					var mIndex	= this.state.method ? this.state.controller.methods.indexOf(this.state.method) : -1;
					if(cIndex > -1)
					{
						this.unwatch();
						this.state.controller = this.store.controllers[cIndex]
						if(mIndex > -1)
						{
							this.state.method = this.state.controller.methods[mIndex];
						}
						this.watch();
					}

					// reload
					this.update();
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

	filters:
	{
		humanize:Helpers.humanize
	},

	methods:
	{
		getLabel:function(method)
		{
			return Helpers.methodLabel(method);
		},
		
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
	
	props:['params', 'deferred'],

	methods:
	{

		run:function()
		{
			this.$dispatch('run');
		},

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

		getId:function(param)
		{
			return 'param-' + param.name;
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




var settings =
{
	useLabels:true
};

var server 	= new Server();

var store 	= new Store({
	server	:server
	});

var state 	= new State({
	store	:store
	});

var app 	= new App({
	settings:settings,
	server	:server,
	store	:store,
	state	:state
	});

