<p>Relative paths run other methods:</p>
<ul>
	<li>This links to the <a href="forms">forms</a> method in the same controller</li>
	<li>This links to one of the <a href="../../demo/tools/dumpapp">sample tools</a> in the tools controller</li>
</ul>

<p>Use the special <code>sketchpad:</code> protocol to link directly to methods:</p>
<ul>
	<li>This links to the same <a href="sketchpad:help/demo/tools/dumpapp">sample tool</a> in the tools controller</li>
</ul>

<p>Absolute paths (outside of sketchpad) run as normal:</p>
<ul>
	<li>This loads the <a href="/">main app</a></li>
</ul>

<p>External links run as normal:</p>
<ul>
	<li>This links to <a href="http://google.com" target="_blank">Google</a> in a new window</li>
	<li>This runs some <a href="javascript:alert('Sketchpad rocks!')">JavaScript</a></li>
</ul>
