Vue.component('method', {

	template:'#method-template',

	props:'method state'.split(' '),

	computed:
	{
		listClass:function()
		{
			var tags 	= this.tags;
			var state 	= this.state;
			var data 	=
			{
				active		:state.route && state.route.indexOf(this.route) == 0,
				favourite	:tags.favourite,
				icon		:tags.favourite || tags.icon
			};

			if(tags.css) {
				data[tags.css] = true;
			}
			if(tags.deferred) {
				data.icon = true;
				data['deferred'] = true;
			}
			if(tags.warning) {
				data.icon = true;
				data['warning'] = true;
			}
			if(tags.archived) {
				data['archived'] = true;
			}
			if(tags.icon) {
				var parts = tags.icon.split(/\s+/);
				data['fa-' + parts.pop()] = true;
			}
			return data;
		},

		listStyle:function()
		{
			var tags	= this.tags;
			var data 	= {};
			if(tags.icon) {
				var parts = tags.icon.split(/\s+/);
				if(parts.length == 2)
				{
					data.color = parts.shift();
				}
			}
			return data;
		},

		linkStyle:function()
		{
			var tags	= this.tags;
			var data 	= {};
			if(tags.color){
				data.color = tags.color;
			}
			return data;
		},

		name:function() { return this.method.name; },
		label:function() { return Helpers.getMethodLabel(this.method); },
		route:function() { return this.method.route; },
		error:function() { return this.method.error; },
		comment:function() { return this.method.comment; },
		tags:function() { return this.method.tags; }
	}

});