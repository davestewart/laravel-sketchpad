<p>The purpose of Sketchpad is to provide a front-end wrappper around your controllers and methods.</p>

<p>It's really quite simple:</p>

<ul>
	<li>the UI mirrors your controllers and methods 1:1</li>
	<li>controllers can be just regular PHP classes</li>
	<li>you may extend from Laravel Controllers if you need that functionality</li>
</ul>

<p>To work with existing or new controllers, add their <strong>root-relative</strong> paths to the settings page.</p>
<p>These are the currently-loaded controller folders:</p>

{!! tb($paths, 'cols:150,500') !!}


<p>The list is fully-configurable, allowing you to rename, reorder, add, remove, enable and disable folders, with changes immediately reflected in the UI.</p>

<p>Note that the following controllers will be <strong>not</strong> be added:</p>
<ul>
	<li>abstract controllers</li>
	<li>methodless controllers</li>
	<li><a href="../tags/hidden">private</a> controllers</li>
</ul>


<p>To configure your controller paths now, visit the <a href="{{$route}}settings#paths">Settings</a> page.</p>