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

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImRvb2RsZS5qcyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFBQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EiLCJmaWxlIjoiZG9vZGxlLmpzIiwic291cmNlc0NvbnRlbnQiOlsiOyhmdW5jdGlvbigkLCB3aW5kb3cpe1xuXG5cdC8vIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxuXHQvLyB2YXJpYWJsZXNcblxuXHRcdC8vIGVsZW1lbnRzXG5cdFx0dmFyICRuYXY7XG5cdFx0dmFyICRjb250cm9sbGVycztcblx0XHR2YXIgJG1ldGhvZHM7XG5cdFx0dmFyICRyZXN1bHQ7XG5cdFx0dmFyICRpbmZvO1xuXG5cdFx0Ly8gb2JqZWN0c1xuXHRcdHZhciBkb29kbGUgPSB3aW5kb3cuRG9vZGxlID0ge307XG5cdFx0dmFyIHNlcnZlcjtcblx0XHR2YXIgcm91dGVyO1xuXG5cdFx0Ly8gZGF0YVxuXHRcdHZhciBjb250cm9sbGVyO1xuXHRcdHZhciBtZXRob2Q7XG5cblxuXHQvLyAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cblx0Ly8gZnVuY3Rpb25zXG5cblx0XHRmdW5jdGlvbiBTZXJ2ZXIocm91dGUpXG5cdFx0e1xuXG5cdFx0XHR0aGlzLmxvYWQgPSBmdW5jdGlvbiAocGF0aCwgY2FsbGJhY2ssIGRhdGEpXG5cdFx0XHR7XG5cdFx0XHRcdCQuZ2V0SlNPTihyb3V0ZSArIHBhdGgsIGRhdGEsIGNhbGxiYWNrKTtcblx0XHRcdH07XG5cblx0XHRcdHRoaXMucnVuID0gZnVuY3Rpb24gKHVybCwgY2FsbGJhY2spXG5cdFx0XHR7XG5cdFx0XHRcdCQuZ2V0KHVybCArICc/cnVuPTEnKTtcblx0XHRcdFx0XG5cdFx0XHRcdCQuZ2V0KHJvdXRlICsgcGF0aCwgZGF0YSwgY2FsbGJhY2spO1xuXHRcdFx0fTtcblxuXHRcdH1cblxuXHRcdGZ1bmN0aW9uIHVwZGF0ZUxpc3QoZWxlbWVudClcblx0XHR7XG5cdFx0XHR2YXIgJGVsZW1lbnQgPSAkKGVsZW1lbnQpO1xuXHRcdFx0JGVsZW1lbnRcblx0XHRcdFx0LnBhcmVudCgpXG5cdFx0XHRcdC5hZGRDbGFzcygnYWN0aXZlJylcblx0XHRcdFx0LnNpYmxpbmdzKClcblx0XHRcdFx0LnJlbW92ZUNsYXNzKCdhY3RpdmUnKTtcblx0XHRcdHJldHVybiAkZWxlbWVudDtcblx0XHR9XG5cblx0XHRmdW5jdGlvbiB1cGRhdGVSb3V0ZXMoZGF0YSlcblx0XHR7XG5cdFx0XHQvLyBsb29wIG92ZXIgZGF0YSBhbmQgYnVpbGQgcm91dGVzXG5cdFx0fVxuXG5cdFx0ZnVuY3Rpb24gdXBkYXRlTWV0aG9kcyhkYXRhKVxuXHRcdHtcblx0XHRcdC8vIGxvb3Agb3ZlciBkYXRhIGFuZCBidWlsZCBtZXRob2RzXG5cdFx0fVxuXG5cdFxuXHQvLyAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cblx0Ly8gaGFuZGxlcnNcblxuXHRcdGZ1bmN0aW9uIG9uUm91dGVDbGljayhldmVudClcblx0XHR7XG5cdFx0XHQvLyBldmVudFxuXHRcdFx0ZXZlbnQucHJldmVudERlZmF1bHQoKTtcblxuXHRcdFx0Ly8gdmFyaWFibGVzXG5cdFx0XHR2YXIgJGxpbmsgICA9IHVwZGF0ZUxpc3QodGhpcyk7XG5cdFx0XHR2YXIgdXJsICAgICA9ICRsaW5rLmF0dHIoJ2hyZWYnKTtcblxuXHRcdFx0Ly8gbG9hZFxuXHRcdFx0c2VydmVyLmxvYWQoJzpsb2FkLycgKyB1cmwsIGZ1bmN0aW9uKGRhdGEpXG5cdFx0XHR7XG5cblx0XHRcdH0pO1xuXG5cdFx0fVxuXG5cdFx0ZnVuY3Rpb24gb25NZXRob2RDbGljayhldmVudClcblx0XHR7XG5cdFx0XHQvLyBldmVudFxuXHRcdFx0aWYoZXZlbnQuY3RybEtleSlcblx0XHRcdHtcblx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0fVxuXHRcdFx0ZXZlbnQucHJldmVudERlZmF1bHQoKTtcblxuXHRcdFx0Ly8gdmFyaWFibGVzXG5cdFx0XHR2YXIgJGxpbmsgICBcdD0gdXBkYXRlTGlzdCh0aGlzKTtcblx0XHRcdHZhciB1cmwgICAgIFx0PSAkbGluay5hdHRyKCdocmVmJyk7XG5cblx0XHRcdC8vIHVwZGF0ZSBvdXRwdXRcblx0XHRcdHZhciBpbmRleCAgIFx0PSAkbGluay5wYXJlbnQoKS5pbmRleCgpO1xuXHRcdFx0dmFyIG1ldGhvZCAgXHQ9IGNvbnRyb2xsZXIubWV0aG9kc1tpbmRleF07XG5cdFx0XHR2YXIgY29tbWVudCBcdD0gbWV0aG9kLmNvbW1lbnQuaW50cm87XG5cblx0XHRcdC8vIGxvYWQgY29kZVxuXHRcdFx0c2VydmVyLnJ1bih1cmwsIGZ1bmN0aW9uKGh0bWwpXG5cdFx0XHR7XG5cdFx0XHRcdC8vIHVwZGF0ZSBVSVxuXHRcdFx0XHQkaW5mby5maW5kKCdoMScpLnRleHQoJGxpbmsudGV4dCgpKTtcblx0XHRcdFx0JGluZm8uZmluZCgncCcpLnRleHQoY29tbWVudCk7XG5cblx0XHRcdFx0Ly8gdXBkYXRlIG91dHB1dFxuXHRcdFx0XHQkcmVzdWx0Lmh0bWwoaHRtbCk7XG5cdFx0XHR9KTtcblx0XHR9XG5cblx0XHRkb29kbGUub25GaWxlQ2hhbmdlZCA9IGZ1bmN0aW9uKGZpbGUpXG5cdFx0e1xuXHRcdFx0Y29uc29sZS5sb2coJ2ZpbGUgY2hhbmdlZDonLCBmaWxlKTtcblx0XHR9O1xuXG5cblx0Ly8gLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXG5cdC8vIHNldHVwXG5cblx0XHRmdW5jdGlvbiBzZXR1cFNlcnZlcigpXG5cdFx0e1xuXHRcdFx0c2VydmVyID0gbmV3IFNlcnZlcih3aW5kb3cucm91dGUpO1xuXHRcdH1cblxuXHRcdGZ1bmN0aW9uIHNldHVwUm91dGVyKClcblx0XHR7XG5cdFx0XHQvLyBjbGVhciBhbmQgcmVzZXQgaGlzdG9yeVxuXHRcdFx0aWYoSGlzdG9yeS5nZXRDdXJyZW50SW5kZXgoKSAhPT0gMClcblx0XHRcdHtcblx0XHRcdFx0d2luZG93LmxvY2F0aW9uLnJlcGxhY2UoJy9kb29kbGVzLycpO1xuXHRcdFx0fVxuXG5cdFx0XHQvLyByb3V0aW5nXG5cdFx0XHR2YXIgcm91dGVyID0gbmV3IFJvdXRlcigpO1xuXHRcdFx0cm91dGVyLnJvdXRlKCcvKnBhdGgnLCBmdW5jdGlvbihwYXRoKVxuXHRcdFx0e1xuXHRcdFx0XHRjb25zb2xlLmxvZyhhcmd1bWVudHMpO1xuXHRcdFx0XHQvLyQoJyN0YXJnZXQnKS5hdHRyKCdzcmMnLCAnLycgKyB3aW5kb3cubG9jYXRpb24uaGFzaC5zdWJzdHIoMSkpO1xuXHRcdFx0XHQvLyQoJyN0YXJnZXQnKS5hdHRyKCdzcmMnLCBwYXRoKTtcblx0XHRcdH0pO1xuXHRcdFx0cm91dGVyLnN0YXJ0KCk7XG5cdFx0fVxuXG5cdFx0ZnVuY3Rpb24gc2V0dXBOYXYoKVxuXHRcdHtcblx0XHRcdCRuYXYub24oJ2NsaWNrJywgJ2Eucm91dGUnLCBvblJvdXRlQ2xpY2spO1xuXHRcdFx0JG5hdi5vbignY2xpY2snLCAnYS5tZXRob2QnLCBvbk1ldGhvZENsaWNrKTtcblx0XHR9XG5cblx0XHRmdW5jdGlvbiBvblJlYWR5KClcblx0XHR7XG5cdFx0XHQvLyBlbGVtZW50c1xuXHRcdFx0JG5hdiAgICAgICAgXHQ9ICQoJyNuYXYnKTtcblx0XHRcdCRjb250cm9sbGVyc1x0PSAkKCcjY29udHJvbGxlcnMnKTtcblx0XHRcdCRtZXRob2RzICAgIFx0PSAkKCcjbWV0aG9kcycpO1xuXHRcdFx0JHJlc3VsdCAgICAgXHQ9ICQoJyNyZXN1bHQnKTtcblx0XHRcdCRpbmZvICAgICAgIFx0PSAkKCcjaW5mbycpO1xuXG5cdFx0XHQvLyBkYXRhXG5cdFx0XHRjb250cm9sbGVyXHRcdD0gbnVsbDtcblx0XHRcdHNlcnZlclx0XHRcdD0gd2luZG93LnJlbW90ZSB8fCAnZG9vZGxlcy8nO1xuXG5cdFx0XHQvLyBzdGFydFxuXHRcdFx0Ly9zZXR1cFJvdXRlcigpO1xuXHRcdFx0c2V0dXBTZXJ2ZXIoKTtcblx0XHRcdHNldHVwTmF2KCk7XG5cdFx0fVxuXHRcdFxuXHRcblx0Ly8gLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXG5cdC8vIHN0YXJ0XG5cdFx0XG5cdFx0JChvblJlYWR5KTtcblx0XG59KGpRdWVyeSwgd2luZG93KSk7XG4iXSwic291cmNlUm9vdCI6Ii9zb3VyY2UvIn0=
