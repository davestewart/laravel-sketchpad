<?php namespace davestewart\sketchpad\controllers;

use Artisan;
use davestewart\sketchpad\services\Installer;
use davestewart\sketchpad\services\Setup;
use davestewart\sketchpad\services\Sketchpad;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * Class SketchpadController
 *
 * @private
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

		/**
		 * Handles form data from the setup controller
		 *
		 * @method  POST
		 * @param   Request     $request
		 * @return  array
		 */
		public function submit(Request $request)
		{
		    $input      = $request->all();
			$state      = $this->setup->saveData($input);
            $result     = [
                'step'      => 'config',
                'success'   => $state,
                'message'   => $state ? 'Config saved OK' : 'Unable to save config',
                'data'      => $input
            ];
            if($state)
            {
                return $this->install();
            }
            return $result;
		}

        /**
         * Attempts to install sketchpad
         *
         * @return array
         */
		public function install()
        {
            Artisan::call('sketchpad:install');
        }


        /**
         * Tests sketchpad was successfully installed
         *
         * @return array
         */
		public function test()
        {
            $installer  = new Installer();
			$state      = $installer->test();
            $result     = [
                'step'      => 'install',
                'success'   => $state,
                'message'   => $state ? 'Installation OK' : 'Installation error',
                'data'      => $installer->logs
            ];
            return $result;
        }

}

