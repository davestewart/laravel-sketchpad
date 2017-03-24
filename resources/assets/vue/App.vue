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

	// services
	import server       from '../js/services/server.js';
	import watcher      from '../js/services/watcher.js';

	// state
	import store        from '../js/state/store.js';
	import state        from '../js/state/state.js';
	import settings     from '../js/state/settings.js';

	// components
	import Navigation 	from './nav/Navigation.vue';
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

			// assets
			this.updateAssets()
			this.$watch('settings.head', this.updateAssets, {deep: true})

			// watcher
			if(this.settings.watcher)
			{
				watcher.init()
			}

			// done!
			console.log('App ready')

			// ui
			//$('#nav .sticky').sticky({topSpacing:20, bottomSpacing:20});
			//$('#params .sticky').sticky({topSpacing:20});
		},

		methods:
		{
			onLinkClick (event)
			{
				var root = location.origin + '/' + app.settings.route;
				if(event.target.href && event.target.href.indexOf(root) === 0)
				{
					event.preventDefault();
					const path = event.target.href.substr(root.length - 1);
					router.go(decodeURI(path))
				}
			},

			updateAssets ()
			{
				let html = '';
				const $head = $('head')
				$head.find('[data-asset]').remove()
				this.settings.head
					.forEach(url => {
						html += /\.css$/.test(url)
						    ? '<link data-asset href="' +url+ '" rel="stylesheet">'
						    : '<script data-asset src="' +url+ '"></scr' + 'ipt>'
					    $('head')
					})
				$head.append(html)
			}

		}
	}

</script>

<style lang="scss">

</style>