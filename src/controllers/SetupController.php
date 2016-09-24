<?php namespace davestewart\sketchpad\controllers;

use davestewart\sketchpad\services\Installer;
use davestewart\sketchpad\services\Setup;
use davestewart\sketchpad\services\Sketchpad;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
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
         * @var \davestewart\sketchpad\install\\davestewart\sketchpad\services\Setup
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

		/**
		 * Handles form data from the setup controller
		 *
		 * @method  POST
		 * @param   Request     $request
		 * @return  \Illuminate\Http\JsonResponse
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
         * @return \Illuminate\Http\JsonResponse
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
         * @return \Illuminate\Http\JsonResponse
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

