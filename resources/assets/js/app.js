import Vue          from 'vue'
import VueRouter    from 'vue-router'

import config       from './config'

import Run          from '../vue/components/temp/main/Run.vue'
import Home         from '../vue/components/temp/pages/Home.vue'
import Help         from '../vue/components/temp/pages/Help.vue'
import Settings     from '../vue/components/temp/pages/Settings.vue'

import App          from '../vue/App.vue'

Vue.use(VueRouter);

config();

window.router = new VueRouter({
	history: true,
	root: '/',
	saveScrollPosition: true
});

router.beforeEach(function (transition) {
	console.info('Navigated to:', transition.to.path);
	console.log('Args', transition);
	transition.next()
});

const routes = {
	// '/': {redirect: '/parent'},
	'/':{ component: Home },
	'/run/*any': { component: Run, canReuse:false },
	'/settings' :{ component: Settings },
	'/help': { component: Help },
	'*' : { component: Home}
};

router.map(routes);

router.start(App, '#app');
