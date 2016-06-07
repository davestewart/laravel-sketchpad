Vue.component('params', {
	
	template:'#params-template',
	
	props:['params', 'deferred'],

	methods:
	{

		run:function()
		{
			this.$dispatch('run');
		},

		getType:function(param)
		{
			if(/^-?(\d+|\d+\.\d+|\.\d+)([eE][-+]?\d+)?$/.test(param.value))
			{
				return 'number';
			}
			if(/^true|false$/i.test(param.value))
			{
				return 'checkbox';
			}
			return 'text';
		},

		getId:function(param)
		{
			return 'param-' + param.name;
		},

		onParamChange:function()
		{
			//this.$dispatch('onParamChange');
		}
	}
	
});