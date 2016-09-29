<?php namespace davestewart\sketchpad\controllers;

use davestewart\sketchpad\objects\settings\Paths;
use davestewart\sketchpad\services\Installer;
use davestewart\sketchpad\services\Setup;
use davestewart\sketchpad\services\Sketchpad;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Response;

/**
 * Class SketchpadController
 */
class SetupController extends Controller
{
	
	// ------------------------------------------------------------------------------------------------
	// properties
	
		/**
		 * Sketchpad service
		 *
		 * @var Sketchpad
		 */
		protected $sketchpad;

        /**
         * @var Setup
         */
        protected $setup;


    // ------------------------------------------------------------------------------------------------
	// instantiation

		/**
		 * SketchpadController constructor.
		 *
		 * @param Sketchpad $sketchpad
		 */
		public function __construct(Sketchpad $sketchpad)
		{
			$this->sketchpad    = $sketchpad;
			$this->setup        = new Setup();
		}


	// ------------------------------------------------------------------------------------------------
	// setup methods

		public function index()
		{
			return $this->setup->view();
		}

        public function asset($file)
        {
            // absolute path
            $paths  = new Paths();
            $path   = $paths->publish("assets/$file");

            // mimetype
            $info  = pathinfo($file);
            $mimes =
            [
                'js'    => 'application/javascript',
                'css'   => 'text/css',
                'woff'  => 'application/font-woff',
                'ttf'   => 'application/x-font-ttf',
            ];
            $mime = $mimes[$info['extension']];

            // serve file
            $response = new BinaryFileResponse($path);
            $response->headers->set('Content-type', $mime);
            $response->headers->set('Content-length', filesize($path));
            return $response;
		}

		/**
		 * Handles form data from the setup controller
		 *
		 * @method  POST
		 * @param   Request     $request
		 * @return  JsonResponse
		 */
		public function submit(Request $request)
		{
		    $input      = $request->all();
			$state      = $this->setup->saveData($input);
            $message    = $state ? 'Config saved OK' : 'Unable to save config';
            return $this->response($message, $input, $state);
		}

        /**
         * Tests sketchpad was successfully installed
         *
         * @return JsonResponse
         */
		public function test()
        {
            $installer  = new Installer();
			$state      = $installer->test();
            $message    = $state ? 'Installation OK' : 'Installation error';
            return $this->response($message, $installer->logs, $state);
        }


    // ------------------------------------------------------------------------------------------------
    // utilities

        /**
         * Utility JSON response method
         *
         * @param $message
         * @param null $data
         * @param int|bool $code
         * @return JsonResponse
         */
        protected function response($message, $data = null, $code = 200)
        {
            if(is_bool($code))
            {
                $code = $code ? 200 : 400;
            }
            return Response::json([
                'message' => $message,
                'data' => $data
            ], $code);
        }


}

