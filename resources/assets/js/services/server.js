function Server()
{
	// setup base route
	this.base = $('meta[name="route"]').attr('content');
}

Server.prototype =
{

		base:'',

		requestId:0,

		requestCount:0,


	// ------------------------------------------------------------------------------------------------
	// methods

		/**
		 * Calls a sketchpad route and returns the result
		 *
		 * @param route			The full route, including the '/sketchpad/' portion
		 * @param onSuccess
		 * @param onFail
		 * @returns {*}
		 */
		call:function(route, onSuccess, onFail)
		{
			var url = location.origin + this.getCallUrl(route);
			return $
				.get(url, function(data, status, xhr){
					var requestId = xhr.getResponseHeader('X-Request-ID');
					if(requestId == null || requestId == this.requestId)
					{
						onSuccess(data, status, xhr);
					}
				}.bind(this))
				.fail(onFail);
		},

		/**
		 * Opens a sketchpad route in a new window
		 *
		 * @param url
		 */
		open:function(route)
		{
			window.open(this.getCallUrl(route));
		},

		/**
		 * Requests information from the server
		 *
		 * @param route			The partial route, from '/sketchpad/' onwards
		 * @param onSuccess
		 * @returns {*}
		 */
		load:function(route, onSuccess)
		{
			var url = this.base + route;
			return $.get(url, onSuccess);
		},

	// ------------------------------------------------------------------------------------------------
	// utilities

		getCallUrl:function(url)
		{
			return url.replace(/\/$/, '') + '?call=1&requestId=' + (++this.requestId);
		}

};
