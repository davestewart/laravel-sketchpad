<template>

	<article id="settings">
		<header id="header">
			<h1>Settings</h1>
		</header>
		<section class="content">

			<fieldset name="paths">
				<legend>Paths</legend>
				<ul class="sortable">
					<li v-for="path in settings.config.paths" :data-index="$index" class="form-inline form-group form-group-sm">
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
					<article>
						<label>Homepage</label>
						<ul>
							<li class="radio"><label><input type="radio" v-model="settings.ui.homepage" value="welcome"> Welcome</label></li>
							<li class="radio"><label><input type="radio" v-model="settings.ui.homepage" value="favourites"> Favourites</label></li>
							<li class="radio"><label><input type="radio" v-model="settings.ui.homepage" value="search"> Search</label></li>
						</ul>
					</article>
					<article>
						<label>Navigation</label>
						<ul>
							<li class="checkbox"><label><input type="checkbox" v-model="settings.ui.humanizeText"> Humanize text</label></li>
							<li class="checkbox"><label><input type="checkbox" v-model="settings.ui.showComments"> Show comments</label></li>
							<li class="checkbox"><label><input type="checkbox" v-model="settings.ui.showArchived"> Show archived</label></li>
						</ul>
					</article>
					<article>
						<label>Output</label>
						<ul>
							<li class="checkbox"><label><input type="checkbox" v-model="settings.ui.formatCode"> Format code</label></li>
							<li class="checkbox"><label><input type="checkbox" v-model="settings.ui.appendOutput"> Append output</label></li>
						</ul>
					</article>
				</section>

			</fieldset>
		</section>

	</article>

</template>

<script>

import server       from '../../js/services/server.js';
import settings     from '../../js/state/settings.js';
import store        from '../../js/state/store.js';
import state        from '../../js/state/state.js';

export default
{
	name: 'Settings',

	data()
	{
		return { settings }
	},

	created ()
	{
	},

	ready ()
	{
		this.$watch('settings', this.onSettingsChange, {deep:true})
		this.$watch('settings.config.paths', this.onPathsChange, {deep:true})
		$('.sortable').sortable({
			axis: 'y',
			helper: 'clone',
			tolerance: 'pointer',
			containment: 'parent',
			update: this.onPathsSort
		})
	},

	destoyed ()
	{
		$('.sortable').sortable('destroy')
	},

	methods:
	{
		onSettingsChange (value, old)
		{
			server.post('api/settings', {settings:JSON.stringify(this.settings)})
		},

		onPathsChange (value, old)
		{
			server
				.post('api/settings', {settings:JSON.stringify(this.settings)})
				.then(data => store.loadAll())
		},

		onPathsSort: function (event, ui) {
			const newIndex = ui.item.index()
			const oldIndex = parseInt(ui.item.attr('data-index'))
	        const list = this.settings.config.paths
	        list.splice(newIndex, 0, list.splice(oldIndex, 1)[0])
	    }

	}
}

</script>

<style lang="scss">
	
</style>