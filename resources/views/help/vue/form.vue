<div id="example">
	<div class="form-group form-inline">
		<label class="" for="name">Name:</label>
		<input class="form-control input-sm" id="name" name="name" type="text" v-model="name">
	</div>
	<hr>
	<p>Hello {{ name }}!</p>
</div>

<script type="text/template" id="data">
	%name%
</script>

<script>
	new Vue({
		el:"#example",
		data:{
			name:JSON.parse($('#data').text())
		}
	});
</script>