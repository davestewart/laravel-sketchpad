Helpers =
{
	methodLabel:function(method)
	{
		return settings.useLabels
			? method.label
			? method.label
			: this.humanize(method.name)
			: method.name + '()';
	},

	humanize:function(input)
	{
		return input
			.replace(/_/g, ' ')
			.replace(/([a-z])([A-Z0-9])/g, '$1 $2')
			.toLowerCase();
	}

};
