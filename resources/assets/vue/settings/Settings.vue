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
								<input v-model="settings.paths.views" v-validate-path type="text" class="form-control" name="assets" placeholder="sketchpad/views">
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
								<input v-model="settings.paths.assets" v-validate-path type="text" class="form-control" name="assets" placeholder="sketchpad/assets">
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
							<textarea class="form-control" v-model="head" name="head"></textarea>
							<p class="help-block prompt">URLs to additional JS and CSS (use <code>/{{ settings.route }}user/</code> to load user assets)</p>
						</div>
					</div>

					<div class="form-group form-group-sm">
						<label class="control-label col-sm-3">File watcher</label>
						<div class="col-sm-9">
							<select class="form-control custom" v-model="settings.watcher">
								<option value="">None</option>
								<option value="browsersync">BrowserSync</option>
								<option value="livereload">Live Reload</option>
							</select>
							<p class="help-block prompt">Specify a JavaScript watcher to use when loading assets via Sketchpad Reload</p>
						</div>
					</div>

				</fieldset>

				<fieldset name="ui">
					<legend>UI</legend>

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

export default
{
	name: 'Settings',

	components:
	{
		ControllerPaths
	},

	data()
	{
		return {
			settings: clone(settings)
		}
	},

	computed:
	{
		controllers ()
		{
			return clone(this.settings.paths.controllers);
		},

		head:
		{
			get ()
			{
				return this.settings.head
					.join('\n')
			},
			set (value)
			{
				this.settings.head = trim(value)
					.split('\n')
					.map(trim)
					.filter(value => value !== '')
			}
		}
	},

	ready ()
	{
		// watches
		this.$refs.controllers.$on('update', this.onControllersUpdate)
		this.watch([
			'settings.paths.views',
			'settings.paths.assets',
			'settings.head',
		], true)
		this.watch([
			'settings.watcher',
			'settings.ui'
		])
	},

	methods:
	{
		onSettingsChange (value, old)
		{
			this.save()
		},

		onControllersUpdate (items)
		{
			this.settings.paths.controllers = items
			this.save().then(data => store.loadAll())
		},

		save ()
		{
			Object
				.keys(settings)
				.forEach(key => app.settings[key] = this.settings[key])
			return server.post('api/settings', {settings:JSON.stringify(this.settings)})
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