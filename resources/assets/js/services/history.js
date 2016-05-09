function UserHistory(nav)
{
	this.nav = nav;

	// setup base route
	this.base = $('meta[name="route"]').attr('content');

	// back handler
	window.onpopstate = this.onPopState.bind(this);

	this.updateRoute();

	// history - this isn't really needed
	History.Adapter.bind(window,'statechange',function(){
		var state = History.getState();
		//console.log('state change:', state);
	});

}

UserHistory.prototype =
{
	nav:null,

	base:'',

	pushState:function(route)
	{
		History.pushState(null, this.getTitle(route), route);
	},

	onPopState:function(event)
	{
		var state = History.getStateById(event.state);
		if(state)
		{
			document.title = state.title;
		}
		this.updateRoute();
	},

	// update app
	updateRoute:function(event)
	{
		this.nav.route = window.location.pathname;
	},

	getTitle:function(route)
	{
		return 'Sketchpad - ' + this.getRelativeRoute(route);
	},

	getRelativeRoute:function(route)
	{
		return route.substr(this.base.length);
	}

};