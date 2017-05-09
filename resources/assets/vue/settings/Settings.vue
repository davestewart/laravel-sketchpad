<template>

	<article id="settings">
		<header>
			<h1>Settings</h1>
		</header>
		<section class="content">

			<div v-if="!enabled">
				<h3 class="text-danger">Settings are disabled</h3>
				<p>If you think this is a mistake, contact your System Administrator.</p>
				<p>If you are the System Administrator see the <a href="https://github.com/davestewart/laravel-sketchpad/wiki/Admin" target="_blank">wiki</a> on how to enable setup.</p>

			</div>
			<form v-else class="form-horizontal">

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

				<fieldset name="site">
					<legend>Site</legend>

					<div class="form-group form-group-sm">
						<label class="control-label col-sm-3">Name</label>
						<div class="col-sm-9">
							<span class="field">
								<input v-model="settings.site.name" type="text" class="form-control" placeholder="Sketchpad">
								<span class="icons">
									<i class="validate-path" aria-hidden="true"></i>
								</span>
							</span>
							<p class="help-block prompt">Text that will be used to build page titles</p>
						</div>
					</div>

					<div class="form-group form-group-sm">
						<label class="control-label col-sm-3">Home page</label>
						<div class="col-sm-9">
							<select class="form-control custom" v-model="settings.site.home">
								<option value="welcome">Welcome</option>
								<option value="custom">Custom</option>
							</select>
							<p class="help-block prompt">Home page that will show when you load Sketchpad</p>
						</div>
					</div>

					<div class="form-group form-group-sm">
						<label class="control-label col-sm-3">Help page</label>
						<div class="col-sm-9">
							<select class="form-control custom" v-model="settings.site.help">
								<option value="help">Help</option>
								<option value="custom">Custom</option>
								<option value="none">None</option>
							</select>
							<p class="help-block prompt">Help page to provide basic information to get users started</p>
						</div>
					</div>

					<div class="form-group form-group-sm">
						<label class="control-label col-sm-3">Discovery</label>
						<ul class="col-sm-9 control-group">
							<li><label><input type="checkbox" v-model="settings.site.search"> <span>Search</span></label></li>
							<li><label><input type="checkbox" v-model="settings.site.favourites"> <span>Favourites</span></label></li>
						</ul>
					</div>

				</fieldset>

				<fieldset name="livereload">
					<legend>Live Reload</legend>

					<div v-if="watchError" class="col-sm-offset-3 warning show">
						<p class="help-block prompt">{{ watchError}} <a href="javascript:location.reload(); void(0)">Click here to try again</a>.</p>
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
							<input class="form-control" spellcheck="false" v-model="settings.livereload.host">
							<p class="help-block prompt">The hostname for the live reload script</p>
						</div>
					</div>

					<div v-show="settings.livereload.preset" class="form-group form-group-sm">
						<label class="control-label col-sm-3">Additional paths</label>
						<div class="col-sm-9">
							<textarea class="form-control" v-model="settings.livereload.paths" spellcheck="false" autocomplete="off"></textarea>
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
					<legend>UI</legend>

					<div class="form-group form-group-sm">
						<label class="control-label col-sm-3">Navigation</label>
						<ul class="col-sm-9 control-group">
							<li><label><input type="checkbox" v-model="settings.ui.humanizeText"> <span>Humanize text</span></label></li>
							<li><label><input type="checkbox" v-model="settings.ui.showComments"> <span>Show comments</span></label></li>
							<li><label><input type="checkbox" v-model="settings.ui.showArchived"> <span>Show archived</span></label></li>
						</ul>
					</div>

					<div class="form-group form-group-sm">
						<label class="control-label col-sm-3">Console</label>
						<ul class="col-sm-9 control-group" >
							<li><label><input type="checkbox" v-model="settings.ui.scrollTop"> <span>Scroll to top on load</span></label></li>
							<li><label><input type="checkbox" v-model="settings.ui.formatCode"> <span>Format code</span></label></li>
						</ul>
					</div>
				</fieldset>

			</form>

		</section>

	</article>

</template>

<script>

import _                from 'underscore';
import {clone,
		trim,
		scrollTo}       from '../../js/functions/utils';
import Helpers          from '../../js/functions/helpers';
import server           from '../../js/services/server';

import admin            from '../../js/state/admin';
import settings         from '../../js/state/settings';
import store            from '../../js/state/store';

import ControllerPaths  from './ControllerPaths.vue';

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
		data.livereload.paths = data.livereload.paths.join('\n');

		// return
		return {
			enabled: admin.settings,
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
		if (!this.enabled)
		{
			return;
		}

		// error
		this.watchError = watcher.error;

		// watches
		this.$refs.controllers.$on('update', this.onControllersUpdate)

		// debounce
		this.watchField([
			'settings.paths.assets',
			'settings.paths.views'
		], {debounce: true});

		// deep
		this.watchField([
			'settings.watcher',
			'settings.ui'
		], {deep:true});

		// shallow
		this.watchField([
			'settings.site.home',
			'settings.site.help',
			'settings.site.search',
			'settings.site.favourites'
		]);

		// custom
		this.watchField('settings.site.name', {debounce:true}, this.onSiteNameChange);
		this.watchField('settings.livereload.preset', null, this.onWatchPresetChange);
		this.watchField('settings.livereload.host', {debounce:true}, this.onWatchHostChange);
		this.watchField('settings.livereload', {debounce: true, deep: true}, this.onWatchSettingsChange);

		// scroll
		if (location.hash)
		{
			const fieldset = 'fieldset[name="' +location.hash.substr(1)+ '"]';
			scrollTo(fieldset, -100, 250);
		}
	},

	methods:
	{
		onControllersUpdate (items)
		{
			this.settings.paths.controllers = items
			this.save().then(data => store.loadAll())
		},

		onSiteNameChange (value)
		{
			Helpers.setTitle('settings', value);
			this.save();
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
				$('fieldset[name="livereload"] .warning').addClass('show')
			}, 100)
		},

		onWatchSettingsChange (value)
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

		watchField (fields, options, handler = false)
		{
			// defaults
			options = Object.assign({debounce: false, deep: false}, options);
			handler = handler || this.save;

			// params
			if (options.debounce)
			{
				handler = _.debounce(handler, 400)
			}
			if (typeof fields === 'string')
			{
				fields = [fields];
			}

			// watch
			fields.forEach(field => this.$watch(field, handler, {deep:options.deep}))
		}

	}
}

</script>

<style lang="scss">
	
</style>