import Vue          from  'vue'
import config       from './functions/config'
import Setup		from '../vue/setup/Setup.vue';

config();
window.root = new Vue(
{
	el: '#app',
	data:function() { return {} },
	components:
	{
		Setup
	}
});
