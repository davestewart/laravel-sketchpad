To live-code and live-reload controllers, views and assets, install and run Sketchpad Reload.

When developing, this allows you to work **almost exclusively** in your IDE, with Sketchpad responding and updating as you save files, rename elements, change, add or remove code:


| Element | Event | Action
| ------- | ------ | --------
| Method | Save | Console reloads
| Parameters | Rename, change, add or delete | Console parameters update
| Controller or method | Rename, reorder, add or delete | Navigation updates
| Method | Runtime error | Console displays Laravel stack trace
| Controller | Parse error | Navigation and Console display error indicator / message
| Controller or method | Error correction | Navigation and Console update / reload automatically
| Settings paths | Rename, change, add or delete | LiveReload updates and re-watches paths
| User assets | Save | Styles or Script reloads
| Custom home page | Save | Updates custom home page


If using Sketchpad for **development** you should make installing Sketchpad Reload a priority:

- If you've not yet using the package, visit the <a href="https://github.com/davestewart/laravel-sketchpad-reload/wiki" target="_blank">wiki</a> for installation instructions
- If you need to configure the package, visit the [settings]({{route}}settings#livereload) page.
 
<script>$(window.LiveReload ? '.alert-danger' : '.alert-success').remove()</script>
