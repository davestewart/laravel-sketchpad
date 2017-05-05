Sketchpad allows you to add custom assets to the app by way of:

- Custom user asset files
- Loadable asset URLs

During setup, two starter files were copied to your installation's `assets/` folder.

	{{assets}}scripts.js
	{{assets}}styles.css

These files are set to load automatically, along with any other URLs you add (for example <a href="https://momentjs.com/" target="_blank">Moment.js</a>):

```text
https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.0/moment.js
$assets/scripts.js
$assets/styles.css
```

Note the special "user assets" route `$assets/` which loads the static file contents directly – they do not need to be in your app's `/public/` folder!

You might want to check out:

- the settings [paths]({{route}}settings#paths) section configure asset paths
- the settings [site]({{route}}settings#site) section configure loaded assets
- the [formatting](../output/formatting) example to see some of the base styles
- the [css tag](../tags/css) which can be used in conjunction with user assets to style the navigation element



