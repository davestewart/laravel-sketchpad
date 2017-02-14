<template>

	<div id="test">

		<top-nav :route="state.baseUrl"></top-nav>

		<div class="container">
			<div class="row">
				<div class="col-xs-4">
					<navigation v-ref:navigation :controllers="store.controllers" :state="state" :settings="settings"></navigation>
				</div>
				<div class="col-xs-8">
					<!--
					<result v-if="state.controller" v-ref:result :state="state"></result>
					-->
					<div id="content" class="view">
						<router-view v-ref:content :state="state" :settings="settings" :loader="loader"></router-view>
					</div>
				</div>
			</div>
		</div>

		<modal v-ref:modal></modal>
	</div>

</template>

<script>

// ------------------------------------------------------------------------------------------------
// imports

	// services
	import server       from '../js/services/server/server.js';
	import loader		from '../js/services/loader';

	// state
	import store        from '../js/state/store.js';
	import state        from '../js/state/state.js';
	import settings     from '../js/state/settings.js';

	// components
	import Navigation 	from './nav/Navigation.vue';
	import Result 		from './content/Result.vue';
	import TopNav       from './components/TopNav.vue';
	import Modal        from './components/Modal.vue';

// ------------------------------------------------------------------------------------------------
// objects

	export default
	{
		components:
		{
			Navigation,
			Result,
			Modal,
			TopNav
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
            window.app      = this;
			this.root		= location.origin + $('meta[name="route"]').attr('content'),
            this.server     = server;
            this.loader 	= loader;
            loader.state 	= this.state;
        },

		ready ()
		{
			// reloading
			this.store.$on('load', this.onStoreLoad);

			// reloading
			this.loader.$on('params', this.onParamsChange);
			this.loader.$on('start', this.onLoaderStart);
			this.loader.$on('load', this.onLoaderLoad);
			this.loader.$on('error', this.onLoaderError);

			// routing
			router.afterEach(transition => {
				const route = transition.to;
				if(route.path.indexOf('/run/') === 0)
				{
					// set state
					this.state.setRoute(route.params.route, route.query);

					// update loader
					this.loader.load()
				}
				else
				{
					//this.state.reset();
				}
			})

			// links
			$('#content').on('click', 'a[href]', this.onLinkClick);

			// ui
			//$('#nav .sticky').sticky({topSpacing:20, bottomSpacing:20});
			//$('#params .sticky').sticky({topSpacing:20});
		},

		methods:
		{
			// ------------------------------------------------------------------------------------------------
			// loading

				onLoaderStart (clear)
				{
					if(clear && this.$refs.content && app.$refs.content.setContent)
					{
						app.$refs.content.clear()
					}
				},

				onLoaderLoad (data, type)
				{
					if(this.$refs.content && app.$refs.content.setContent)
					{
						app.$refs.content.setContent(data, type)
					}
				},

				onLoaderLoad (data, type)
				{
					if(this.$refs.content && app.$refs.content.setContent)
					{
						app.$refs.content.setContent(data, type)
					}
				},

				onLoaderError (data, type)
				{
					if(this.$refs.content && app.$refs.content.setContent)
					{
						app.$refs.content.setError(data, type)
					}
				},

				onParamsChange ()
				{
					const route = '/run/' + this.state.makeRoute(this.state.method);
					this.$router.replace(route);
				},

				onStoreLoad (event)
				{
					if(this.state.controller && this.state.controller.relpath == event.path)
					{
						var cIndex 	= event.index;
						var mIndex	= this.state.method ? this.state.controller.methods.indexOf(this.state.method) : -1;
						if(cIndex !== -1)
						{
							this.unwatch();
							this.state.controller = this.store.controllers[cIndex];
							if(mIndex !== -1)
							{
								this.state.method = this.state.controller.methods[mIndex];
							}
							this.watch();
						}

						// reload
						this.update();
					}
				},

				onLinkClick (event)
				{

					var run = this.root + 'run/';
					if(event.target.href.indexOf(run) === 0)
					{
						event.preventDefault();
						const path = event.target.href.substr(run.length);
						router.go('/run/' + decodeURI(path))
					}
				}

		}
	}

</script>

<style lang="scss">

</style>