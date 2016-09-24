import settings from '../services/settings.js';

export default
{
	getMethodLabel:function(method)
	{
		return settings.useLabels
			? this.humanize(method.label)
			: method.label + '()';
	},

	getControllerLabel:function(method)
	{
		return settings.useLabels
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
