import Vue          from 'vue'
import VueRouter    from 'vue-router'
import Sortable     from 'vue-sortable'

Vue.use(Sortable);
Vue.use(VueRouter);

import store        from './state/store'
import config       from './functions/config'
config();


import Home         from '../vue/pages/Home.vue'
import Help         from '../vue/pages/Help.vue'
import Settings     from '../vue/pages/Settings.vue'
import Favourites   from '../vue/nav/Favourites.vue'
import Search       from '../vue/nav/Search.vue'
import Console      from '../vue/console/Console.vue'

import App          from '../vue/App.vue'

window.store = store

window.router = new VueRouter({
	root: $('meta[name="route"]').attr('content'),
	history: true,
	saveScrollPosition: true
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
	'/settings' : {
		component: Settings
	},
	'/search' : {
		component: Search
	},
	'/help': {
		component: Help
	},
	'*' : {
		component: Home
	}
};

router.map(routes);

router.start(App, '#app');
