<div class="navbar navbar-default">

	<div class="container">

		<div class="navbar-header">
			<a href="{{ $route }}" class="navbar-brand">Sketchpad</a>
			<button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
		</div>

		<div class="navbar-collapse collapse" id="navbar-main">

			<ul class="nav navbar-nav">
				<!--
				<li><input class="form-control" type="text" placeholder="Search..."></li>
				-->
			</ul>

			<ul class="nav navbar-nav navbar-right">
				<!--
				<li><a href="{{ $route }}~/new" title="New Controller">New</a></li>
				<li><a href="{{ $route }}~/settings" title="Settings">Settings</a></li>
				-->
				<li><a target="_blank" href="{{ $route }}~/help" title="Help">Help</a></li>
			</ul>

		</div>
	</div>
</div>
