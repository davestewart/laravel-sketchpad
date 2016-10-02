<template>

	<label
		for="{{ id }}"
		:title="param.text"
	>{{ param.name }}</label>
	<input

		:id="id"
		:type="type"
		v-model="value"
		debounce="400"
	>

</template>

<script>
	
export default
{
	props:['param'],

	ready:function()
	{
		//console.log(this.param);
	},

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

		fields:function()
		{
			// http://www.w3schools.com/html/html_form_input_types.asp
			var types		= 'text,number,date,select';
			var attributes 	= 'min,max,step,size,maxlength,pattern,options';
		},

		id:function()
		{
			return 'param-' + this.param.name;
		}
	}

}

</script>

<style lang="scss">
	
</style>