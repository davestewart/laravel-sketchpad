// libraries
import Vue			from 'vue';

export default function ()
{
	// vue
	Vue.config.debug	= true;
	window.Vue			= Vue;

	// token
	const csrf = $('meta[name="csrf-token"]').attr('content');
	const root = $('meta[name="route"]').attr('content');

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
}

