<p>The format of the function is:</p>
<pre class="code php">tb($values, $options = '');</pre>
<p>The table helper can render:</p>
<ul>
	<li>Arrays</li>
	<li>Any <code>Arrayable</code> class (such as Collections)</li>
	<li>Any <code>Paginator</code> instance</li>
</ul>
<p>The options parameter passes formatting arguments, with a syntax similar to Laravel validation:</p>
<pre class="code php">tb($data, '<?php echo $options; ?>');</pre>

<p>Within the options string, you can:</p>
<ul>
	<li>add multiple options, using the pipe character</li>
	<li>specify arguments, using a colon</li>
	<li>add multiple arguments, separated with commas</li>
</ul>

<p>Valid options for tables are:</p>

{!! tb($data, 'cols:100,400,300|html:example') !!}

<style>
table a {
	text-decoration: none !important;
}
table code {
	cursor: pointer;
}
table code:hover {
	background: #CCC;
	color:#FFF;
}
table.fancy,
table.fancy th,
table.fancy td {
	border: 1px dashed black !important;
	background: #EEE;
	color: #18bc9c;
}
</style>

<p>Experiment with updating the options parameter above, or click the values in the table above to update the example table below:</p>

{!! tb($preview, $options) !!}
