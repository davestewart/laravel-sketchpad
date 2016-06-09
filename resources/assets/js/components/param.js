Vue.component('param', {

	template:'#param-template',

	props:['param'],

	computed:
	{
		type:function()
		{
			if(/^bool/.test(this.param.type))
			{
				return 'checkbox';
			}
			else if(this.param.type == 'number')
			{
				return 'number';
			}
			return this.param.type;
		},

		value:
		{
			get:function()
			{
				if(/^bool/.test(this.param.type))
				{
					return this.param.value == true || this.param.value == 'true';
				}
				else if(this.param.type == 'number')
				{
					return Number(this.param.value);
				}
				return this.param.value;
			},
			set:function(value)
			{
				this.param.value = value;
			}
		},

		id:function()
		{
			return 'param-' + this.param.name;
		}
	}

});