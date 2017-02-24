export default function (store) {

	// decorate LiveReload
	if(window.LiveReload)
	{
		const reload = LiveReload.reloader.reload;
		LiveReload.reloader.reload = function(file, options)
		{
			if(store.reload(file))
			{
				return true;
			}
			return reload.call(store, file, options);
		};
	}

	// listen to browsersync
	if(window.___browserSync___)
	{
		___browserSync___
			.socket.on('sketchpad.udpate', function(event) {
				console.log(arguments);
				// TODO check this is the proper call
				store.reload(event.file)
			})
	}

}

