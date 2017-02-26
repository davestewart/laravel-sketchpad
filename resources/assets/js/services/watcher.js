class Watcher {

	constructor()
	{
		// handlers
		this.handlers = [];

		// decorate LiveReload
		if(window.LiveReload)
		{
			// proxy
			const reload = LiveReload.reloader.reload.bind(LiveReload.reloader);

			// decorate
			LiveReload.reloader.reload = (file, options) =>
			{
				// default type
				let type = 'change';

				// parse custom types add, delete, change
				let matches = file.match(/^(add|change|delete):(.+)/);
				if(matches)
				{
					type = matches[1];
					file = matches[2];
				}

				// handle event
				if(this.handle(file, type))
				{
					return true;
				}
				return reload(file, options);
			};
		}

		// listen to browsersync
		if(window.___browserSync___)
		{
			___browserSync___.socket.on('sketchpad.update', event =>
			{
				console.log(arguments);
				this.handle(event.file, event.type); // TODO check this is the proper call
			})
		}
	}

	/**
	 * Add file path handler
	 *
	 * @param   {RegExp}    rx      A regular expression to match a passed filepath
	 * @param   {Function}  fn      A handler function for the filepath. Should return a Boolean true if it handled the path
	 */
	addHandler (rx, fn)
	{
		this.handlers.push({rx, fn})
	}

	/**
	 * Delegated livereload function
	 *
	 * @param   {string}    file    A root-relative path; i.e. sketchpad/controllers/ExampleController.php
	 * @param   {string}    type    The type of change; will be one of "add", "change", "delete"
	 * @returns {boolean}           A flag indicating whether Sketchpad will intercept the load (true) or allow LiveReload to handle it (false)
	 */
	handle (file, type)
	{
		// debug
		console.info(type, file);

		// get matching handlers
		let handlers = this.handlers
			.filter(handler => handler.rx.test(file));

		// handle
		let handled = false;
		handlers
			.some(handler =>
			{
				const state = handler.fn(file, type);
				if (state === true)
				{
					handled = true;
					return true;
				}
			});

		// return result
		return handled;
	}

}

export default new Watcher;
