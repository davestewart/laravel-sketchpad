function Server()
{

}

Server.prototype =
{

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
	}

};
