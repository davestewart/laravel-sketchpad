class Watcher {

	constructor()
	{
		this.initialized = false;
		this.error = '';
		this.handlers = [];
	}

	init ()
	{
		if (!app.settings.livereload.host)
		{
			return;
		}

		// decorate LiveReload
		if(window.LiveReload)
		{
			// don't re-decorate
			if (LiveReload.reloader.decorated)
			{
				return false;
			}

			// debug
			console.log('Initializing LiveReload...');

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

				// let livereload handle css, js and image files
				if (/\.(js|css|jpe?g|gif|png)$/.test(file))
				{
					return reload(file, options);
				}

				// handle event
				if(this.handle(file, type))
				{
					return true;
				}
			};

			// success
			this.initialized = true;
		}

		// debug
		if (this.initialized)
		{
			console.info('LiveReload initialized');
			return true;
		}
		this.error = 'LiveReload not detected! Did you run the Sketchpad node task?';
		console.warn(this.error);
		return false;
	}

	/**
	 * Add file path handler
	 *
	 * @param   {Function}          callback    A callback function for the file path; format is function (file, type) { return !!handled }
	 * @param   {RegExp|Function}  [filter]     A filter function or RegExp to match a passed file path; format is function (file)
	 */
	addHandler(callback, filter)
	{
		const fn = filter instanceof RegExp
			? function (file) { return filter.test(file); }
			: filter
		this.handlers.push({filter: fn, callback});
		return this;
	}

	/**
	 * Remove file path handler
	 *
	 * @param   {Function}          callback    An existing handler function for the file path
	 */
	removeHandler (callback)
	{
		const index = this.handlers.findIndex(handler => handler.callback === callback);
		if (index > -1)
		{
			this.handlers.splice(index, 1);
		}
		return this;
	}

	/**
	 * Delegated livereload function
	 *
	 * @param   {string}    file    A root-relative path; i.e. sketchpad/controllers/ExampleController.php
	 * @param   {string}    type    The type of change; will be one of "add", "change", "delete"
	 * @returns {boolean}           A flag indicating whether Sketchpad will intercept the load (true) or allow LiveReload to handle it (falsy)
	 */
	handle (file, type)
	{
		// debug
		// console.info('File change:', type, file);

		// get matching handlers
		let handlers = this.handlers
			.filter(handler => handler.filter ? handler.filter(file) : true);

		// handle
		let handled = false;
		handlers
			// changing forEach to some here, so that only the first successful handler
			// handles the file load. Not as-designed right now, but defends against a
			// 404 when controller methods change name. Also completely dependent on which
			// order handlers are added. Generally bad design which needs to be fixed outside
			// of watcher
			.forEach(handler =>
			{
				if(handler.callback(file, type))
				{
					handled = true;
					return true;
				}
			});

		// return result
		return handled;
	}

}

const watcher = new Watcher;
window.watcher = watcher;

export default watcher
