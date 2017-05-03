import _ from 'underscore';

export default {

	props: ['param'],

	data ()
	{
		return {
			val: decodeURIComponent(this.param.value),
			debounce: 400
		}
	},

	created ()
	{
		if (this.debounce)
		{
			this.update = _.debounce(this.update, this.debounce);
		}
	},

	computed:
	{
		value:
		{
			get ()
			{
				return this.param.value;
			},
			set (value)
			{
				this.val = value;
				this.update(value);
			}
		},

		id ()
		{
			return 'param-' + this.param.name;
		}
	},

	methods:
	{
		update (value)
		{
			this.param.value = value;
		}
	}
}