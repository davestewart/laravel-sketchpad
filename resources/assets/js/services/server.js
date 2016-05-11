function Server()
{
	// setup base route
	this.base = $('meta[name="route"]').attr('content');
}

Server.prototype =
{

		base:'',

		requestId:0,

		count:0,


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
		call:function(route, onSuccess, onFailure)
		{
			var url = location.origin + this.getCallUrl(route);
			this.count++;
			return $
				.get(url)
				.done(this.getSuccessCallback(onSuccess))
				.fail(this.getFailureCallback(onFailure));
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

		isLastRequest:function(xhr)
		{
			this.count--;
			return this.requestId == xhr.getResponseHeader('X-Request-ID');
		},

		getSuccessCallback:function(callback)
		{
			return function(data, status, xhr)
			{
				if(this.isLastRequest(xhr))
				{
					callback(data, status, xhr);
				}
			}.bind(this);
		},

		getFailureCallback:function(callback)
		{
			return function(xhr, status, message)
			{
				if(this.isLastRequest(xhr))
				{
					callback(xhr, status, message);
				}
			}.bind(this);
		},

		getCallUrl:function(url)
		{
			return url.replace(/\/$/, '') + '?call=1&requestId=' + (++this.requestId);
		}

};
