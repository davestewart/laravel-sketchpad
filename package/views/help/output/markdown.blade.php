<p>The format of the function is:</p>
<pre>md($path, $data = []);</pre>

<p>Pass absolute paths or a <code>sketchpad::</code> path:</p>
<pre class="code php">md('sketchpad::path/to/view');</pre>

<p>You can also inject data into Markdown views, in a manner similar to Blade:</p>
<pre class="code php">// PHP
md('sketchpad::help/helpers/text', ['value' => 100]);</pre>

<pre>// Markdown
The value is @{{value}} // note: no dollar sign!</pre>

<pre>// Result
The value is 100</pre>

<p>Note that the Sketchpad view <a href="../methods/variables">route</a> variable is passed along the same as Sketchpad views:</p>

<pre>// Markdown
Navigate to [settings](@{{route}}settings#paths)</pre>

<pre>// Result
Navigate to &lt;a href="/sketchpad/settings#paths"&gt;settings&lt;/a&gt;</pre>

