Vue.component('params', {
	
	template:'#params-template',
	
	props:['params'],

	methods:
	{

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

		onParamChange:function()
		{
			this.$dispatch('onParamChange');
		}
	}
	
});