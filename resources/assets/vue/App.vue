<template>

	<div id="app" class="row">

		<div class="col-xs-4">
			<navigation v-ref:navigation :controllers="store.controllers" :state="state" >
				Navigation
			</navigation>
		</div>

		<div class="col-xs-8">
			<result v-if="state.controller" v-ref:result :state="state">
				Result
			</result>
			<div id="content" v-else>

			</div>
		</div>

		<modal v-ref:modal></modal>

	</div>

</template>

<script>

// ------------------------------------------------------------------------------------------------
// imports

	// services
	import server		from '../js/services/server/server.js';
	import store		from '../js/services/store.js';
	import state		from '../js/services/state.js';
	import settings		from '../js/services/settings.js';

	// components
	import Navigation	from './Navigation.vue';
	import Result		from './Result.vue';
	import Modal		from './Modal.vue';

// ------------------------------------------------------------------------------------------------
// objects

	export default
	{
		components:
		{
			Navigation,
			Result,
			Modal
		},

		data ()
		{
			return {
				settings	:settings,
				store		:store,
				state		:state
			};
		},

        created ()
        {
			this.router 	= new Router(); // global: slim-router
            this.server     = server;
            window.app      = this;
        },

		ready ()
		{
			// reloading
			this.store.$on('load', this.onStoreLoad);

			// links
			//$('body').on('click', 'a[href^="/sketchpad/"]', this.onLinkClick);
			$('body').on('click', 'a', this.onLinkClick);

			// routes
			var url 		= this.state.baseUrl;

			this.router.route(url, this.onHome);
			this.router.route(url + '~/*view', this.onView);
			this.router.route(url + '*path', this.onRoute);

			// seems to be a small bug with router on home route, so trigger home then start as normal
			this.onHome();
			this.router.start();

			// ui
			//$('#nav .sticky').sticky({topSpacing:20, bottomSpacing:20});
			//$('#params .sticky').sticky({topSpacing:20});
		},

		methods:
		{

			// ------------------------------------------------------------------------------------------------
			// methods

				run (route)
				{
					this.unwatch();
					this.state.setRoute(route);
					this.$nextTick(function()
					{
						this.update();
						if(this.state.method)
						{
							this.watch();
						}
					});
				},

				update ()
				{
					if(this.$refs.result)
					{
						this.$refs.result.load();
					}
				},

				watch ()
				{
					this.unwatch = this.$watch('state.method.params', this.onParamsChange, {deep:true});
				},

				unwatch ()
				{
					// will be populate by $watch
				},


			// ------------------------------------------------------------------------------------------------
			// handlers

				onLinkClick (event)
				{
					// variables
					var meta 	= event.ctrlKey || event.metaKey;
					var route	= this.state.getLink(event.target.href);
					var $target	= $(event.target);
					var path	= $target.data('path');
					var href	= $target.attr('href');

					// skip # links
					if(href && href.indexOf('#') == 0)
					{
						return;
					}

					// resolve ~ links
                    if(href.indexOf('~') == 0)
                    {
                        debugger;
                    }

					// controller
					if(path && meta)
					{
						event.preventDefault();
						this.server.loadController(path);
					}

					// method
					if(route)
					{
						event.preventDefault();
						meta
							? this.server.open(route)
							: this.router.navigate(route);
					}
				},

				onRoute (route)
				{
					this.run(this.state.baseUrl + route);
				},

				onParamsChange ()
				{
					this.router.navigate(this.state.route, false, true);
				},

				onHome ()
				{
					this.onView('welcome');
				},

				onView (type)
				{
					document.title 	= 'Sketchpad - ' + type;
					this.state.reset();
					this.server.load(':page/' + type, function(html)
					{
						$('#content').html(html);
					});
				},

				onStoreLoad (event)
				{
					if(this.state.controller && this.state.controller.relpath == event.path)
					{
						var cIndex 	= event.index;
						var mIndex	= this.state.method ? this.state.controller.methods.indexOf(this.state.method) : -1;
						if(cIndex > -1)
						{
							this.unwatch();
							this.state.controller = this.store.controllers[cIndex];
							if(mIndex > -1)
							{
								this.state.method = this.state.controller.methods[mIndex];
							}
							this.watch();
						}

						// reload
						this.update();
					}
				}
		}
	}

</script>

<style lang="scss">
	
</style>