import Queue from './server/queue';
import Request from './server/request';

function Server()
{
	// setup base route
	this.base   = $('meta[name="route"]').attr('content');
	this.queue  = new Queue('post');
}

Server.prototype =
{

		base        : '',

		queue       : null,

	// ------------------------------------------------------------------------------------------------
	// methods

		/**
		 * Runs a sketchpad method and returns the result
		 *
		 * @param 	{Object}	method		A Method object with route and params properties
		 * @param 	{Function}	done
		 * @param 	{Function}	fail
		 * @param 	{Function}	always
		 * @returns {Request}
		 */
		run(method, done, fail, always)
		{
			const url	= this.getRunUrl(method);
			const data	= method.params.map( function(param)
			{
				let {name, type, value } = param;
				return {name, type, value}
			});
			return this.queue.add(new Request(url, data, done, fail, always));
		},

		/**
		 * Opens a sketchpad route in a new window
		 *
		 * @param route
		 * @param data
		 */
		open(route, data)
		{
			var request = new Request(route, data);
			window.open(request.url);
		},

		/**
		 * Requests information from the server
		 *
		 * Mainly used for :page/
		 *
		 * @param 	{string}	path			The partial route, from '/sketchpad/' onwards
		 * @param	{Function}	[done]          An optional onLoad handler
		 * @returns {Promise}
		 */
		load(path, done)
		{
			var url = this.getUrl(path);
			return $.get(url, done);
		},

		loadController(route, onSuccess)
		{
			var url = 'api/load/' + route;
			return onSuccess
				? this.load(url, onSuccess)
				: window.open(this.base + url);
		},

		post(path, data, done)
		{
			const url	= this.getUrl(path);
			return $.post(url, data, done);
		},

		getRunUrl(method)
		{
			return this.getUrl('api/run/' + method.route);
		},

		getUrl(path)
		{
			return this.base + path;
		}
};

export default new Server;




