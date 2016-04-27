<p>Creating a new controller will allow you to add methods and begin testing code within the Sketchpad interface.</p>

<form class="form-horizontal">
	<fieldset>
		<div class="form-group">
			<label for="name" class="control-label">Name</label>
			<div class="">
				<input type="text" class="form-control" name="name" placeholder="Name">
				<span class="help-block">No need to add the word "Controller".</span>
			</div>
		</div>
		<div class="form-group">
			<label for="path" class="control-label">Path</label>
			<div class="">
				<select class="form-control" name="path">
					@foreach($routes as $route)
						@if($route instanceof \davestewart\doodle\objects\route\FolderReference)
					<option value="{{ $route->route }}">{{ $route->route }}</option>
						@endif
					@endforeach
					<option value="">New...</option>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label for="methods" class="control-label">Methods</label>
			<div class="">
				<textarea class="form-control" rows="10" name="methods"></textarea>
				<span class="help-block">Enter a list of method names. You may optionally include parameters separated by spaces.</span>
			</div>
		</div>
		<div class="form-group">
			<div class="">
				<button type="reset" class="btn btn-default">Cancel</button>
				<button type="submit" class="btn btn-primary">Create</button>
			</div>
		</div>
	</fieldset>
</form>
