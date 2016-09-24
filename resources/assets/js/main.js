// ------------------------------------------------------------------------------------------------
// imports

	// libraries
	import Vue			from 'vue';
	//import VueResource 	from 'vue-resource';

	// components
	import App			from '../vue/App.vue';
	import settings		from './services/settings.js';


// ------------------------------------------------------------------------------------------------
// configuration

	// debug
	console.log('APP CONFIGURING!');

	// vue
	Vue.config.debug	= true
	window.Vue			= Vue;

	// resource
	Vue.use(require('vue-resource'));
	Vue.http.options.root = $('meta[name="app-url"]').attr('content');
	Vue.http.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');


// ------------------------------------------------------------------------------------------------
// app

	window.app = new Vue(
	{
		el: 'body',

		data:function()
		{
			return {
				settings:settings
			}
		},

		components:
		{
			App
		}

	});
