### Overview

Sketchpad ships with a variety of helper functions to make it as easy as possible to put content on-screen.

Text helpers make it easy to output well-formatted HTML without building views:

| Helper    | Result
| --------- | -------
| `p()`     | Output paragraphs
| `text()`  | Output preformatted text
| `code()`  | Output syntax-highlighted code
| `alert()` | Output Bootstrap alert elements (useful for reporting)

Data helpers make it easy to inspect arrays, objects and classes without writing any code:

| Helper    | Result
| --------- | -------
| `pr()`    | `print_r` data
| `vd()`    | `var_dump` data
| `dump()`  | `dd()` but don't die
| `json()`  | Interactively inspect objects in JSON format
| `ls()`    | Output objects or classes as list of name / value pairs
| `tb()`    | Output an array of objects in table format


Note that helper functions `echo` immediately. If you need the raw HTML, call the source `Html` method:

```php
$html = Html::tb($data);
```

### Source

You can view the source for this section at:

	vendor/davestewart/sketchpad/package/views/help/helpers
	vendor/davestewart/sketchpad/src/help/docs/HelpersController.php

You can view the source for the helper functions at:

```text
vendor/davestewart/sketchpad/package/views/html/*
```

<style> table th:first-child { min-width:100px; }</style>
