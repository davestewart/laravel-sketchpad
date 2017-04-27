<p>This function takes an optional formatting string, with a syntax similar to Laravel validation:</p>
<pre class="code php">tb($data, '<?php echo $options; ?>');</pre>
<p>The following table outlines the available options:</p>

{!! tb($data, $options) !!}

<p>Within the options string, you can:</p>
<ul>
	<li>add multiple options, using the pipe character</li>
	<li>specify arguments, using a colon</li>
	<li>add multiple arguments, separated with commas</li>
</ul>

<p>Experiment with updating the options string above, or click the links below to explore some presets:</p>
<ul>
	<li><a href="?options=html:example|index">Add an index</a></li>
	<li><a href="?options=html:example|index|cols:100,400,300">Set column widths</a></li>
	<li><a href="?options=html:example|index|cols:100,400,300|index|style:background:white;z-index:1000;transform:rotate(10deg)">Set the style</a></li>
	<li><a href="?options=html:example|label:Table formatting options">Set a table caption</a></li>
	<li><a href="?options=html:example">Reset the table</a></li>
	<li><a href="?options=">Clear all settings</a></li>
</ul>
