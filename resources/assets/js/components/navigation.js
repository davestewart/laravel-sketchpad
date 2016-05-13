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
				var name 	= '<span class="name">';
				var divider	= ' <span class="divider">&#9656;</span> ';
				var close	= '</span>';

				return name + route
					.replace('/sketchpad/', '')
					.replace(/\/$/, '')
					.split('/')
					.join(close + divider + name) + close;
			},

			isActive:function(route)
			{
				return this.$parent.$data._route.indexOf(route) == 0;
			}

	}

});