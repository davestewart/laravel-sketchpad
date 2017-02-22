import settings from '../state/settings.js';

export default
{
	getFolderHtml (route)
	{
		var name 	= '<span class="name">';
		var divider	= '<span class="divider">&#9656;</span> ';
		var close	= '</span> ';

		return name + route
				.replace(/\/$/, '')
				.split('/')
				.join(close + divider + name) + close;
	},

	getMethodLabel:function(method)
	{
		return settings.ui.humanizeText
			? this.humanize(method.label)
			: method.label + '()';
	},

	getControllerLabel:function(method)
	{
		return settings.ui.humanizeText
			? this.humanize(method.label)
			: method.label;
	},

	humanize:function(input)
	{
		return input
			.replace(/_/g, ' ')
			.replace(/([a-z])([A-Z0-9])/g, '$1 $2')
			.toLowerCase();
	}

};
