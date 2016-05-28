;function Reloader(app)
{
	// properties
	this.app = app;
	this.paths = app.paths;

	// monkeypatch livereloader
	var reload 	= LiveReload.reloader.reload;
	var monkey	= this;
	LiveReload.reloader.reload = function(path, options)
	{
		if(monkey.reload(path))
		{
			return;
		}
		return reload.call(this, path, options);
	};
}

Reloader.prototype =
{
	app:null,

	paths:[],

	reload:function(path)
	{
		// intercept controller updates
		if (/Controller\.php$/.test(path))
		{
			this.app.reloadController(path);
			return true;
		}

		// php file
		if(/\.php$/.test(path))
		{
			this.app.reloadMethod();
			return true;
		}

		// let LiveReload handle the load
		return false;
	}
};
