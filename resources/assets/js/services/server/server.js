import Queue from './queue';
import Request from './request';

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
			const route	= this.base + 'run/' + method.route;
			const data	= method.params.map( function(param)
			{
				let {name, type, value } = param;
				return {name, type, value}
			});
			return this.queue.add(new Request(route, data, done, fail, always));
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
		 * @param 	{string}	route			The partial route, from '/sketchpad/' onwards
		 * @param	{Function}	done
		 * @returns {*}
		 */
		load(route, done)
		{
			var url = this.base + route;
			return $.get(url, done);
		},

		loadController(path, onSuccess)
		{
			var url = 'load/' + path;
			return onSuccess
				? this.load(url, onSuccess)
				: window.open(this.base + url);
		}
};

export default new Server;




