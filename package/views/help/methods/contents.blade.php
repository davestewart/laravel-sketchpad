<p>To show a <a href="methods">contents</a> page when you click on your controllers add an <code>index()</code> method and echo or return some content:</p>
<pre class="code php">
class SomeController extends Controller
{
    public function index()
    {
        md('path.to.index'); // example uses markdown, but you could just as easily use Blade
    }
}</pre>

<p>If you want to cheat, just save a markdown file in the same folder as the controller:</p>

<pre class="code php">
md(__DIR__ . '/some.md');
</pre>

<p>See the <a href="../output/markdown">markdown</a> example for more info about the <code>md()</code> method.</p>
