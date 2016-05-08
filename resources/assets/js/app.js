

var vm = new Vue({

	el:'#app',

	data:function(){
		var data = JSON.parse($('#data').text());
		data.controller = null;
		data.method = null;
		data._route = null;
		data.loading = false;
		data.base = $('meta[name="route"]').attr('content');
		return data;
	},

	init:function(){
		console.log('>', this.data);
	},

	ready:function(){

		// update app
		this.updateRoute();

		// history - this isn't really needed
		window.onpopstate = this.onPopState;
		History.Adapter.bind(window,'statechange',function(){
			var state = History.getState();
			console.log('state change:', state);
		});

		// sticky nav
		$('#sticky').sticky({topSpacing:20});

	},

	computed:{

		route:{
			get:function () {
				return this.$data._route;
			},
			set:function (route) {
				this.setRoute(route);
			}
		}

	},

	methods:
	{

		// ------------------------------------------------------------------------------------------------
		// html

			getLinkHtml:function(route)
			{
				return route
					.replace('/sketchpad/', '')
					.replace(/\/$/, '')
					.split('/')
					.join(' <span class="divider">&#9656;</span> ');
			},

			isActive:function(route)
			{
				var _route = String(this.$data._route);
				return _route.indexOf(route) === 0;
			},


		// ------------------------------------------------------------------------------------------------
		// getters

			getController:function (route) {
				var arr = this.controllers.filter(function(e){
					return route.indexOf(e.route) == 0;
				});
				return arr ? arr[0] : null;
			},

			getMethod:function (route, controller) {
				controller = controller || this.controller || this.getController(route);
				if(controller)
				{
					var arr = controller.methods.filter(function(e){
						return route.indexOf(e.route) == 0;
					});
					return arr ? arr[0] : null;
				}
			},


		// ------------------------------------------------------------------------------------------------
		// route

			setRoute:function(route)
			{
				this.$data._route = route;
				this.controller = this.getController(route);
				var method = this.getMethod(route);
				if(method)
				{
					//this.$broadcast('loadMethod', method);
					this.runMethod(method)
				}
			},

			updateRoute:function(event)
			{
				this.route = window.location.pathname;
			},

			loadController:function(controller)
			{
				History.pushState({controller:this.controller, method:null}, this.getTitle(controller.route), controller.route);
				this.route = controller.route;
			},

			loadMethod:function(method, element, $http)
			{
				if(event.metaKey || event.ctrlKey)
				{
					return window.open(this.getCallUrl(event.target.href));
				}
				History.pushState({controller:this.controller, method:method}, this.getTitle(method.route), method.route);
				this.route = method.route;
			},

			runMethod:function(method)
			{
				var url = this.getCallUrl(location.origin + method.route);
				var _this = this;
				this.loading = true;
				$.get(url, function(data)
				{
					_this.loading = false;
					_this.method = method;
					_this.showResult(data);
				});

			},

			showResult:function(data)
			{
				$('#output').html(data);
			},


		// ------------------------------------------------------------------------------------------------
		// utilities

			onPopState:function(event)
			{
				var state = History.getStateById(event.state);
				if(state)
				{
					document.title = state.title;
				}
				this.updateRoute();
			},

			getTitle:function(route)
			{
				return 'Sketchpad - ' + this.getRelativeRoute(route);
			},

			getRelativeRoute:function(route)
			{
				return route.substr(this.base.length);
			},

			getCallUrl:function(url)
			{
				return url.replace(/\/$/, '') + '?call=1';
			}

	}

});
