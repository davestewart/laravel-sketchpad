<p>Sketchpad supports both implicit and explicit pagination.</p>

<p><strong>Implicitly</strong>, Sketchpad intercepts all page links and passes along all GET values from the front to the back end.</p>
<pre class="code php">
public function pagination ($start = 1, $length = 10)
{
    // $_GET['page'] is passed if present in the URL
}
</pre>
<p>This allows Laravel's built-in pagination to work without any extra input:</p>

{!! tb($items) !!}
{!! $paginator->links() !!}

<p><strong>Explicitly</strong>, you can also add a <code>page</code> parameter to your methods, which will always be part of the URL, allowing you to set pagination manually:</p>
<pre class="code php">
public function pagination ($start = 1, $length = 10, $page = 1)
{
    // manually set up pagination
}
</pre>