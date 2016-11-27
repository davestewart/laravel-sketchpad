var path = require('path')
	
/**
 *
 * BrowserSync
 * 		- https://laracasts.com/discuss/channels/general-discussion/homestead-and-browsersync-gulp-file
 */
browserSync.init({
	proxy: 'http://timeslicelive.kiosk',
	ghostMode:false,
	notify:false,
	files: [

		// general
		'../resources/views/**/*.php',
		//'public/assets/**/*',
		elixir.config.appPath + '/**/*.php',
		elixir.config.get('public.js.outputFolder') + '**/*.js',
		elixir.config.get('public.css.outputFolder') + '**/*.css',
		elixir.config.get('public.versioning.buildFolder') + '/rev-manifest.json',

		// client callback example
		{
			match:['*'],
			fn:function(event, file)
			{
				// what is `path`? Is this a library
				file = path.normalize(__dirname + '/' + file);
				this.sockets.emit('sketchpad.udpate', {type:event, file: file});

				// in client, run this
				// ___browserSync___.socket.on('sketchpad.udpate', function(){ console.log(arguments); })
			}
		}
	]
});