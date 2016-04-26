@extends('doodle::content.modal)

@section('modal-content')

	<form class="form-horizontal">
		<fieldset>
			<legend>Create a new Controller</legend>
			<div class="form-group">
				<label for="inputEmail" class="col-lg-2 control-label">Name</label>
				<div class="col-lg-10">
					<input type="text" class="form-control" id="inputEmail" placeholder="Email">
					<span class="help-block">No need to add the word "Controller".</span>
				</div>
			</div>
			<div class="form-group">
				<label for="select" class="col-lg-2 control-label">Path</label>
				<div class="col-lg-10">
					<select class="form-control" id="select">
						<option>1</option>
						<option>2</option>
						<option>3</option>
						<option>4</option>
						<option>5</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="textArea" class="col-lg-2 control-label">Methods</label>
				<div class="col-lg-10">
					<textarea class="form-control" rows="3" id="textArea"></textarea>
					<span class="help-block">Enter a list of method names. You may optionally include parameters separated by spaces.</span>
				</div>
			</div>
		</fieldset>
	</form>

@endsection

