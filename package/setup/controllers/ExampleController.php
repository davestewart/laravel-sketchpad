<?php namespace controllers;

use davestewart\sketchpad\config\SketchpadConfig;

/**
 * An example controller, just to get you started
 *
 * @label start here
 */
class ExampleController
{

    public function index (SketchpadConfig $config)
    {
        return view('sketchpad::example/index', compact('config'));
    }

    /**
     * An example method, just to get you started...
     */
    public function welcome ($name = 'World')
    {
        echo "Hello $name";
    }

}
