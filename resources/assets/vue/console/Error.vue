<template>
	<div id='console-error'>
		<header id='header'>
			<h1>{{ error.name }}</h1>
			<div class='info alert alert-danger'>{{ error.info }}</div>
		</header>
		<section id='output' class='content'>{{ error.html }}</section>
	</div>
</template>

<script>

const errors =
{
	'route':
	{
		name: 'Error',
		info: 'Invalid route',
		html: 'Sketchpad couldn\'t find a matching controller / method for that route'
	},

	'parse':
	{
		name: 'Error',
		info: 'Invalid controller',
		html: 'There was an issue parsing the current controller. Fix the errors and resave / reload the file'
	},
	
	'default':
	{
		name: 'Error',
		info: 'Unknown error',
		html: 'Something went wrong. Sorry!'
	}
}

export default
{
	name: 'Error',
	
	props: ['controller'],

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
			if (this.controller)
			{
				return errors.parse;
			}
			return errors.route;
		}
	}
}

</script>

<style lang='scss'>
	
</style>