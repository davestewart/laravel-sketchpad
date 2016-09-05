Vue.component('navigation', {

	template:'#navigation-template',

	props:
	[
		'controllers',
		'state'
	],

	filters:
	{
		humanize:Helpers.humanize
	},

	ready:function()
	{
		this.$watch('state.controller', function ()
		{
			//$(this.$el).find('a[title]').tooltip({container:'body', trigger:'hover', placement:'right', delay: { "show": 500, "hide": 100 }})
		});

	},

	methods:
	{
		getLabel:function(obj)
		{
			return Helpers.getControllerLabel(obj);
		},
		
		getLinkHtml:function(route)
		{
			var name 	= '<span class="name">';
			var divider	= '<span class="divider">&#9656;</span> ';
			var close	= '</span> ';

			return name + route
				.replace('/sketchpad/', '')
				.replace(/\/$/, '')
				.split('/')
				.join(close + divider + name) + close;
		},

		isActive:function(route)
		{
			return this.state.route && this.state.route.indexOf(route) == 0;
		}

	}

});