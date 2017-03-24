<template>

	<li>
		<label
			:for="id"
			:title="param.text"
		>{{ param.name }}</label>
		<div class="field">
			<span class="sizer" v-if="isText()">{{{ param.value }}}</span>
			<input
				v-model="param.value"
				@input="setSize()"
				@change="setSize()"
				:type="type"
				:id="id"
			>
		</div>
	</li>

</template>

<script>

function getTextWidth(text, font) {
    // re-use canvas object for better performance
    var canvas = getTextWidth.canvas || (getTextWidth.canvas = document.createElement("canvas"));
    var context = canvas.getContext("2d");
    context.font = font;
    var metrics = context.measureText(text);
    return metrics.width;
}

export default
{
	name: 'Param',

	props:['param'],

	ready ()
	{
		//console.log('name:', this.param.name)
		this.setSize()
	},

	computed:
	{
		type ()
		{
			if(/^bool/.test(this.param.type))
			{
				return 'checkbox';
			}
			else if(this.param.type == 'number')
			{
				return 'number';
			}
			else if(/url|email|date/.test(this.param.type))
			{
				return this.param.type;
			}
			return 'text';
		},

		fields ()
		{
			// http://www.w3schools.com/html/html_form_input_types.asp
			var types		= 'text,number,date,select';
			var attributes 	= 'min,max,step,size,maxlength,pattern,options';
		},

		id ()
		{
			return 'param-' + this.param.name;
		}

	},

	methods:
	{
		isText ()
		{
			return this.type !== 'checkbox';
		},

		setSize ()
		{
			if (this.isText())
			{
				const $el = this.$el;
				const padding = this.type === 'number' ? 20 : 15;

				// FF needs requires nextTick
				this.$nextTick(() => {
					var input = $el.getElementsByTagName('input')[0];
					var sizer = $el.getElementsByClassName('sizer')[0];
					var width = parseInt(window.getComputedStyle(sizer).width);
					input.style.width = (parseInt(width) + padding) + 'px';
				})
			}
		}

	},

	watch:
	{
		param (value)
		{
			console.log('param', value)
		}
	}

}

</script>

<style lang="scss">
	
</style>