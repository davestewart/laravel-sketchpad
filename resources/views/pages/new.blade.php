<h1>New Controller</h1>


<form class="form-horizontal">

	<div class="form-group">
		<div class="col-lg-push-2 col-lg-10">
			<p>Enter some basic parameters here, and Sketchpad will build and save the controller code for you.</p>
		</div>
	</div>

	<fieldset>

		<div class="form-group">
			<label for="path" class="col-lg-2 control-label">Location</label>
			<div class="col-lg-10">
				<select class="form-control" name="path">
					@foreach($folders as $route => $value)
						<option value="{{ $route }}">{{ $route }}</option>
					@endforeach
					<option value="">New...</option>
				</select>
				<span class="help-block">This is the folder location where your new controller will be saved</span>
			</div>
		</div>

		<div class="form-group">
			<label for="name" class="col-lg-2 control-label">Name</label>
			<div class="col-lg-10">
				<input type="text" class="form-control" name="name" placeholder="Database, Users, Tests, etc...">
				<span class="help-block">No need to add the word "Controller", as Sketchpad will do that for you</span>
			</div>
		</div>

		<div class="form-group">
			<label for="methods" class="col-lg-2 control-label">Methods</label>
			<div class="col-lg-10">
				<textarea class="form-control" rows="10" name="methods" placeholder="foo
bar param1
baz param1 param2
etc..."></textarea>
				<span class="help-block">List method names, optionally followed by space-delimited parameters</span>
			</div>
		</div>

		<!--
		<div class="form-group">
			<div class="col-lg-push-2 col-lg-10">
				<button type="reset" class="btn btn-default">Cancel</button>
				<button type="submit" class="btn btn-primary">Create</button>
			</div>
		</div>
		-->
	</fieldset>
</form>
