<template>

	<header id="top-nav" class="navbar navbar-default">
		<div class="container-fluid">

			<div class="navbar-header">
				<a v-link="'/'" class="navbar-brand">{{ settings.site.name }}</a>
				<button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
					<i class="fa fa-bars" aria-hidden="true"></i>
				</button>
			</div>

			<div class="navbar-collapse collapse" id="navbar-main">
				<ul class="nav navbar-nav navbar-right">
					<li><a v-if="settings.site.search" v-link="'/search'" title="Search">Search</a></li>
					<li><a v-if="settings.site.favourites" v-if="links" v-link="'/favourites'" title="Favourites">Favourites</a></li>
					<li v-if="admin.settings"><a v-link="'/settings'" title="Settings">Settings</a></li>
					<li v-if="settings.site.help !== 'none'"><a v-link="'/help'" title="Help">Help</a></li>
				</ul>
			</div>

		</div>
	</header>

</template>

<script>

import admin    from '../../js/state/admin';
import settings from '../../js/state/settings';

export default
{
	data ()
	{
		return { admin, settings }
	}

}

</script>

<style lang="scss">

</style>