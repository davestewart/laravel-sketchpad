<p>Like normal Laravel controllers, Sketchpad supports dependency injection via type hinting:</p>
<pre class="code php">
public function typeHinting(SketchpadConfig $config)
{
    pr($config->settings->data);
}
</pre>

<p>Here's the output:</p>

{!! pr($config->settings->data) !!}