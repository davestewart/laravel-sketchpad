<template>

	<article id="settings">
		<header id="header">
			<h1>Settings</h1>
		</header>
		<section class="content">

			<form class="form-horizontal">

				<fieldset name="paths">

					<legend>Paths</legend>

					<div class="form-group form-group-sm controllers">
						<label class="control-label col-sm-3" style="margin-top: 8px">Controllers</label>
						<div class="col-sm-9">
							<controller-paths :data="controllers" v-ref:controllers></controller-paths>
						</div>
					</div>

					<div class="form-group form-group-sm">
						<label class="control-label col-sm-3">Views</label>
						<div class="col-sm-9">
							<span class="field">
								<input v-model="settings.paths.views" v-validate-path type="text" class="form-control" placeholder="sketchpad/views">
								<span class="icons">
									<i class="validate-path" aria-hidden="true"></i>
								</span>
							</span>
							<p class="help-block prompt">Root-relative path to a folder to load Sketchpad-specific Blade templates</p>
						</div>
					</div>

					<div class="form-group form-group-sm">
						<label class="control-label col-sm-3">Assets</label>
						<div class="col-sm-9">
							<span class="field">
								<input v-model="settings.paths.assets" v-validate-path type="text" class="form-control" placeholder="sketchpad/assets">
								<span class="icons">
									<i class="validate-path" aria-hidden="true"></i>
								</span>
							</span>
							<p class="help-block prompt">Root-relative path to a folder to load user scripts and styles</p>
						</div>
					</div>

				</fieldset>

				<fieldset name="assets">
					<legend>Assets</legend>

					<div class="form-group form-group-sm">
						<label class="control-label col-sm-3">Head content</label>
						<div class="col-sm-9">
							<textarea class="form-control" v-model="settings.head" autocomplete="off"></textarea>
							<p class="help-block prompt">URLs to additional JS and CSS (use <code>/{{ settings.route }}user/*</code> to load user assets)</p>
						</div>
					</div>

				</fieldset>

				<fieldset name="watch">
					<legend>Live Reload</legend>

					<div v-if="watchError" class="col-sm-offset-3 warning show">
						<p class="help-block prompt">{{ watchError}}</p>
					</div>

					<div v-if="watchHostChanged" class="col-sm-offset-3 warning">
						<p class="help-block prompt">Host changed! <a href="javascript:location.reload(); void(0)">Click here to reload the page</a>.</p>
					</div>

					<div class="form-group form-group-sm">
						<label class="control-label col-sm-3">Setup</label>
						<div class="col-sm-9">
							<select class="form-control custom" v-model="settings.livereload.preset">
								<option value="">No reloading</option>
								<option value="local">Run from local machine</option>
								<option value="vagrant">Run from virtual machine</option>
								<option value="custom">Edit settings...</option>
							</select>
							<p class="help-block prompt">Choose how and where you'll run the Sketchpad Reload task.</p>
						</div>
					</div>

					<div v-show="settings.livereload.preset==='custom'" class="form-group form-group-sm">
						<label class="control-label col-sm-3">Hostname</label>
						<div class="col-sm-9">
							<input class="form-control" v-model="settings.livereload.host">
							<p class="help-block prompt">The hostname for the live reload script</p>
						</div>
					</div>

					<div v-show="settings.livereload.preset" class="form-group form-group-sm">
						<label class="control-label col-sm-3">Additional paths</label>
						<div class="col-sm-9">
							<textarea class="form-control" v-model="settings.livereload.paths" autocomplete="off"></textarea>
							<p class="help-block prompt">Any additional root-relative paths you want to watch for file changes</p>
						</div>
					</div>

					<div v-show="settings.livereload.preset==='custom'" class="form-group form-group-sm">
						<label class="control-label col-sm-3">Options</label>
						<ul class="col-sm-9 control-group">
							<li><label><input type="checkbox" v-model="settings.livereload.usePolling"> <span>Use polling</span></label></li>
						</ul>
					</div>

				</fieldset>

				<fieldset name="ui">
					<legend id="ui">UI</legend>

					<div class="form-group form-group-sm">
						<label class="control-label col-sm-3">Homepage</label>
						<ul class="col-sm-9 control-group">
							<li><label><input type="radio" v-model="settings.ui.homepage" value="welcome"> <span>Welcome</span></label></li>
							<li><label><input type="radio" v-model="settings.ui.homepage" value="favourites"> <span>Favourites</span></label></li>
							<li><label><input type="radio" v-model="settings.ui.homepage" value="search"> <span>Search</span></label></li>
						</ul>
					</div>

					<div class="form-group form-group-sm">
						<label class="control-label col-sm-3">Navigation</label>
						<ul class="col-sm-9 control-group">
							<li><label><input type="checkbox" v-model="settings.ui.humanizeText"> <span>Humanize text</span></label></li>
							<li><label><input type="checkbox" v-model="settings.ui.showComments"> <span>Show comments</span></label></li>
							<li><label><input type="checkbox" v-model="settings.ui.showArchived"> <span>Show archived</span></label></li>
						</ul>
					</div>

					<div class="form-group form-group-sm">
						<label class="control-label col-sm-3">Output</label>
						<ul class="col-sm-9 control-group" >
							<li><label><input type="checkbox" v-model="settings.ui.formatCode"> <span>Format code</span></label></li>
							<li><label><input type="checkbox" v-model="settings.ui.appendOutput"> <span>Append output</span></label></li>
						</ul>
					</div>

				</fieldset>

			</form>

		</section>

	</article>

</template>

<script>

import _                from 'underscore';
import {clone, trim}    from '../../js/functions/utils.js';
import server           from '../../js/services/server.js';

import settings         from '../../js/state/settings.js';
import store            from '../../js/state/store.js';

import ControllerPaths  from './ControllerPaths.vue';

function textToArray(value)
{
	return trim(value)
		.split('\n')
		.map(trim)
		.filter(value => value !== '')
}

export default
{
	name: 'Settings',

	components:
	{
		ControllerPaths
	},

	data()
	{
		// modify data
		var data = clone(settings)
		data.head = data.head.join('\n');
		data.livereload.paths = data.livereload.paths.join('\n');

		// return
		return {
			watchHostChanged: false,
			watchError: false,
			settings: data
		}
	},

	computed:
	{
		controllers ()
		{
			return clone(this.settings.paths.controllers);
		}
	},

	ready ()
	{
		// error
		this.watchError = watcher.error;

		// watches
		this.$refs.controllers.$on('update', this.onControllersUpdate)
		this.watch([
			'settings.paths.views',
			'settings.paths.assets'
		], true)
		this.watch([
			'settings.ui'
		])
		this.$watch('settings.head', _.debounce(this.onHeadChange, 400));
		this.$watch('settings.watcher', this.onWatcherChange);
		this.$watch('settings.livereload.preset', this.onWatchPresetChange);
		this.$watch('settings.livereload.host', _.debounce(this.onWatchHostChange), 400);
		this.$watch('settings.livereload', _.debounce(this.onWatchSettingsChange, 400), {deep: true});
	},

	methods:
	{
		onHeadChange (value)
		{
			settings.head = textToArray(value)
		},

		onWatcherChange ()
		{
			this.save()
		},

		onWatchPresetChange (value)
		{
			switch(value)
		    {
				case 'local':
					this.settings.livereload.host = 'localhost'
					this.settings.livereload.usePolling = false
					break
				case 'vagrant':
					this.settings.livereload.host = location.hostname
					this.settings.livereload.usePolling = true
					break
				case '':
					this.settings.livereload.host = ''
					this.settings.livereload.usePolling = false
					break
			}
		},

		onWatchHostChange (value)
		{
			this.watchHostChanged = true
			setTimeout(function () {
				$('fieldset[name="watch"] .warning').addClass('show')
			}, 100)
		},

		onWatchSettingsChange (value)
		{
			this.save()
		},

		onControllersUpdate (items)
		{
			this.settings.paths.controllers = items
			this.save().then(data => store.loadAll())
		},

		onSettingsChange (value, old)
		{
			this.save()
		},

		save ()
		{
			const promise = server.post('api/settings', {settings:JSON.stringify(this.settings)})
			promise.then(result => {
				Object
					.keys(result)
					.forEach(key => app.settings[key] = result[key])
			})
			return promise
		},

		watch (fields, debounce)
		{
			let handler = this.onSettingsChange
			if (debounce)
			{
				handler = _.debounce(handler, 400)
			}
			fields.forEach(field => this.$watch(field, handler, {deep:true}))
		}

	}
}

</script>

<style lang="scss">
	
</style>