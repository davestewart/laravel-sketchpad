<p>There are often occasions where you want to <strong>test</strong> code before running it.</p>

<p>Sketchpad allows you to set an additional boolean parameter <code>$run</code> which creates a special <code>Test / Run</code> toggle <i class="fa fa-bolt"></i> on the front end:</p>
<pre class="code php">public function processFiles($run = false) { ... }</pre>

<p>This allows you to preview output and <em>only</em> run additional code when happy with the results:</p>
<pre class="code php">
public function processFiles($run = false)
{
    // show files
    $files = $this->getFiles();
    pr($files);

    // do something with files
    if ($run)
    {
        foreach ($files as $file) { ... }
    }
}
</pre>

<p>If you prefer, you can use parameter names <code>$save</code> or <code>$update</code> (which also change the button text):</p>
<pre class="code php">public function addColumn($name = 1, $save = false) { ... }
public function editUser($id = 1, $udpate = false) { ... }</pre>

<p>Note that each time parameters are updated or the the method is called, the mode is reset to "Test".</p>
