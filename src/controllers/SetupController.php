<?php namespace davestewart\sketchpad\controllers;

use davestewart\sketchpad\objects\scanners\Finder;
use davestewart\sketchpad\objects\scanners\Scanner;
use davestewart\sketchpad\services\Setup;
use davestewart\sketchpad\services\Sketchpad;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
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
			$this->setup->check();


			/*
			// debug
			$finder     = new Finder();
			$finder->start();

			pr($finder);
			$scanner = new Scanner($finder->path . 'Sketchpad/');
			$scanner->start();

			*/

			//pd($this->setup);

			return $this->setup->view();
		}

		/**
		 * Handles form data from the setup controller
		 *
		 * @method  POST
		 * @param   Request     $request
		 * @return  \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
		 */
		public function setup(Request $request)
		{
			// instantiate setup
			$setup  = new Setup();
			$input  = $request->all();
			$result = $setup->makeConfig($input);

			// run the next stage of setup
			return redirect('/' .  $input['route']);
		}


	

}

