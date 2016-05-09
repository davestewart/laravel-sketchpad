function Server()
{
	// setup base route
	this.base = $('meta[name="route"]').attr('content');
}

Server.prototype =
{

	base:'',

	call:function(route, onSuccess, onFail)
	{
		var url = this.getCallUrl(location.origin + route);
		return $.get(url, onSuccess).fail(onFail);
	},
	
	open:function(url)
	{
		window.open(server.getCallUrl(url));
	},

	getCallUrl:function(url)
	{
		return url.replace(/\/$/, '') + '?call=1';
	},

	load:function(route, onSuccess)
	{
		var url = this.base + route;
		console.log(url);
		return $.get(url, onSuccess);
	}

};
