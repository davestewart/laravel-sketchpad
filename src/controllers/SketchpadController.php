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
	// main entry point

		/**
		 * Main entry point for any non :command URIs
		 *
		 * Will trigger index or a controller/method call
		 *
		 * @param   Request     $request
		 * @param   string      $path
		 * @return  \Illuminate\View\View|mixed|string
		 */
		public function call(Request $request, $path = '')
		{
			// instantiate setup
			$setup = new Setup();


			// return a view
			if($setup->check())
			{
				if(Input::get('call') || $request->isMethod('POST'))
				{
					return $this->sketchpad->call($path);
				}
				return $this->sketchpad->index();
			}
			//die('setup' . $setup->view());
			return $setup->index();
		}


	// ------------------------------------------------------------------------------------------------
	// main app methods

		/**
		 * Handles commands from the main UI
		 *
		 * @param   string      $type
		 * @param   null        $data
		 * @return  \Illuminate\View\View|\Symfony\Component\HttpFoundation\Response
		 */
		public function command($type, $data = null)
		{
			// shows an html page
			if($type == 'show')
			{
				return $this->sketchpad->getPage($data);
			}
			
			// loads controller data
			if($type == 'load')
			{
				return response()->json($this->sketchpad->getController($data));
			}
		}

		/**
		 * Creates a new controller
		 *
		 * @method  POST
		 * @param   Request     $request
		 */
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
	

}

require_once __DIR__ . '/../utils/utils.php';
