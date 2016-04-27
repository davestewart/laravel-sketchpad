;(function($, window){

	// ------------------------------------------------------------------------------------------------
	// variables

		// elements
		var $body;
		var $nav;
		var $controllers;
		var $methods;
		var $result;
		var $info;

		// objects
		var sketchpad = window.Sketchpad = {};
		var server;
		var router;

		// data
		var controller;
		var method;


	// ------------------------------------------------------------------------------------------------
	// objects

		function Server()
		{
			var route = $('meta[name="route"]').attr('content');

			this.json = function (url, callback)
			{
				$.getJSON(url + '?json=1', callback);
			};

			this.html = function (url, callback)
			{
				$.get(url + '?html=1', callback);
			};

			this.run = function (url, callback)
			{
				$.get(url, callback);
			};

		}


	// ------------------------------------------------------------------------------------------------
	// functions

		function setMode(mode)
		{
			$body.attr('data-mode', mode);
			console.log(mode);

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
			$(controller.methods).each(function(i, e){
				console.log(e);
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
				$result.html(html);
			});
		}

		function onControllerClick(event)
		{
			// event
			event.preventDefault();

			// variables
			var $link   = updateList(this);
			var url     = $link.attr('href');
			var name	= $link.data('name');

			// load
			server.json(url, function(data)
			{
				controller = data;
				//updateMethods();
			});

			// load
			server.html(url, function(html)
			{
				setMode('code');
				setTitle(controller.class, controller.comment.intro);
				$result.empty();
				$methods.html(html);
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
			var title		= $link.text();
			var comment 	= method.comment.intro;

			// history
			//router.navigate(url);

			// load code
			server.run(url, function(html)
			{
				setTitle(title, comment);
				$result.html(html);
			});
		}

		sketchpad.onFileChanged = function(file)
		{
			console.log('file changed:', file);
		};


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
			$result     	= $('#result');
			$info       	= $('#info');

			// data
			controller		= JSON.parse($('#controller').text());
			server			= new Server();

			// start
			//setupRouter();
			setupNav();
		}
		
	
	// ------------------------------------------------------------------------------------------------
	// start
		
		$(onReady);
	
}(jQuery, window));
