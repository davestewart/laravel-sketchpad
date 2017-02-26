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
	import server       from '../js/services/server.js';

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
			// routing
			router.afterEach(transition => {
				const route = transition.to;
				if(route.path.indexOf('/run/') === 0)
				{
					state.setRoute(route.params.route, route.query);
					this.$nextTick( () => this.$refs.content.load())
				}
				else
				{
					state.reset()
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
			onLinkClick (event)
			{
				var run = location.origin + server.getUrl('run/');
				if(event.target.href && event.target.href.indexOf(run) === 0)
				{
					event.preventDefault();
					const path = event.target.href.substr(run.length);
					router.go('/run/' + decodeURI(path))
					//router.replace('/run/' + decodeURI(path))
				}
			}

		}
	}

</script>

<style lang="scss">

</style>