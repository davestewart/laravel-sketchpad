<template>

	<li>
		<label
			:for="id"
			:title="param.text"
		>{{ param.name }}</label>
		<div class="field">
			<span class="sizer" v-if="isText()">{{{ value }}}</span>
			<input
				v-model="value"
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

		value:
		{
			get ()
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
			set (value)
			{
				this.param.value = value;
			}
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
				// FF needs requires nextTick
				this.$nextTick(() => {
					var input = this.$el.getElementsByTagName('input')[0];
					var sizer = this.$el.getElementsByClassName('sizer')[0];
					var width = parseInt(window.getComputedStyle(sizer).width);
					var padding = this.type === 'number' ? 20 : 15;
					input.style.width = (parseInt(width) + padding) + 'px';
				})
			}
		}

	}

}

</script>

<style lang="scss">
	
</style>