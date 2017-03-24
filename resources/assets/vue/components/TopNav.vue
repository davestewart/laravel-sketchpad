<template>

	<header id="top-nav" class="navbar navbar-default">
		<div class="container-fluid">

			<div class="navbar-header">
				<a v-link="'/'" class="navbar-brand">Sketchpad</a>
				<button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
					<i class="fa fa-bars" aria-hidden="true"></i>
				</button>
			</div>

			<div class="navbar-collapse collapse" id="navbar-main">
				<ul class="nav navbar-nav navbar-right">
					<li><a v-link="'/settings'" title="Settings">Settings</a></li>
					<li><a v-link="'/help'" title="Help">Help</a></li>
				</ul>
			</div>

		</div>
	</header>

</template>

<script>

export default
{
	methods:
	{

	}

}

</script>

<style lang="scss">

</style>