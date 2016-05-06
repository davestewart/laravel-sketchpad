
var vue = new Vue({

	el:'#app',

	data:JSON.parse($('#data').text()),

	methods:
	{

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
			return this.route.indexOf(route) === 0;
		},
		
		loadController:function(controller)
		{
			event.preventDefault();
			this.route 		= controller.route;
			this.controller	= controller;
		},

		loadMethod:function(method, element, $http)
		{
			event.preventDefault();
			this.route 		= method.route;

			var url = event.target.href.replace(/\/$/, '') + '?call=1';

			if(event.metaKey || event.ctrlKey)
			{
				window.open(url);
				return;
			}

			$.get(url, function(data){
				$('#output').html(data);
			});
		}
	}

});
