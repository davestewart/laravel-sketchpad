export default
{
	ready ()
	{
		this.setSize()
	},

	methods:
	{
		setSize ()
		{
			const $el = this.$el;
			const padding = this.name === 'TextParam' ? 15 : 20;

			// FF needs requires nextTick
			this.$nextTick(() => {
				const input = $el.getElementsByTagName('input')[0];
				const sizer = $el.getElementsByClassName('sizer')[0];
				const width = parseInt(window.getComputedStyle(sizer).width);
				input.style.width = (parseInt(width) + padding) + 'px';
			})
		},

	}

}
