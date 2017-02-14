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

        // 404
        if(!file_exists($path))
        {
            header("HTTP/1.0 404 Not Found");
            exit;
        }

        // mimetype
        $info   = pathinfo($path);
        $ext    = $info['extension'];
        $mimes  =
        [
            'js'    => 'application/javascript',
            'css'   => 'text/css',
            'gif'   => 'image/gif',
            'png'   => 'image/png',
            'woff'  => 'application/font-woff',
            'ttf'   => 'application/x-font-ttf',
        ];
        $mime = isset($mimes[$ext])
            ? $mimes[$ext]
            : 'application/octet-stream'; //'text/html';

        // serve file
        $response = new BinaryFileResponse($path);
        $response->mustRevalidate();
        $response->headers->set('Content-type', $mime);
        $response->headers->set('Content-length', filesize($path));
        return $response;
    }



}

