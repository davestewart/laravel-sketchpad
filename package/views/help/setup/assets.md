Sketchpad provides a custom folder to load user assets:

```text
{{assets}}
```

Three starter files were copied here during installation:

	{{assets}}scripts.js
	{{assets}}styles.css
	{{assets}}favicon.ico

These are loaded via your custom [view](views) file `head.blade.php`:

```html
<!-- user meta -->
<link rel="shortcut icon" href="{{ $assets }}favicon.ico" type="image/x-icon">
<link rel="icon" href="{{ $assets }}favicon.ico" type="image/x-icon">

<!-- user assets -->
<script src="{{ $assets }}scripts.js"></script>
<link   href="{{ $assets }}styles.css" rel="stylesheet">
```
Both the assets and head file are yours to do with as you please; for example modifying styles, loading 3rd party libraries, adding tracking, meta tags, etc. 

You might also want to check out:

- the settings [paths]({{route}}settings#paths) section to configure your asset path
- the [formatting](../output/formatting) example to view Sketchpad's default styles
- the [css tag](../tags/css) which can be used in conjunction with user assets to style the navigation element
