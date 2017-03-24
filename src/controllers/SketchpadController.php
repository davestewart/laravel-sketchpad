<?php namespace davestewart\sketchpad\controllers;

use davestewart\sketchpad\config\SketchpadConfig;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

use davestewart\sketchpad\config\Paths;
use davestewart\sketchpad\services\Setup;
use davestewart\sketchpad\services\Sketchpad;

/**
 * Class SketchpadController
 *
 * @private
 */
class SketchpadController extends Controller
{

	// ------------------------------------------------------------------------------------------------
	// properties

		/**
		 * Sketchpad service
		 *
		 * @var Sketchpad
		 */
		protected $sketchpad;


	// ------------------------------------------------------------------------------------------------
	// instantiation

		/**
		 * SketchpadController constructor.
		 *
		 * @param Sketchpad $sketchpad
		 */
		public function __construct(Sketchpad $sketchpad)
		{
			$this->sketchpad = $sketchpad;
		}


	// ------------------------------------------------------------------------------------------------
	// endpoints

        public function index(Request $request)
        {
            // not installed
            if(!$this->sketchpad->isInstalled())
            {
		        // run setup
		        $setup = new Setup();
		        return $setup->index();
            }

			// set up the router and rescan to get all data
			$config     = $this->sketchpad->init(true)->config;

			// build resources
			$resources  = $config->settings->get('assets') ?: [];
			$resources  = array_map(function ($file) {
				$file = trim($file);
				return preg_match('/.js$/', $file) === 1
					? '<script src="' . $file . '"></script>'
					: '<link  href="' . $file . '" rel="stylesheet">';
			}, $resources);

			// variables
			$data =
			[
				'route'         => $config->route,
				'assets'        => $config->route . 'assets/',
				'settings'      => $config->settings->data,
				'resources'     => implode("\n\t", $resources),
				'data'          =>
				[
					'controllers'   => $this->sketchpad->getController(),
				]
			];

			// view
			return view('sketchpad::index', $data);
	    }

	    public function asset(Paths $paths, $file)
	    {
	    	return $this->getAsset($paths->publish("assets/$file"));
	    }

	    public function user(SketchpadConfig $config, $file)
		{
			return $this->getAsset(base_path(trim($config->assets, '/') . '/' . $file));
	    }


	// ------------------------------------------------------------------------------------------------
	// helpers

		protected function getAsset($path)
		{
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

require_once __DIR__ . '/../utils/utils.php';
