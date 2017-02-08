import Vue          from 'vue'
import VueRouter    from 'vue-router'

import Run          from '../../vue/components/main/Run.vue'
import Home         from '../../vue/components/pages/Home.vue'
import Help         from '../../vue/components/pages/Help.vue'
import Settings     from '../../vue/components/pages/Settings.vue'

Vue.use(VueRouter);

// ------------------------------------------------------------------------------------------------
// routes
// ------------------------------------------------------------------------------------------------

    const router = new VueRouter({
        history: true,
        root: '/',
        saveScrollPosition: true
    });

    router.map({
        // '/': {redirect: '/parent'},
        '/':{ component: Home },
        '/run/*any': { component: Run, canReuse:false },
        '/settings' :{ component: Settings },
        '/help': { component: Help },
        '*' : { component: Home}
    });

    router.beforeEach(function (transition) {
        console.info('Navigated to:', transition.to.path);
        console.log('Args', transition);
        transition.next()
    });

    export default router;
