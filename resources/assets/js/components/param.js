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

		id:function()
		{
			return 'param-' + this.param.name;
		}
	}

});