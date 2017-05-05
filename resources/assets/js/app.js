import Vue          from 'vue'
import VueRouter    from 'vue-router'
import              '../vue/directives/validate-path';
import              '../vue/directives/field-attrs';

// state
import admin        from './state/admin'
import store        from './state/store'
import settings     from './state/settings'
import config       from './functions/config'
config();

// functions
import Helpers      from './functions/helpers';

// components
import Home         from '../vue/pages/Home.vue'
import Help         from '../vue/pages/Help.vue'
import Favourites   from '../vue/pages/Favourites.vue'
import Search       from '../vue/pages/Search.vue'
import Settings     from '../vue/settings/Settings.vue'
import Console      from '../vue/console/Wrapper.vue'

import App          from '../vue/App.vue'

window.store = store

Vue.use(VueRouter);
window.router = new VueRouter({
	root: settings.route,
	history: true,
	saveScrollPosition: true
});

const Redirect = Vue.component('redirect', {
	route: {
		canActivate: function (transition) {
			window.location.href = settings.route + transition.to.path.substr(1);
			return false;
		}
	}
});

const routes = {
	// '/': {redirect: '/parent'},
	'/': {
		component: Home
	},
	'/run/*route': {
		component: Console,
		canReuse:false
	},
	'/favourites' : {
		component: Favourites
	},
	'/search' : {
		component: Search
	},
	'/settings' : {
		component: Settings
	},
	'/setup' : {
		component: Redirect,
	},
	'/help': {
		component: Help
	},
	'*' : {
		component: Home
	}
};

router.map(routes);

router.afterEach(function (transition) {
	if (transition.to.path.indexOf('/run') !== 0)
	{
		Helpers.setTitle(transition.to.matched[0].handler.component.name);
	}
})

router.start(App, '#app');
