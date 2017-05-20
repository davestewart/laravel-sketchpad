Sketchpad has some basic functionality to set administrative privileges.

The settings are stored in `admin.json` that ships with Sketchpad:

	storage/sketchpad/admin.json

To prevent access to [settings]({{route}}settings) or [setup]({{route}}setup), edit this file then reload Sketchpad:

```json
{
    "settings": true,
    "setup": true
}
```

To set permissions per user, set values on the `SketchpadServiceProvider` in your `AppServiceProvider::boot()`:

```php
SketchpadServiceProvider::set('admin.setup', false);
SketchpadServiceProvider::set('admin.settings', false);
```
