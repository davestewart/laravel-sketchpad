<?php

// ------------------------------------------------------------------------------------------------
// api

    // api
    Route::post ($config->route . 'api/run/{params?}',  'ApiController@run')->where('params', '.*');
    Route::get  ($config->route . 'api/load/{path?}',   'ApiController@load')->where('path', '.*');

    // settings
    Route::get  ($config->route . 'api/settings',       'ApiController@settings');
    Route::post ($config->route . 'api/settings',       'ApiController@settings');

    // tools
    Route::get ($config->route . 'api/path',           'ApiController@path');
    Route::post ($config->route . 'api/create',         'ApiController@create');


// ------------------------------------------------------------------------------------------------
// setup

    Route::get  ($config->route . 'setup',              'SetupController@index');
    Route::get  ($config->route . 'setup/install',      'SetupController@install');
    Route::post ($config->route . 'setup/install',      'SetupController@submit');
    Route::get  ($config->route . 'setup/test',         'SetupController@test');


// ------------------------------------------------------------------------------------------------
// sketchpad

    Route::get  ($config->route . 'assets/{file}',      'SketchpadController@asset')->where(['file' => '.*']);
    Route::get  ($config->route . 'user/{file}',        'SketchpadController@user')->where(['file' => '.*']);
    Route::get  ($config->route . '{params?}',          'SketchpadController@index')->where('params', '.*');
