# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)

## [1.2.*] - 2017-05-10

### Added

- Added functionality to load custom views from a subfolder
- Added static `Code` helper class

### Changed

- Made Paths utility functions static
- Moved demo tools controller before docs controller
- `code()` is now a variadic function and calls `Code` class

### Fixed

- Modified test mode so it passes `$run` even if false
- Modified custom views to load directly


## [1.2.0] - 2017-05-09

### Added

- Added top-level Search and Favourites 
- Implemented support for `@field` tags
- New `text()` and `code()` helper functions
- Data injection for `md()` function
- Static `Sketchpad::$form` and `Sketchpad::$data` properties
- Automatic injection of `$route` variable into views
- Implemented controller reordering
- Implemented support for implicit pagination
- Included app controllers folder in paths
- Custom page title functionality
- Added custom Help page
- Added custom head view
- Implemented live-reloading for custom pages
- Added index pages for all help controllers
- Added scroll to section functionality for Settings
- Bootstrap formatting for Markdown tables
- Added favicon.ico
- Added changelog

### Changed

- Huge docs and examples update!
  - Created new Sketchpad docs sections
  - Updated a lot of help to be better-formatted and more consistent
  - Improved example controller and view content
- Moved various settings to single "Site" section
- Moved custom asset declaration to custom head view
- Disabled Settings page now shows a message
- Index pages can be re-run
- Added formatting classes to `pr()` and `vd()`
- Updated FontAwesome to latest version
- Improved submission of form data
- Improved formatting of tables and forms
- Removed global appendOutput setting
- New Param sub-components for different data types
- Simplified copying of user views in setup

### Fixed

- Defended against showing parent paths in Browse filesystem
- Defended against saving settings if not allowed in admin.json
- Fixed edge-case with buggy internal routes
- Fixed bug with method titles showing parameters
- Fixed Markdown not showing code or blockquotes correctly
- Fixed bug where coloured @icon icons not showing
- Fixed scroll to top animation
- Fixed bug with intercepted link currentTarget
- Fixed URL encoding bug with route parameters
- Fixed bug in NumberParam where unset variables don't show 0
- Fixed bug where Output only styles first markdown table
- Fixed bug in internal href links not working for links with hashes
- Fixed bug with validate-path directive causing settings to be reloaded on focus
- Fixed routing bug in Help
- Removed absolute path references from RouteReference classes
- Updated demo link in readme

### Upgrade notes

The new settings configuration has breaking changes:

- Back up your existing settings file `storage/sketchpad/settings.json`
- Reinstall Sketchpad via `http://<yoursite>/sketchpad/setup`
- Locate updated `settings.json` and update relevant nodes (`paths`, `livereload`, `ui`) from your backed-up file


## [1.1.0] - 2017-04-21

### Added

- Test mode functionality 
- Clientside script to auto-resize iframe
- User can now edit and show custom home page
- Basic admin permissions to prevent showing of Setup and Settings pages

### Changed

- Moved routes to separate file
- Example Browse Filesystem tool now only browses from site root downwards
- Refactored `publish/` folder to be `package/`
- Moved `resources/views` to `package/views`
- Moved `$config` references for Example controller / view to method

### Fixed

- Controller labels not being humanized
- Bug with new controllers not being added
- Layout fix when moving between controller indexes and methods
- Prevented spellcheck on settings fields
- Setup edge-case bugs for custom setups which required two install attempts
- URLs now load properly into iframes
- Better iframe error handling
- Page titles now show for non-console routes
- `tb()` and `ls()` now display booleans correctly
- `vd()` now displays preformatted text, also added better formatting


## [1.0.0] - 2017-04-12

### Added

- Scroll to top when changing methods
- Pressing enter now submits method changes
- Added icon parameter to `alert()` helper
- Parameter values now stay the same when reloading
- Shorthand `sketchpad:link` format for in-page links
- Shorthand `$assets/` URL reference for user assets URLs

### Changed

- Search page starts with no results; type to filter
- Nicer console transitions
- Various UI updates
- Improved resilience of watcher / store / state updates for controllers / methods
- Major refactor of router > console
- Better error handling / feedback on session timeouts
- Simplified return of different data formats by using container div rather than headers
- Improved VueJS data injection functionality; now uses `$data` variable
- Removed BrowserSync watching in favour of LiveReload

### Fixed

- Minor bugs in table rendering
- Bug with JSON data return < Laravel 5.2
- Bug with wrong active path showing for paths with similar names
- Various inconsistencies with controller reloading / missing routes
- Various installer bugs / optmiisations

## Pre-releases

- [1.0.0-beta] - 2017-03-24
- [0.3.0] - 2016-05-12
- [0.2.0] - 2016-04-30
- [0.1.0] - 2016-04-27
- 0.0.0 - 2016-04-19

[1.2.*]: https://github.com/davestewart/laravel-sketchpad/compare/v1.2.0...develop
[1.2.0]: https://github.com/davestewart/laravel-sketchpad/compare/v1.1.0...v1.2.0
[1.1.0]: https://github.com/davestewart/laravel-sketchpad/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/davestewart/laravel-sketchpad/compare/v1.0.0-beta...v1.0.0
[1.0.0-beta]: https://github.com/davestewart/laravel-sketchpad/compare/v0.3...v1.0.0-beta
[0.3.0]: https://github.com/davestewart/laravel-sketchpad/compare/v0.2...v0.3
[0.2.0]: https://github.com/davestewart/laravel-sketchpad/compare/v0.1...v0.2
[0.1.0]: https://github.com/davestewart/laravel-sketchpad/compare/3b2c4c0efdffaaead8a490132c49bdea3a3aef1c...v0.1
