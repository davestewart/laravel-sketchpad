<?php namespace davestewart\sketchpad\controllers;

use davestewart\sketchpad\middleware\RequestId;
use davestewart\sketchpad\services\Setup;
use davestewart\sketchpad\services\Sketchpad;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Response;

/**
 * Class SketchpadController
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
	// public methods

		public function command($type, $data = null)
		{
			if($type == 'show')
			{
				return $this->sketchpad->getPage($data);
			}
			if($type == 'load')
			{
				return $this->sketchpad->getController($data);
			}
		}
	
		public function call($path = '')
		{
			// instantiate setup
			$setup = new Setup();
			
			// return a view
			return $setup->check()
				? $this->sketchpad->call($path)
				: $setup->view();
		}

		public function setup(Request $request)
		{
			// instantiate setup
			$setup  = new Setup();
			$input  = $request->all();
			$result = $setup->makeConfig($input);
				
			// run the next stage of setup
			return redirect('/' .  $input['route']);
		}
	
		public function create(Request $request)
		{
			// get input
			$input      = $request->all();
			
			// extract variables
			$name       = $input['name'];
			$path       = $input['path'];
			$members    = $input['members'];
			$options    = $input['options'];
	
			// create
		}
	
	// ------------------------------------------------------------------------------------------------
	// protected methods


	

}

