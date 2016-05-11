Vue.component('navigation', {

	template:'#navigation-template',

	props:['controllers', 'controller'],

	methods:
	{

		// ------------------------------------------------------------------------------------------------
		// handlers

			onControllerClick:function(controller)
			{
				this.$dispatch('onNavClick', controller.route);
			},

			onMethodClick:function(method)
			{
				if(event.metaKey || event.ctrlKey)
				{
					return this.$root.server.open(event.target.href);
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