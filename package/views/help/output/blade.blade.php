<p>Sketchpad namespaces your <code>views/</code> folder to <code>sketchpad::</code> so you can silo and load development views separately from your app:</p>
<pre class="code php">
echo view('sketchpad::some-view'); // loads from "<?php echo $views; ?>"
</pre>
<p>Note that you can still load views from your application, using the normal syntax:</p>
<pre class="code php">
echo view('some-view'); // loads from "resources/views/"
</pre>
