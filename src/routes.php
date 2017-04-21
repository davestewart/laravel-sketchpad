<?php

// api - controllers
Route::post ($config->route . 'api/run/{params?}',  'ApiController@run')->where('params', '.*');
Route::get  ($config->route . 'api/load/{path?}',   'ApiController@load')->where('path', '.*');

// api - settings
Route::get  ($config->route . 'api/settings',       'ApiController@settings');
Route::post ($config->route . 'api/settings',       'ApiController@settings');

// api - tools
Route::get ($config->route .  'api/path',           'ApiController@path');

// setup
Route::get  ($config->route . 'setup',              'SetupController@index');
Route::post ($config->route . 'setup/install',      'SetupController@submit');
Route::get  ($config->route . 'setup/install',      'SetupController@install');
Route::get  ($config->route . 'setup/test',         'SetupController@test');

// assets
Route::get  ($config->route . 'assets/user/{file}', 'SketchpadController@userAsset')->where(['file' => '.*']);
Route::get  ($config->route . 'assets/{file}',      'SketchpadController@asset')->where(['file' => '.*']);

// sketchpad
Route::get  ($config->route . '{params?}',          'SketchpadController@index')->where('params', '.*');
