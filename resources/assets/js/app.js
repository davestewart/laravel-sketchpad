
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
			this.route 		= controller.route;
			this.controller	= controller;
		},

		loadMethod:function(method, element, $http)
		{
			this.route 		= method.route;
			this.method		= method;

			var url = event.target.href.replace(/\/$/, '') + '?call=1';

			if(event.metaKey || event.ctrlKey)
			{
				window.open(url);
				return;
			}

			$.get(url, function(data)
			{
				this.showResult(data);
			}.bind(this));
		},

		showResult:function(data)
		{
			$('#output').html(data);
		}
	}

});
