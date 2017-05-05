<p>The result of this call is:</p>
<pre>Hello, {{ $name }}!</pre>
<p>Optional parameters are exposed as editable front-end inputs:</p>
<pre class="code php">
public function parameters($name = 'World')
{
    echo "Hello, $name!";
}
</pre>
<p>Update the parameter to automatically call the method again</p>
