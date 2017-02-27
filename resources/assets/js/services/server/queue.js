/**
 * Queue class
 *
 * Prevents flooding the server with calls, and ensures only the last-called call completes
 *
 * As such, any queue can have a maximum of 2 calls in it; the currently-calling call,
 * and any queued call
 *
 * Only the final call in any queue will have its handlers called on done, fail and always
 *
 * @constructor
 */
export default function Queue(method = 'post')
{
	this.method = method;
	this.requests = [];
	window.queue = this;
}

Queue.prototype =
{
	method  :'post',

	requests: null,

	add(request)
	{
		// only ever allow 2 requests in the queue; the current one and the next one
		if(this.requests.length > 1)
		{
			this.requests.length = 1;
		}
		this.requests.push(request);

		// only ever run if there is one request in the queue
		// if there are 2, the newly-added request will be loaded on completion of teh first one
		if(this.requests.length == 1)
		{
			this.next();
		}

		return request;
	},

	next()
	{
		let request = this.requests[0];
		if(request)
		{
			const method = $[this.method];
			request.deferred = method(request.url, {data:request.data})
				.done( (data, status, xhr) =>
				{
					this.requests.shift();
					this.requests.length == 0
						? request.done(data, status, xhr)
						: this.next();
				})
				.fail( (xhr, status, message) =>
				{
					this.requests.shift();
					this.requests.length == 0
						? request.fail(xhr, status, message)
						: this.next();
				})
				.always( () =>
				{
					if(this.requests.length == 0)
					{
						if(request.always)
						{
							request.always();
						}
					}
				});
		}

	}
};