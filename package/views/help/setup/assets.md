Sketchpad provides a custom folder to load user assets:

```text
{{assets}}
```

Two starter files were copied here during installation:

	{{assets}}scripts.js
	{{assets}}styles.css

These are loaded via your custom [view](views) file `head.blade.php`:

```html
<script src="{{ $assets }}scripts.js"></script>
<link   href="{{ $assets }}styles.css" rel="stylesheet">
```
Both the assets and head file are yours to do with as you please; for example modifying styles, loading 3rd party libraries, adding meta tags, etc. 

You might also want to check out:

- the settings [paths]({{route}}settings#paths) section to configure your asset path
- the [formatting](../output/formatting) example to view Sketchpad's default styles
- the [css tag](../tags/css) which can be used in conjunction with user assets to style the navigation element
