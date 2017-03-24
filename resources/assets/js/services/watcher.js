class Watcher {

	constructor()
	{
		this.initialized = false;
		this.handlers = [];
	}

	init ()
	{
		// debug
		console.log('Initializing file watcher...');

		const watcher = app.settings.watcher || 'File watcher';

		// decorate LiveReload
		if(window.LiveReload)
		{
			// don't re-decorate
			if (LiveReload.reloader.decorated)
			{
				return false;
			}

			// proxy
			const reload = LiveReload.reloader.reload.bind(LiveReload.reloader);

			// decorate
			LiveReload.reloader.decorated = true;
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

			// success
			this.initialized = true;
		}

		// listen to browsersync
		if(window.___browserSync___)
		{
			// don't redecorate
			if(___browserSync___.socket.hasListeners('sketchpad:change'))
			{
				return false;
			}

			// add listener
			___browserSync___.socket.on('sketchpad:change', event =>
			{
				return ! this.handle(event.file, event.type);
			});

			// success
			this.initialized = true;
		}

		// debug
		if (this.initialized)
		{
			console.info(watcher + ' initialized');
			return true;
		}
		console.warn(watcher + ' not detected! Did you start the Gulp task?');
		return false;
	}

	/**
	 * Add file path handler
	 *
	 * @param   {RegExp}    rx      A regular expression to match a passed filepath
	 * @param   {Function}  fn      A handler function for the filepath. Should return a Boolean true if it handled the path
	 */
	addHandler (rx, fn)
	{
		this.handlers.push({rx, fn});
		return this;
	}

	/**
	 * Remove file path handler
	 *
	 * @param   {Function}  fn      An existing function for the filepath
	 */
	removeHandler (fn)
	{
		const index = this.handlers.findIndex(handler => handler.fn === fn);
		this.handlers.splice(index, 1);
		return this;
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
		// console.info('File change:', type, file);

		// get matching handlers
		let handlers = this.handlers
			.filter(handler => handler.rx.test(file));

		// handle
		let handled = false;
		handlers
			.forEach(handler =>
			{
				if(handler.fn(file, type))
				{
					handled = true;
				}
			});

		// return result
		return handled;
	}

}

const watcher = new Watcher;
window.watcher = watcher;

export default watcher
