### Overview

Sketchpad is designed to help you produce good-looking and meaningful output from the get-go.

It ships with a variety of helper functions and front end tricks to make it as *easy as possible* to put content on-screen.


### Helpers

The following helpers are loaded by default; you can use them in any methods loaded by Sketchpad:

- `p()` - echo paragraphs
- `alert()` - echo Bootstrap alert elements
- `pr()` - print_r data
- `vd()` - var_dump data
- `dump()` - dump (but don't die)
- `json()` - output objects as JSON
- `ls()` - output objects as list of name:value pairs
- `tb()` - output an array of objects in table format
- `md()` - transform and output Markdown
- `vue()` - load and inject data into a Vue file


### Source

You can view the source for the HTML examples at:

	vendor/davestewart/sketchpad/src/help

The source for the actual helpers and views is available at:

	vendor/davestewart/sketchpad/src/utils/Html.php
	vendor/davestewart/sketchpad/package/views/html
	