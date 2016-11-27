<?php

// ------------------------------------------------------------------------------------------------
// assets

    // setup assets
    Route::get  ($config->route . ':assets/{file}', 'AssetsController@asset')->where(['file' => '.*']);


// ------------------------------------------------------------------------------------------------
// setup

    // setup view
    Route::get  ($config->route . ':setup', 'SetupController@index');

    // post setup data
    Route::post ($config->route . ':setup', 'SetupController@submit');

    // test setup has worked correctly
    Route::get  ($config->route . ':setup/install', 'SetupController@install');


// ------------------------------------------------------------------------------------------------
// other

    // load content
    Route::get  ($config->route . ':load/{data?}', 'SketchpadController@controller')->where('data', '.*');
    Route::get  ($config->route . ':page/{data?}', 'SketchpadController@view')->where('data', '.*');

    // create a new sketchpad controller
    Route::post ($config->route . ':create', 'SketchpadController@create');

    // catch-all command
    //Route::get  ($config->route . ':{command}/{data?}', 'SketchpadController@command')->where('data', '.*');

    // catch-call route
    Route::match(['GET', 'POST'], $config->route . '{params?}', 'SketchpadController@call')->where('params', '.*');