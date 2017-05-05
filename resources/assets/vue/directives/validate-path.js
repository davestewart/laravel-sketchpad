import Vue from 'vue'
import _ from 'underscore'
import {trim} from '../../js/functions/utils'
import server from '../../js/services/server'

Vue.directive('validate-path',
{
// -------------------------------------------------------------------------------------------------------------------
// lifecycle

	bind ()
	{
		this.$el = $(this.el);
		this.$icon = this.$el.parent().find('.validate-path');
		this.$el
			.attr('autocomplete', 'off')
			.on('focus', this.onFocus.bind(this))
			.on('input', _.debounce(this.onInput.bind(this), 400))
			.on('blur', this.onBlur.bind(this));
	},

	update (value)
	{

	},

	unbind ()
	{
		this.$el.off();
	},

// -------------------------------------------------------------------------------------------------------------------
// methods

	onFocus ()
	{
		this.$icon.show();
		this.check();
	},

	onInput ()
	{
		this.check(true);
	},

	onBlur ()
	{
		this.$icon.hide();
	},

	check (update)
	{
		const $el = $(this.el);
		const path = trim($el.val());
		server
			.validatePath(path)
			.then(data => {
				if ($el.is(':focus'))
				{
					this.$icon
						.attr('title', data.exists ? 'Path exists' : 'Path does not exist')
						.addClass('fa')
						.toggleClass('fa-check', data.exists)
						.toggleClass('fa-exclamation-triangle', !data.exists);
				}
				if (data.exists && update)
				{
					if (this.vm.onPathExists)
					{
						this.vm.onPathExists(data, this);
					}
				}
			})
	},
});
