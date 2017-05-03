export default
{
	computed:
	{
		options ()
		{
			const options = this.param.attrs.options;
			return Array.isArray(options)
				? options.reduce((obj, el) => {
					obj[el] = el;
					return obj;
				}, {})
				: options;
		}

	}

}
