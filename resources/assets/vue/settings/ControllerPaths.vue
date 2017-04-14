<template>

	<div>
		<ul class="sortable deletable">
			<li v-for="item in items" :data-index="$index" :data-disabled="!item.enabled">
				<span class="field">
					 <input class="form-control" autocomplete="off" type="input" name="name" v-model="item.name" :disabled="!item.enabled" @input="onNameChange">
					:<input class="form-control" autocomplete="off" type="input" name="path" v-model="item.path" :disabled="!item.enabled" v-validate-path>
					<span class="icons">
						<i class="validate-path" aria-hidden="true"></i>
						<i class="handle fa fa-arrows-v" aria-hidden="true" title="Reorder"></i>
						<i :class="{toggle:true, fa:true, 'fa-eye':item.enabled, 'fa-eye-slash':!item.enabled}" aria-hidden="true" title="Toggle"></i>
					</span>
				</span>
				<i class="delete fa fa-times" aria-hidden="true" title="Delete"></i>
			</li>
		</ul>
		<button style="margin-top: 2px;" class="pull-right btn btn-xs">Add path...</button>
		<p class="help-block prompt">Root-relative paths to controller folders that Sketchpad will analyse</p>
	</div>

</template>

<script>

import _            from 'underscore'
import {trim}       from '../../js/functions/utils';
import server       from '../../js/services/server';

export default
{
// ---------------------------------------------------------------------------------------------------------------------
// data

	name: 'ControllerPaths',

	props: ['data'],

	data ()
	{
		return {items:this.data}
	},


// ---------------------------------------------------------------------------------------------------------------------
// lifecycle

	ready ()
	{
		this.onPathChange = _.debounce(this.onPathChange, 400)
		this.onNameChange = _.debounce(this.onNameChange, 400)
		$('.controllers button').on('click', this.onAdd)
		$('.controllers .sortable')
			.on('click', 'i.delete', this.onDelete)
			.on('click', 'i.toggle', this.onToggle)
			.sortable({
				axis: 'y',
				helper: 'clone',
				handle: '.handle',
				tolerance: 'pointer',
				containment: '.controllers',
				update: this.onOrder
			})
	},

	destoyed ()
	{
		$('.sortable')
			.off()
			.sortable('destroy')
	},


// ---------------------------------------------------------------------------------------------------------------------
// methods

	methods:
	{
		update ()
		{
			this.$emit('update', this.items)
		},

		onNameChange (value, old)
		{
			if (trim(value) !== '')
			{
				this.update()
			}
		},

		onPathExists (data)
		{
			this.update()
		},

		onOrder (event, ui) {
			const newIndex = ui.item.index()
			const oldIndex = parseInt(ui.item.attr('data-index'))
	        const list = this.items
	        list.splice(newIndex, 0, list.splice(oldIndex, 1)[0])
	        this.update()
	    },

	    onToggle (event)
	    {
	        const index = $(event.target).closest('li').index()
	        const item = this.items[index]
	        item.enabled = ! item.enabled
	        this.update()
	    },

	    onDelete (event)
	    {
	        const index = $(event.target).closest('li').index()
	        this.items.splice(index, 1)
	        this.update()
	    },

	    onAdd (event)
	    {
	        event.preventDefault()
	        this.items.push({enabled: true, name: 'name', path: 'path'})
	        this.$nextTick(() => $('input[name="name"]').last().select().focus())
	    }

	}
}

</script>

<style lang="scss">
	
</style>