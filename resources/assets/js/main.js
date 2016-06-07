


var settings =
{
	useLabels:true
};

var server 	= new Server();

var store 	= new Store({
	server	:server
	});

var state 	= new State({
	store	:store
	});

var app 	= new App({
	settings:settings,
	server	:server,
	store	:store,
	state	:state
	});

