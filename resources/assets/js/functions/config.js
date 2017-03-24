// libraries
import Vue		        from 'vue';
import {clone, dump}    from './utils'

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
	window.clone = clone;
	window.dump = dump;
}

