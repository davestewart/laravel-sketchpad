<template>

	<article id="settings">

		<h1>Settings</h1>
		<form class="form">

			<fieldset name="homepage">
				<h3>Homepage</h3>
				<ul>
					<li><label><input type="radio" v-model="settings.ui.homepage" value="intro"> Intro</label></li>
					<li><label><input type="radio" v-model="settings.ui.homepage" value="favourites"> Favourites</label></li>
				</ul>
			</fieldset>

			<fieldset name="paths">
				<legend>Paths</legend>
				<ul class="form-inline" v-sortable="{handle: '.handle', onUpdate: onPathsReorder}">
					<li v-for="path in settings.config.paths" class="form-group">
						<input class="form-control" name="checkbox" type="checkbox" v-model="path.enabled">
						<input class="form-control" name="name" type="input" v-model="path.name">
						<input class="form-control" name="path" type="input" v-model="path.path">
						<i class="handle fa fa-arrows" aria-hidden="true"></i>
					</li>
				</ul>
			</fieldset>

			<fieldset name="ui">
				<legend>UI</legend>
				<p>Navigation</p>
				<ul>
					<li><label><input type="checkbox" v-model="settings.ui.useLabels"> Use labels</label></li>
					<li><label><input type="checkbox" v-model="settings.ui.showComments"> Show comments</label></li>
					<li><label><input type="checkbox" v-model="settings.ui.showArchived"> Show archived</label></li>
				</ul>

				<p>Output</p>
				<ul>
					<li><label><input type="checkbox" v-model="settings.ui.formatCode"> Format code</label></li>
					<li><label><input type="checkbox" v-model="settings.ui.appendResult"> Append result</label></li>
				</ul>

			</fieldset>

			<pre>{{ settings | json }}</pre>

		</form>
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