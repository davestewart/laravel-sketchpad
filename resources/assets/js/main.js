var server 	= new Server();

var store 	= new Store({
	server:server,
	state:state});

var app 	= new App({
	server:server,
	state:state,
	store:store});
