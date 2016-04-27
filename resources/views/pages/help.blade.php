
<h3>Front end</h3>

<p>Sketchpad is designed to be point-and-click easy.</p>
<p>Simply click controllers and methods on the left to see output on the right!</p>

<p>Some interactivity tips:</p>

<ul>
	<li>If a method has parameters, these will be available to edit via on-screen controls, or by the URL</li>
	<li>To see raw output, CTRL/Command click a method name</li>
</ul>

<h3>Back end</h3>

<p>Add code by creating new controllers in your application's <code>Controllers/{{ $route }}</code> folder, and adding named methods.</p>
<p>Add as many controllers and folders as you'd like; divide up functionality by job, feature, category; you name it.</p>
<p>No need to worry about routing; all this is taken care or for you!</p>

<p>Regarding the code:</p>

<ul>
	<li>Controllers can just <code>echo</code> or otherwise output to the page; no need to return a view or response</li>
	<li>Doc comments are parsed and passed to the front end. Use the special <code>@@label</code> parameter to overide labels for controllers and methods</li>
	<li>Sketchpad will only run when <code>APP_DEBUG</code> is set to true</li>
</ul>

<h4>Helper methods</h4>

<p>The following helper methds are available:</p>

<ul>
	<li><code>pr()</code> : PHP's <code>print_r()</code> with <code>pre</code> tags; takes multiple parameters</li>
	<li><code>vd()</code> : PHP's <code>vardump()</code> with <code>pre</code> tags</li>
	<li><code>dump()</code> : Laravel's <code>dump()</code> without the <code>die()</code></li>
</ul>

<h3>Config</h3>

<p>If you've gotten this far, then you probably don't need to worry about this!</p>
<p>However, you can change the route Sketchpad runs on by changing the <code>route</code> parameter in the config file.</p>