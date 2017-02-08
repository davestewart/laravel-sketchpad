<template>

	<h1>Settings</h1>
	<form class="form">


		<p>Homepage</p>
		<li><label><input type="radio" v-model="homepage" value="intro">Intro</label></li>
		<li><label><input type="radio" v-model="homepage" value="favourites">Favourites</label></li>
		<li><label><input type="radio" v-model="homepage" value="view">View: ______________________</label></li>

		<p>Paths</p>
		<li><label><input type="checkbox" v-model="paths.project">Project</label></li>
		<li><label><input type="checkbox" v-model="paths.help">Help</label></li>

		<p>Controllers / Methods</p>
		<li><label><input type="checkbox" v-model="humanizeNames">Humanize names</label></li>
		<li><label><input type="checkbox" v-model="showComments">Show comments</label></li>
		<li><label><input type="checkbox" v-model="showArchived">Show archived</label></li>

		<p>Organisation:</p>
		<li><label><input type="radio" v-model="organisation" value="flat">Flat</label></li>
		<li><label><input type="radio" v-model="organisation" value="organise.indented">Indented</label></li>
		<li><label><input type="radio" v-model="organisation" value="organise.tree">Tree</label></li>

		<p>Parameters</p>

		<p>Update on:</p>
		<li><label><input type="radio" v-model="update" value="input">input</label></li>
		<li><label><input type="radio" v-model="update" value="change">change</label></li>

		<fieldset>
			<div class="form-group">
				<label for="inputEmail" class="col-lg-2 control-label">Email</label>
				<div class="col-lg-10">
					<input type="text" class="form-control" id="inputEmail" placeholder="Email">
				</div>
			</div>
			<div class="form-group">
				<label for="inputPassword" class="col-lg-2 control-label">Password</label>
				<div class="col-lg-10">
					<input type="password" class="form-control" id="inputPassword" placeholder="Password">
					<div class="checkbox">
						<label>
							<input type="checkbox"> Checkbox
						</label>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label for="textArea" class="col-lg-2 control-label">Textarea</label>
				<div class="col-lg-10">
					<textarea class="form-control" rows="3" id="textArea"></textarea>
					<span class="help-block">A longer block of help text that breaks onto a new line and may extend beyond one line.</span>
				</div>
			</div>


			<div class="form-group">
				<label class="col-lg-2 control-label">Radios</label>

				<div class="col-lg-10">
					<div class="radio">
						<label>
							<input type="radio" v-model="">Option two can be something else
						</label>
					</div>
				</div>


			</div>

			<div class="form-group">
				<label class="col-lg-2 control-label">Checkboxes</label>

				<div class="col-lg-10">
					<div class="checkbox">
						<label>
							<input type="radio" v-model="">Option two can be something else
						</label>
					</div>
				</div>


			</div>

			<div class="form-group">
				<label for="select" class="col-lg-2 control-label">Selects</label>
				<div class="col-lg-10">
					<select class="form-control" id="select">
						<option>1</option>
						<option>2</option>
						<option>3</option>
						<option>4</option>
						<option>5</option>
					</select>
					<br>
					<select multiple="" class="form-control">
						<option>1</option>
						<option>2</option>
						<option>3</option>
						<option>4</option>
						<option>5</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<div class="col-lg-10 col-lg-offset-2">
					<button type="reset" class="btn btn-default">Cancel</button>
					<button type="submit" class="btn btn-primary">Submit</button>
				</div>
			</div>
		</fieldset>
	</form>

</template>

<script>
	
export default
{

}

</script>

<style lang="scss">
	
</style>