
console.log(data.route);

var vm = new Vue({

	el:'#app',

	data:function(){
		var data = JSON.parse($('#data').text());
		data.controller = null;
		data.method = null;
		data._route = null;
		data.loading = false;
		return data;
	},

	init:function(){
		console.log('>', this.data);
	},

	ready:function(){

		// update app
		this.updateRoute();

		// history
		History.Adapter.bind(window,'statechange',function(){ // Note: We are using statechange instead of popstate
			var State = History.getState(); // Note: We are using History.getState() instead of event.state
		});

		window.onpopstate = this.popHistory;
	},

	computed:{

		route:{
			get:function () {
				return this.$data._route;
			},
			set:function (route) {
				this.setRoute(route);
			}
		}

	},

	methods:
	{

		getController:function (route) {
			var arr = this.controllers.filter(function(e){
				return route.indexOf(e.route) == 0;
			});
			return arr ? arr[0] : null;
		},

		getMethod:function (route, controller) {
			controller = controller || this.controller;
			if(controller)
			{
				var arr = controller.methods.filter(function(e){
					return route.indexOf(e.route) == 0;
				});
				return arr ? arr[0] : null;
			}
		},

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
			var _route = String(this.$data._route);
			return _route.indexOf(route) === 0;
		},

		setRoute:function(route)
		{
			this.$data._route = route;
			this.controller = this.getController(route);
			var method = this.getMethod(route);
			if(method)
			{
				this.runMethod(method)
			}
		},

		updateRoute:function(event)
		{
			this.route = window.location.pathname;
		},

		popHistory:function(event)
		{
			var state = History.getStateById(event.state);
			if(state)
			{
				document.title = state.title;
			}
			this.updateRoute();
		},

		loadController:function(controller)
		{
			History.pushState({controller:this.controller, method:null}, this.getTitle(controller.route), controller.route); // logs {state:3}, "State 3", "?state=3"
			this.route = controller.route;
		},

		loadMethod:function(method, element, $http)
		{
			if(event.metaKey || event.ctrlKey)
			{
				return window.open(this.getCallUrl(event.target.href));
			}
			History.pushState({controller:this.controller, method:method}, this.getTitle(method.route), method.route);
			this.route = method.route;
		},

		runMethod:function(method)
		{
			var url = this.getCallUrl(location.origin + method.route);
			var _this = this;
			this.loading = true;
			$.get(url, function(data)
			{
				_this.loading = false;
				_this.method = method;
				_this.showResult(data);
			});
		},

		getTitle:function(route)
		{
			return 'Sketchpad - ' + this.getRelativeRoute(route);
		},

		getRelativeRoute:function(route)
		{
			var base = $('meta[name="route"]').attr('content');
			return route.substr(base.length);
		},

		getCallUrl:function(url)
		{
			return url.replace(/\/$/, '') + '?call=1';
		},

		showResult:function(data)
		{
			$('#output').html(data);
		}
	}

});

;(function($, window){

	// ------------------------------------------------------------------------------------------------
	// variables

		// elements
		var $body;
		var $nav;
		var $controllers;
		var $methods;
		var $output;
		var $info;

		// objects
		var sketchpad = window.Sketchpad = {};
		var server;
		var router;
		var reloader;

		// data
		var data;
		var controller;
		var method;

		var active =
		{
			controller	:null,
			method		:null,
			route		:''
		};


	// ------------------------------------------------------------------------------------------------
	// objects

		function Server()
		{
			var route = $('meta[name="route"]').attr('content');

			this.json = function (url, callback)
			{
				$.getJSON(url + '?json=1', null, callback);
			};

			this.html = function (url, callback)
			{
				$.get(url + '?html=1', callback);
			};

			this.run = function (url, success, fail)
			{
				// set loading
				$output.addClass('loading');

				// make the call
				$
					.get(url, success)
					.fail(fail)
					.always(function(){
						$output.removeClass('loading');
					});
			};

		}

		/**
		 * Live reloader
		 * 
		 * @see https://github.com/hiddentao/gulp-server-livereload/
		 */
		function LiveReloader(window)
		{
			// proxy original livereload function
			this.onFileChanged = window._onLiveReloadFileChanged;
			
			/**
			 * The proxied callback
			 * @param {Object} file 	An object with path, name and ext properties
			 */
			window._onLiveReloadFileChanged = function(file)
			{
				// debug
				// console.log('file changed:', file);

				// intercept controller updates
				if(/Controller\.php/.test(file.path))
				{
					reloadController(file.path);
					return false;
				}
				else
				{
					this.onFileChanged(file);
				}

			}.bind(this)
		}




	// ------------------------------------------------------------------------------------------------
	// functions

		function setMode(mode)
		{
			$body.attr('data-mode', mode);
		}

		function setTitle(title, comment)
		{
			comment = comment === null
						? '&elipsis;'
						: comment === ''
							? '&nbsp;'
							: comment;
			$info.find('h1').text(title);
			$info.find('.info').html(comment);
		}

		function setFormat(format)
		{
			$output.attr('data-format', format);
		}

		function loadIframe(xhr)
		{
			var text	= xhr.responseText;
			var type	= xhr.getResponseHeader('Content-Type');
			//var script	= '<script>var b=document.body,h=document.documentElement;parent.setIframeHeight(Math.max(b.scrollHeight,b.offsetHeight,h.clientHeight,h.scrollHeight,h.offsetHeight));</script>';
			var src		= 'data:' + type + ',' + encodeURIComponent(text);
			var $iframe = $('<iframe class="error" frameborder="0">');

			$output.empty().append($iframe);
			$iframe.attr('src', src);
		}

		window.setIframeHeight = function(height)
		{
			$output.find('iframe').height(height);
		};

		function updateList(element)
		{
			var $element = $(element);
			$element
				.parent()
				.addClass('active')
				.siblings()
				.removeClass('active');
			return $element;
		}


	// ------------------------------------------------------------------------------------------------
	// data functions

		function loadController(url)
		{
			// load
			server.json(url, function(data)
			{
				// update
				controller 			= data;
				active.controller	= controller;

				// debug
				console.log(controller);

				// load already-defined view (need to replace this soon)
				server.html(url, function(html)
				{
					// update UI
					setMode('code');
					setTitle(controller.class, controller.comment.intro);
					$output.empty();
					$methods.html(html);

					// check to see if the new controller contains the active route
					var methods 		= controller.methods.filter(function (e){ return active.method && e.route === active.method.route; });
					if(methods.length)
					{
						active.method = methods[0];
						loadMethod(active.method);

						var $m = $methods.find('a[href="' +active.method.route+ '"]');

						updateList($m.get(0));
					}

				});
			});
		}

		function reloadController(path)
		{
			//var controllers = data.filter(function (e){ return active.controller && e.path === path; });
			if(controller.path == path)
			{
				loadController(controller.route);
			}
		}

		function loadMethod(method)
		{
			// update output
			var url     	= method.route;
			var title		= method.label;
			var comment 	= method.comment.intro;
			var format		= method.comment.tags.format || null;

			// history
			//router.navigate(url);

			active.method	= method;

			setTitle(title, comment);

			// load code
			server.run(url, function(data, status, xhr)
			{
				//console.log([status, xhr.getAllResponseHeaders(), xhr]);

				setFormat(format);

				if(format == 'html')
				{
					loadIframe(xhr);
					return;
				}

				var contentType = xhr.getResponseHeader('Content-Type');
				//console.log(contentType);

				if(contentType === 'application/json')
				{
					setFormat('json');
					$($output).JSONView(data);
					return;
				}


				$output.html(data);

			}, function(xhr, status, message)
			{
				setFormat('error');
				loadIframe(xhr);
			});
		}

	
	// ------------------------------------------------------------------------------------------------
	// handlers

		function onCommandClick(event)
		{
			// event
			event.preventDefault();

			// variables
			var $link 	= $(this);
			var title 	= $link.attr('title');
			var url 	= $link.attr('href');

			// load
			server.html(url, function(html)
			{
				setMode('help');
				setTitle(title, '');
				$output.html(html);
			});
		}

		function onControllerClick(event)
		{
			// event
			event.preventDefault();

			// variables
			var $link   = updateList(this);
			var url     = $link.attr('href');
			loadController(url, 'url');
		}

		function onMethodClick(event)
		{
			// event
			if(event.ctrlKey)
			{
				return;
			}
			event.preventDefault();

			// variables
			var $link   	= updateList(this);
			var index   	= $link.parent().index();
			var method  	= controller.methods[index];
			loadMethod(method);
		}

	// ------------------------------------------------------------------------------------------------
	// setup

		function setupRouter()
		{
			// clear and reset history
			if(History.getCurrentIndex() !== 0)
			{
				window.location.replace('/sketchpad/');
			}

			// routing
			router = new Router();
			router.route('*path', function(path)
			{
				var $a = $nav.find('a[href="' +path+ '"]');
				console.log($a);
				if($a.length)
				{
					updateList($a);
				}
				console.log(arguments);
				//$('#target').attr('src', '/' + window.location.hash.substr(1));
				//$('#target').attr('src', path);
			});
			router.start();
		}

		function setupNav()
		{
			$nav
				.on('click', 'a.controller', onControllerClick)
				.on('click', 'a.method', onMethodClick);
			$body
				.on('click', 'a.command', onCommandClick);
		}

	
		function onReady()
		{
			// elements
			$body        	= $('body');
			$nav        	= $('#nav');
			$controllers	= $('#controllers');
			$methods    	= $('#methods');
			$output     	= $('#output');
			$info       	= $('#info');

			// data
			var json		= $.trim($('#controller').text());
			if(json)
			{
				controller		= JSON.parse(json);
				server			= new Server();

				// start
				//setupRouter();
				setupNav();
			}

			// livereload
			if(window._onLiveReloadFileChanged instanceof Function)
			{
				reloader = new LiveReloader(window);
			}
		}
		
	
	// ------------------------------------------------------------------------------------------------
	// start
		
		onReady();
	
}(jQuery, window));
