<?php

// ------------------------------------------------------------------------------------------------
// assets

    Route::get  ($config->route . 'assets/{file}',  'AssetsController@asset')->where(['file' => '.*']);


// ------------------------------------------------------------------------------------------------
// setup

    Route::get  ($config->route . 'setup',          'SetupController@index');
    Route::get  ($config->route . 'setup/install',  'SetupController@install');
    Route::post ($config->route . 'setup/install',  'SetupController@submit');
    Route::get  ($config->route . 'setup/test',     'SetupController@test');


// ------------------------------------------------------------------------------------------------
// other

    // data
    Route::get  ($config->route . 'load/{path?}',   'SketchpadController@load')->where('path', '.*');

    // tools
    Route::post ($config->route . 'run/{params?}',  'SketchpadController@run')->where('params', '.*');
    Route::post ($config->route . 'settings',       'SketchpadController@settings');
    Route::post ($config->route . 'create',         'SketchpadController@create');

    // catch all
    Route::get  ($config->route . '{params?}',      'SketchpadController@index')->where('params', '.*');
