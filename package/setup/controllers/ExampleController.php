<?php namespace user;

use Illuminate\Routing\Controller as BaseController;
use davestewart\sketchpad\config\SketchpadConfig;

/**
 * An example controller, just to get you started
 *
 * @label start here
 */
class ExampleController extends BaseController
{

    public function index (SketchpadConfig $config)
    {
    	$data =
	    [
	        'controllers' => $config->controllers['sketchpad'],
	        'views'       => $config->views,
	    ];
        return view('sketchpad::example.index', $data);
    }

    /**
     * An example method, just to get you started
     */
    public function welcome ($name = 'World')
    {
        return view('sketchpad::example.welcome', compact('name'));
    }

    // your methods here...

}
