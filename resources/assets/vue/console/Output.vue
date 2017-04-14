<template>
	<section id="output" class="content" :data-format="format"></section>
</template>

<script>

import settings		from '../../js/state/settings.js';

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
		setContent (text, contentType, transition)
		{
			// variables
			const method 			= this.method;

			// properties
			this.transition 	= false;

			// clear
			if(transition)
			{
				this.clear();
			}

			// html
			let $html = $('<div class="data">' + text + '</div>');

			// transform data
			$html
				.find('[data-format]')
				.each(function ()
				{
					// variables
					const $el = $(this)
					const format = $(this).attr('data-format')
					const text = $el.html()

					// transform
					if (format === 'json')
					{
						$el.addClass('code json').JSONView(text);
					}
					else if (format === 'markdown')
					{
						$el.addClass('markdown').html(marked(text));
					}
				})

			// format code
			if(settings.ui.formatCode)
			{
				$html
					.find('pre.code, pre > code')
					.each(function(i, block)
					{
						hljs.highlightBlock(block);
					});
			}

			// add content
			(method && method.tags.append) || settings.ui.appendOutput
				? this.$output.prepend($html)
				: this.$output.html($html);
		},

		setError (html, type)
		{
			// variables
			const styles    = '<style>#sf-resetcontent { width:auto; word-break: break-all; }</style>';
			const script    = '<script>function post() { parent.postMessage({setFrameHeight:document.body.clientHeight + 80}, "*"); } if(parent.postMessage) { post(); window.onresize = post; };</scr' + 'ipt>';

			// set content
			const src		= 'data:' +type+ ',' + encodeURIComponent(html + styles + script);
			const $iframe   = $('<iframe id="error-frame" frameborder="0">').attr('src', src);
			this.clear().append($iframe);
			this.format 	= 'error';
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