<?php namespace davestewart\sketchpad\controllers;

use davestewart\sketchpad\services\Setup;
use davestewart\sketchpad\services\Sketchpad;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;

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

        public function action($action)
        {
            $data = Input::get('data');
            switch($action)
            {
                case 'install':
                    $this->install();
                    break;

                case 'view':
                    return $this->sketchpad->getPage($data);
                    break;

                case 'call':
                    $this->run();
                    break;

                case 'create':
                    $this->create();
                    break;

                case 'settings':
                    $this->settings();
                    break;

            }
        }

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
			// return a view
			if(file_exists(config_path('sketchpad.php')))
			{
				if(Input::get('_call') || $request->isMethod('POST'))
				{
				    $request->query->remove('_call');
					return $this->sketchpad->call($path, $request->all());
				}
				return $this->sketchpad->index();
			}

			//dump($path);

			// no config
			$setup = new Setup();
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
			if($type == 'page')
			{
				return $this->sketchpad->getPage($data);
			}
			
			// loads controller data
			if($type == 'load')
			{

			}
		}

        public function controller($data)
        {
            return response()->json($this->sketchpad->getController($data));
		}

        public function view($data)
        {
            return $this->sketchpad->getPage($data);
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
