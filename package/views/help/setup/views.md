Sketchpad provides a custom folder to keep your Sketchpad views separate from your application views:

```text
{{views}}
```
You can place the following Sketchpad-supported view types here:

- [Blade](../output/blade)
- [Markdown](../output/markdown)
- [Vue](../output/vue)

Load views from this folder using the [sketchpad::](../methods/variables) namespace:

```
echo view('sketchpad::somefile')
``` 

The views folder is also where your custom [pages](customisation) and [head](assets) view are loaded from.