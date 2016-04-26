;(function($, window){

	// ------------------------------------------------------------------------------------------------
	// variables

		// elements
		var $nav;
		var $controllers;
		var $methods;
		var $result;
		var $info;

		// objects
		var doodle = window.Doodle = {};
		var server;
		var router;

		// data
		var controller;
		var method;


	// ------------------------------------------------------------------------------------------------
	// functions

		function Server(route)
		{

			this.load = function (path, callback, data)
			{
				$.getJSON(route + path, data, callback);
			};

			this.run = function (url, callback)
			{
				$.get(url + '?run=1');
				
				$.get(route + path, data, callback);
			};

		}

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

		function updateRoutes(data)
		{
			// loop over data and build routes
		}

		function updateMethods(data)
		{
			// loop over data and build methods
		}

	
	// ------------------------------------------------------------------------------------------------
	// handlers

		function onRouteClick(event)
		{
			// event
			event.preventDefault();

			// variables
			var $link   = updateList(this);
			var url     = $link.attr('href');

			// load
			server.load(':load/' + url, function(data)
			{

			});

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
			var url     	= $link.attr('href');

			// update output
			var index   	= $link.parent().index();
			var method  	= controller.methods[index];
			var comment 	= method.comment.intro;

			// load code
			server.run(url, function(html)
			{
				// update UI
				$info.find('h1').text($link.text());
				$info.find('p').text(comment);

				// update output
				$result.html(html);
			});
		}

		doodle.onFileChanged = function(file)
		{
			console.log('file changed:', file);
		};


	// ------------------------------------------------------------------------------------------------
	// setup

		function setupServer()
		{
			server = new Server(window.route);
		}

		function setupRouter()
		{
			// clear and reset history
			if(History.getCurrentIndex() !== 0)
			{
				window.location.replace('/doodles/');
			}

			// routing
			var router = new Router();
			router.route('/*path', function(path)
			{
				console.log(arguments);
				//$('#target').attr('src', '/' + window.location.hash.substr(1));
				//$('#target').attr('src', path);
			});
			router.start();
		}

		function setupNav()
		{
			$nav.on('click', 'a.route', onRouteClick);
			$nav.on('click', 'a.method', onMethodClick);
		}

		function onReady()
		{
			// elements
			$nav        	= $('#nav');
			$controllers	= $('#controllers');
			$methods    	= $('#methods');
			$result     	= $('#result');
			$info       	= $('#info');

			// data
			controller		= null;
			server			= window.remote || 'doodles/';

			// start
			//setupRouter();
			setupServer();
			setupNav();
		}
		
	
	// ------------------------------------------------------------------------------------------------
	// start
		
		$(onReady);
	
}(jQuery, window));
