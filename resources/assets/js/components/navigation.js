Vue.component('navigation', {

	template:'#navigation-template',

	props:['controllers', 'controller'],

	ready:function()
	{
		$('#nav .sticky').sticky({topSpacing:20});
	},

	methods:
	{

		// ------------------------------------------------------------------------------------------------
		// handlers

			onControllerClick:function(controller)
			{
				this.$dispatch('onNavClick', controller.route);
			},

			onMethodClick:function(method, element, $http)
			{
				if(event.metaKey || event.ctrlKey)
				{
					return server.open(event.target.href);
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