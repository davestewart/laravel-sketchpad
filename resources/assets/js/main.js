// ------------------------------------------------------------------------------------------------
// imports

	// libraries
	import Vue			from 'vue';

	// components
	import App			from '../vue/App.vue';
	import Setup		from '../vue/setup/Setup.vue';
	import settings		from './services/settings.js';


// ------------------------------------------------------------------------------------------------
// configuration

	// vue
	Vue.config.debug	= true;
	window.Vue			= Vue;

	// token
	var csrf = $('meta[name="csrf-token"]').attr('content');
	var root = $('meta[name="app-url"]').attr('content');

	// resource
	Vue.use(require('vue-resource'));
	Vue.http.options.root = root;
	Vue.http.headers.common['X-CSRF-TOKEN'] = csrf;

	// jquery
	jQuery.ajaxSetup(
	{
		headers:
		{
			'X-CSRF-Token': csrf
		}
	});

	// utilities
	window.clone = function clone(obj){ return JSON.parse(JSON.stringify(obj)); };
	window.dump = function dump(){ Array.prototype.slice.call(arguments).map( obj => console.log(clone(obj) ) ) };


// ------------------------------------------------------------------------------------------------
// app

	window.root = new Vue(
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
			Setup,
			App
		}
	});
