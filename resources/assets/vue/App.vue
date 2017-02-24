<template>

	<div id="app" :class="{'show-comments':settings.ui.showComments}">

		<top-nav></top-nav>

		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-4">
					<navigation v-ref:navigation></navigation>
				</div>
				<div class="col-xs-8">
					<!--
					<result v-if="state.controller" v-ref:result :state="state"></result>
					-->
					<div id="content" class="view">
						<router-view v-ref:content></router-view>
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
            this.server     = server;
        },

		ready ()
		{
			// reloading
			this.store.$on('load', this.onStoreLoad);

			// links
			$('#content').on('click', 'a[href]', this.onLinkClick);

			// routing
			router.afterEach(transition => {
				const route = transition.to;
				if(route.path.indexOf('/run/') === 0)
				{
					this.state.setRoute(route.params.route, route.query);
					this.$nextTick( () => this.$refs.content.load())
				}
				else
				{
					this.state.reset()
				}
			})

			// ui
			//$('#nav .sticky').sticky({topSpacing:20, bottomSpacing:20});
			//$('#params .sticky').sticky({topSpacing:20});
		},

		methods:
		{
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
				var run = location.origin + server.getUrl('run/');
				if(event.target.href && event.target.href.indexOf(run) === 0)
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