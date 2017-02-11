<?php

// ------------------------------------------------------------------------------------------------
// assets

    Route::get  ($config->route . 'assets/{file}', 'AssetsController@asset')->where(['file' => '.*']);


// ------------------------------------------------------------------------------------------------
// setup

    Route::get  ($config->route . 'setup', 'SetupController@index');
    Route::post ($config->route . 'setup', 'SetupController@submit');
    Route::get  ($config->route . 'setup/install', 'SetupController@install');


// ------------------------------------------------------------------------------------------------
// other

    // data
    Route::post ($config->route . 'run/{params?}', 'SketchpadController@run')->where('params', '.*');
    Route::get  ($config->route . 'load/{path?}', 'SketchpadController@load')->where('path', '.*');

    // tools
    Route::get  ($config->route . 'view/{name?}', 'SketchpadController@view')->where('name', '.*');
    Route::post ($config->route . 'create', 'SketchpadController@create');

    // index
    Route::get  ($config->route, 'SketchpadController@index');

    // catch all
    Route::get  ($config->route . '{params?}', 'SketchpadController@index')->where('params', '.*');
