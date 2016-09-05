var settings =
{
	useLabels:true,
	formatCode:false,
	showComments:true,
	appendResult:false
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

