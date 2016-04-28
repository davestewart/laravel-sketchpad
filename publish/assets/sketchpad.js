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

		// data
		var controller;
		var method;
		var lastMethod;


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

		window.setIframeHeight = function(height)
		{
			$output.find('iframe').height(height);
		};

	
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
			var name	= $link.data('name');

			// load
			server.json(url, function(data)
			{
				controller = data;
				console.log(controller);
				//updateMethods();

				server.html(url, function(html)
				{
					setMode('code');
					setTitle(controller.class, controller.comment.intro);
					$output.empty();
					$methods.html(html);
				});
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
			var format		= method.comment.tags.format || null;

			// history
			//router.navigate(url);

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
		}
		
	
	// ------------------------------------------------------------------------------------------------
	// start
		
		$(onReady);
	
}(jQuery, window));
