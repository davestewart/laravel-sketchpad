@extends('sketchpad::setup.layout')

@section('content')

	<h1>Howdy...</h1>

	<h2>Welcome to the Sketchpad installer</h2>

	<h3 class="text-info">To begin the installation, Sketchpad needs to know a little about your system.</h3>

	<p>Fill in the fields below so they match your current setup, then click Submit to continue.</p>

	<p>Sketchpad will create a new controllers folder (along with a couple of example
		controllers that you can edit or delete as you see fit) and will copy required assets to your public folder.</p>

	<div class="text-danger">Note that this form <strong>is not validated</strong>, so be sure before submitting that the paths are correct!</div>

	<div class="form-container">

		<div class="wegll" style="margin:30px 20px;">

			<form class="form-forizontal" method="post" action="/{{ $route }}:setup" data-ns="{{ $ns }}">

				<fieldset>

					<div class="form-group">
						<label for="name" class="control-label">Base URL</label>
						<div class="">
							<input type="text" class="form-control"
							       name="route"
							       value="{{ $config->route }}"
							       placeholder="sketchpad/">
						</div>
						<span class="help-block">This is the URL you will run Sketchpad from</span>
					</div>

					<div class="form-group">
						<label for="name" class="control-label">Base controller path</label>
						<div class="">
							<input type="text" class="form-control"
							       name="path"
							       value="{{ $config->path }}"
							       placeholder="{{ $config->path }}">
						</div>
						<span class="help-block">This will be the physical path the new Sketchpad controllers folder</span>
					</div>

					<div class="form-group">
						<label for="name" class="control-label">Base controller namespace</label>
						<div class="">
							<input type="text" class="form-control"
							       name="namespace"
							       value="{{ $config->namespace }}"
							       placeholder="{{ $config->namespace }}">
						</div>
						<span class="help-block">This will be the namespace for all newly-created Sketchpad controllers</span>
					</div>

					<div class="form-group">
						<label for="name" class="control-label">Public assets folder</label>
						<div class="">
							<input type="text" class="form-control"
							       name="assets"
							       value="{{ $config->assets }}"
							       placeholder="{{ $config->assets }}">
						</div>
						<span class="help-block">This is where Sketchpad's styles and scripts will live within your public folder</span>
					</div>

					<div class="form-group form-group-submit">
						<div class="">
							<button type="submit" class="btn btn-primary">Submit</button>
						</div>
					</div>
				</fieldset>

			</form>

			<script>

				$('input[name="path"]').on('change keyup input', function()
				{
					var ns      = $('form').attr('data-ns');
					var value   = $(this).val();
					value       = value.replace(/^app\//, ns || 'App\\');
					value       = value.replace(/\//g, '\\');

					$('input[name="namespace"]').val(value);
				}).trigger('input');
			</script>

		</div>

	</div>

@endsection
