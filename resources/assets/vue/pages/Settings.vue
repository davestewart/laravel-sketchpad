<template>

	<article id="settings">
		<header>
			<h1>Settings</h1>
		</header>
		<section>
			<fieldset name="paths">
				<legend>Paths</legend>
				<ul class="form-inline" v-sortable="{handle: '.handle', onUpdate: onPathsReorder}">
					<li v-for="path in settings.config.paths" class="form-group form-group-sm">
						<input class="form-control" name="checkbox" type="checkbox" v-model="path.enabled">
						<input class="form-control" name="name" type="input" v-model="path.name">
						<input class="form-control" name="path" type="input" v-model="path.path">
						<i class="handle fa fa-arrows" aria-hidden="true"></i>
					</li>
				</ul>
			</fieldset>

			<fieldset name="ui">
				<legend>UI</legend>

				<section style="margin-left:25px">

					<label>Homepage</label>
					<ul>
						<li class="radio"><label><input type="radio" v-model="settings.ui.homepage" value="welcome"> Welcome</label></li>
						<li class="radio"><label><input type="radio" v-model="settings.ui.homepage" value="favourites"> Favourites</label></li>
						<li class="radio"><label><input type="radio" v-model="settings.ui.homepage" value="search"> Search</label></li>
					</ul>

					<label>Navigation</label>
					<ul>
						<li class="checkbox"><label><input type="checkbox" v-model="settings.ui.humanizeText"> Humanize text</label></li>
						<li class="checkbox"><label><input type="checkbox" v-model="settings.ui.showComments"> Show comments</label></li>
						<li class="checkbox"><label><input type="checkbox" v-model="settings.ui.showArchived"> Show archived</label></li>
					</ul>

					<label>Output</label>
					<ul>
						<li class="checkbox"><label><input type="checkbox" v-model="settings.ui.formatCode"> Format code</label></li>
						<li class="checkbox"><label><input type="checkbox" v-model="settings.ui.appendOutput"> Append output</label></li>
					</ul>

				</section>

			</fieldset>
		</section>

	</article>

</template>

<script>

import server from '../../js/services/server/server';

export default
{
	props: ['settings'],

	data:
	{

	},

	created ()
	{
		this.$watch('settings', this.onSettingsChange, {deep:true})
		this.$watch('settings.config.paths', this.onPathsChange, {deep:true})
	},

	methods:
	{
		onSettingsChange (value, old)
		{
			server.post('api/settings', {settings:JSON.stringify(this.settings)}, console.log)
		},

		onPathsChange (value, old)
		{
			server
				.post('api/settings', {settings:JSON.stringify(this.settings)})
				.then(data => app.store.reloadAll())
		},

		onPathsReorder: function (event) {
	        const list = this.settings.config.paths
	        list.splice(event.newIndex, 0, list.splice(event.oldIndex, 1)[0])
	    }

	}
}

</script>

<style lang="scss">
	
</style>