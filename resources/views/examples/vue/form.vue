<div id="example">
	<p><label>Name: <input type="text" v-model="name"></label></p>
	<p>Hello {{ name }} !</p>
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