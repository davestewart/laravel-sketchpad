<?php namespace davestewart\sketchpad\controllers;

use davestewart\sketchpad\objects\settings\Paths;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Class SketchpadController
 */
class AssetsController extends Controller
{
	
    public function asset($file)
    {
        // absolute path
        $paths  = new Paths();
        $path   = $paths->publish("assets/$file");

        // mimetype
        $info   = pathinfo($file);
        $ext    = $info['extension'];
        $mimes  =
        [
            'js'    => 'application/javascript',
            'css'   => 'text/css',
            'woff'  => 'application/font-woff',
            'ttf'   => 'application/x-font-ttf',
        ];
        $mime = isset($mimes[$ext])
            ? $mimes[$ext]
            : 'text/html';

        // serve file
        $response = new BinaryFileResponse($path);
        $response->headers->set('Content-type', $mime);
        $response->headers->set('Content-length', filesize($path));
        return $response;
    }



}

