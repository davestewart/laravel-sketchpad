<?php

// ------------------------------------------------------------------------------------------------
// assets

    Route::get  ($config->route . 'assets/{file}',      'AssetsController@asset')->where(['file' => '.*']);


// ------------------------------------------------------------------------------------------------
// api

    // data
    Route::post ($config->route . 'api/run/{params?}',  'SketchpadController@run')->where('params', '.*');
    Route::get  ($config->route . 'api/load/{path?}',   'SketchpadController@load')->where('path', '.*');

    // other
    Route::get  ($config->route . 'api/settings',       'SketchpadController@settings');
    Route::post ($config->route . 'api/settings',       'SketchpadController@settings');
    Route::post ($config->route . 'api/create',         'SketchpadController@create');


// ------------------------------------------------------------------------------------------------
// setup

    Route::get  ($config->route . 'setup',              'SetupController@index');
    Route::get  ($config->route . 'setup/install',      'SetupController@install');
    Route::post ($config->route . 'setup/install',      'SetupController@submit');
    Route::get  ($config->route . 'setup/test',         'SetupController@test');


// ------------------------------------------------------------------------------------------------
// catch all

    Route::get  ($config->route . '{params?}',          'SketchpadController@index')->where('params', '.*');
