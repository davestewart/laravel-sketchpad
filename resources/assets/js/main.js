$(function(){

	// ------------------------------------------------------------------------------------------------
	// app
	
		var app = window.app = new Vue(vm);


	// ------------------------------------------------------------------------------------------------
	// livereload

		// store original callback
		window.__onLiveReloadFileChanged = window._onLiveReloadFileChanged;

		// proxy livereload function
		window._onLiveReloadFileChanged = function(file)
		{
			// intercept controller updates
			if (/Controller\.php/.test(file.path))
			{
				app.reloadController(file.path);
				return false;
			}
			else
			{
				window.__onLiveReloadFileChanged(file);
			}
		}

});

