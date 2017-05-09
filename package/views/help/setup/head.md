Sketchpad allows you to inject custom head content via a custom [view](views) file:

```text
{{views}}head.blade.php
```


The initial HTML is as follows:

```html
<!-- user meta -->
<link rel="shortcut icon" href="{{ $assets }}favicon.ico" type="image/x-icon">
<link rel="icon" href="{{ $assets }}favicon.ico" type="image/x-icon">

<!-- user assets -->
<script src="{{ $assets }}scripts.js"></script>
<link   href="{{ $assets }}styles.css" rel="stylesheet">
```
Feel free to add or remove user assets, 3rd party libraries, add tracking, meta tags, etc. 

Note that the variables `assets` and `route` are available to use.