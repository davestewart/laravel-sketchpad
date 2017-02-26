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
				if(this.handle(file))
				{
					return true;
				}
				return reload(file, options);
			};
		}

		// listen to browsersync
		if(window.___browserSync___)
		{
			___browserSync___.socket.on('sketchpad.udpate', event =>
			{
				console.log(arguments);
				this.handle(event.file); // TODO check this is the proper call
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
	 * @returns {boolean}           A flag indicating whether Sketchpad will intercept the load (true) or allow LiveReload to handle it (false)
	 */
	handle (file)
	{
		// default type
		let type = 'change';

		// detect custom types for add, delete and change
		let matches = file.match(/^(add|change|delete):(.+)/);
		if(matches)
		{
			type = matches[1];
			file = matches[2];
		}

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
