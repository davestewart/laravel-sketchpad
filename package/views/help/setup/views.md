Sketchpad provides a custom folder to keep your Sketchpad views separate from your application views:

```text
{{views}}
```
You can place the following Sketchpad-supported view types here:

- [Blade](../output/blade)
- [Markdown](../output/markdown)
- [Vue](../output/vue)

To reference this folder in your view paths, you can use the [sketchpad::](../methods/variables) package shortcut:

```
echo view('sketchpad::somefile')
``` 
