<!-- template; include an id-->
<div id="example">
	<p>This is a sample Vue application; start typing to see it update:</p>

	<div style="margin:25px" class="form-group form-inline">
		<label>Hello</label>
		<input class="form-control input-sm" type="text" v-model="name">
		<label> results in <span style="color: red">Hello {{ name }} !</span></label>
	</div>

	<p>Pass template and data from Laravel like so:</p>
	<pre><code class="php">vue('sketchpad::help.vue.form', ['name' => 'World']);</code></pre>

	<p>Then reference it in Vue using the injected variable <code>$data</code>:</p>

	<pre><code class="js">new Vue({
    el: "#example",
    data: $data
});</code></pre>

	<p>View the source method and template at <code>vendor/davestewart/sketchpad/resources/views/help/vue/form.vue</code> to see how it's done.</p>
</div>

<!-- script; reference `el` by id -->
<script>
	new Vue({
		el: "#example",
		data: $data
	});
</script>