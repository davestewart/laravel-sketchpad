<p>The purpose of Sketchpad is to provide a front-end wrappper around your controllers and methods.</p>

<p>It's really quite simple:</p>

<ul>
	<li>the UI mirrors your controllers and methods 1:1</li>
	<li>controllers can be just regular PHP classes</li>
	<li>you may extend from Laravel Controllers if you need that functionality</li>
</ul>

<p>You choose which folders to include by specifying them in the <a href="{{$route}}settings#paths">settings</a> page:</p>

{!! tb($paths, 'cols:150,500') !!}

<p>The list is fully-configurable, allowing you to rename, reorder, add, remove, enable and disable folders, with changes immediately reflected in the UI.</p>

<p>Note that abstract, methodless and <a href="../tags/hidden">private</a> controllers will be ignored.</p>
