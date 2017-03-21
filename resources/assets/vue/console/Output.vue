<template>
	<section id="output" :data-format="format"></section>
</template>

<script>

import settings		    from '../../js/state/settings.js';

export default
{
	name: 'Output',

	props: ['method'],

	data ()
	{
		return {
			format		:'',
			loading		:false,
			transition	:false
		}
	},

	ready ()
	{
		this.$output = $('#output');
		this.$output.on('submit', 'form[action=""],form:not([action])', this.onFormSubmit);
	},

	methods:
	{
		setContent (data, contentType = '', transition)
		{
			// variables
			var method 			= this.method;

			// properties
			this.transition 	= false;

			// clear
			if(transition)
			{
				this.clear();
			}

			// format
			if(method && method.tags.iframe)
			{
				return this.loadIframe(data, contentType);
			}

			var $data = $('<div class="data">' + data + '</div>');

			// handle json response
			if(contentType == 'application/json')
			{
				this.format = 'json';
				$data.html($('<div class="code json">').JSONView(data));
			}

			// handle md response
			else if(contentType.indexOf('text/markdown') > -1)
			{
				var html		= marked(data);
				this.format 	= 'markdown';
				$data.html(html);
			}

			// add content
			(method && method.tags.append) || settings.ui.appendOutput
				? this.$output.prepend($data)
				: this.$output.html($data);

			// format code blocks
			if(settings.ui.formatCode)
			{
				$data.find('pre.code, pre > code').each(function(i, block) {
					hljs.highlightBlock(block);
				});
			}


		},

		setError (text, type)
		{
			this.format 		= 'error';
			this.loadIframe (text, type);
		},

		loadIframe (text, type)
		{
			var src		= 'data:' + type + ',' + encodeURIComponent(text);
			var $iframe = $('<iframe class="error" frameborder="0">');
			this.clear().append($iframe.attr('src', src));
		},

		clear ()
		{
			return this.$output && this.$output.empty();
		},

		onFormSubmit (event)
		{
			event.preventDefault();
			this.$emit('submit', $(event.target).serialize())
		}

	}


}

</script>

<style lang="scss">
	
</style>