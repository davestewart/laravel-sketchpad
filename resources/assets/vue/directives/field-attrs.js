import Vue from 'vue'
import _ from 'underscore'
import {trim} from '../../js/functions/utils'
import server from '../../js/services/server'

Vue.directive('field-attrs',
{
// -------------------------------------------------------------------------------------------------------------------
// lifecycle

	bind ()
	{

	},

	update (value)
	{
		if (value)
		{
			Object.keys(value)
				.forEach(key => this.el.setAttribute(key, value[key]))
		}
	},

	unbind ()
	{

	}


});
