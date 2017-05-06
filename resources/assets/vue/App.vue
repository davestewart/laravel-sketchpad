<template>

	<div id="app" :class="{'show-comments':settings.ui.showComments}">

		<top-nav></top-nav>

		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-4">
					<navigation v-ref:nav></navigation>
				</div>
				<div class="col-xs-8">
					<div id="main">
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

	// libs
	import _            from 'underscore'
	import {getRoute}   from '../js/functions/utils'

	// services
	import server       from '../js/services/server.js';
	import watcher      from '../js/services/watcher.js';

	// state
	import store        from '../js/state/store.js';
	import state        from '../js/state/state.js';
	import settings     from '../js/state/settings.js';

	// components
	import Navigation 	from './navigation/Navigation.vue';
	import TopNav       from './components/TopNav.vue';
	import Modal        from './components/Modal.vue';

// ------------------------------------------------------------------------------------------------
// objects

	export default
	{
		components:
		{
			Navigation,
			TopNav,
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
            window.app      = this;
            this.server     = server;
        },

		ready ()
		{
			// links
			$('#main').on('click', 'a[href]', this.onLinkClick);

			// watcher
			if(this.settings.livereload.host)
			{
				watcher.init()
			}

			// assets
			watcher.addHandler(this.onHeadChange, file => {
				return file === settings.paths.views + 'head.blade.php';
			});

			// error frame
			window.addEventListener('message', this.onPostMessage)

			// done!
			console.log('Sketchpad loaded OK')

			// ui
			//$('#nav .sticky').sticky({topSpacing:20, bottomSpacing:20});
			//$('#params .sticky').sticky({topSpacing:20});
		},

		methods:
		{
			onLinkClick (event)
			{
				const target = event.currentTarget;
				const href = target.href;
				if (href && !target.target)
				{
					// variables
					const root  = location.origin + this.settings.route;
					let path

					// handle sketchpad: links
					if (href.indexOf('sketchpad:') === 0)
					{
						path = '/run/' + href.replace(/sketchpad:\/?/, '');
					}

					// handle normal links
					if(href.indexOf(root) === 0) //  && href.indexOf('#') !== 0
					{
						path = href.substr(root.length - 1);
					}

					// navigate to route
					if (path)
					{
						event.preventDefault();
						path = path.replace(/^\/api\/run\//, '/run/');
						getRoute(path) === getRoute(this.$route.path)
							? router.replace(decodeURI(path))
							: router.go(decodeURI(path))
					}
				}

			},

			onHeadChange ()
			{
				window.location.reload();
			},

			onPostMessage (event)
			{
				if (event.data && event.data.setFrameHeight)
				{
					const height = event.data.setFrameHeight + 100;
					$('#console iframe').css('height', height + 'px');
				}
			}

		}
	}

</script>

<style lang="scss">

</style>