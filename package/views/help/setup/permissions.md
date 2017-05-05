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

See the [wiki](https://github.com/davestewart/laravel-sketchpad/wiki/Admin) for more information, including setting permissions on a per-user basis.
