<p>The format of the function is variadic, as it passes its values to the static <code>Code</code> helper class:</p>
<pre>code( ... );</pre>
<p>Depending on the values passed in, the function can output:</p>
<ul>
	<li>Raw code</li>
	<li>Files</li>
	<li>Sections of files</li>
	<li>Classes</li>
	<li>Methods</li>
</ul>
<p>The selected option above calls:</p>
<pre class="code php">code{{ $signature }};</pre>

<p>Outputing - <strong>{{ $desc }}</strong> :</p>
<?php
call_user_func_array('code', $args);
?>

<p>You can call the <code>Code</code> class directly if you prefer:</p>
<pre class="code php">
Code::{{ $func }}{{ $signature }};
</pre>

<p>Check its source code at:</p>
<pre>vendor/davestewart/sketchpad/src/utils/Code.php</pre>