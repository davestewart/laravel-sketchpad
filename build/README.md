# Laravel Sketchpad - Build Process

## Overview

The build process currently uses Laravel Elixir, so the calls mirror the default Elixir command line.


## Build

Targets:

```
gulp                        // build app
gulp --setup                // build setup
```

Types:

```
gulp                        // build normally
gulp watch                  // build, watch, and hot reload
gulp --production           // production build
```

Note that you can combine tasks and flags:

```
gulp watch --setup          // development mode for setup
gulp --setup --production   // final build for setup
```


## Notes

### Watching

In watch mode, the console will show the following to incicate hot-reloading is running:

```
[HMR] Attempting websocket connection to http://localhost:3123
```

Note that hot reloading causes the page to poll for a running hot reload server. If one is not found (i.e. gulp is not currently watching) the console will output errors every second or so:

```
GET http://localhost:3123/socket.io/?EIO=3&transport=polling&t=LTExC5Y
net::ERR_CONNECTION_REFUSED
polling-xhr.js:250
```

The solution is either to make sure the hot reload server is running (but running this gulp task!) or compile the production build so the polling is not included in the compiled files.
