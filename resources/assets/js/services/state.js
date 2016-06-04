state =
{

	// ------------------------------------------------------------------------------------------------
	// properties

		route		:'',
		controller	:null,
		method		:null,
		base		:$('meta[name="route"]').attr('content'),


	// ------------------------------------------------------------------------------------------------
	// public setters

		/**
		 * Set multiple values using a Route instance, optionally adding to history
		 *
		 * @param route
		 * @param updateHistory
		 */
		setRoute:function(route, updateHistory)
		{
			// state
			this.route		= route.route;
			this.controller	= route.controller;
			this.method		= route.method;
			if(route.params)
			{
				this.updateParams(route.params);
			}

			// history
			if(updateHistory)
			{
				this.updateHistory(true);
			}

			// route
			this.updateRoute();
		},

		/**
		 * Set params only, and replace history
		 *
		 * @param params
		 */
		setParams:function(params)
		{
			this.updateParams(params);
			this.updateRoute();
			this.updateHistory();
		},

		/**
		 * Get the current state values as an object
		 *
		 * @returns {{route: *, controller: *, method: *}}
		 */
		getState:function()
		{
			return {
				route		:this.route,
				controller	:this.controller,
				method		:this.method
			};
		},

		/**
		 * Gets a route string based on the values of the current controller, method
		 *
		 * @returns {string}
		 */
		getRoute:function()
		{
			return this.method
				? this.method.route + this.method.params.map(function (p) { return p.value; }).join('/')
				: this.controller
					? this.controller.route
					: '';
		},


	// ------------------------------------------------------------------------------------------------
	// internal update properties

		/**
		 * Update the current method's parameters
		 *
		 * @param params
		 */
		updateParams:function(params)
		{
			var method = this.method;
			if(method)
			{
				params.forEach(function (value, index)
				{
					var param = method.params[index];
					if (param)
					{
						param.value = value;
					}
				});
			}
		},

		/**
		 * Update the current route string
		 */
		updateRoute:function()
		{
			// route
			this.route 		= this.getRoute();

			// title
			var title		= 'Sketchpad';
			if(this.route)
			{
				title += ' - ' + this.route.replace(this.base, '');
			}
			document.title = title.replace(/\/$/, '').replace(/\//g, ' ▸ ');
		},

		/**
		 * Update the HTML5 history state
		 *
		 * @param push
		 */
		updateHistory:function(push)
		{
			history[push ? 'pushState' : 'replaceState'](this.getState(), document.title, this.route);
		}

};