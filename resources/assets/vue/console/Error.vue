<template>
	<div id="console-error">
		<header id="header">
			<h1>{{ error.name }}</h1>
			<div class="info alert alert-danger">{{ error.info }}</div>
		</header>
		<section id="output" class="content">{{ error.html }}</section>
	</div>
</template>

<script>

const errors =
{
	'404':
	{
		name: 'Error',
		info: 'Invalid route',
		html: "Sketchpad couldn't find a matching controller / method for that route"
	},

	'controller':
	{
		name: 'Error',
		info: 'Invalid controller',
		html: "There was an issue parsing the current controller. Fix the errors and resave / reload the file"
	}
}

export default
{
	name: 'Error',

	data ()
	{
		return {
			type: '404'
		}
	},

	computed:
	{
		error ()
		{
			return errors[this.type || '404']
		}
	}
}

</script>

<style lang="scss">
	
</style>