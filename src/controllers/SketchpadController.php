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

        public function index(Request $request)
        {
            // is installed
            if($this->sketchpad->isInstalled())
            {
                return $this->sketchpad->index();
            }

            // run setup
            $setup = new Setup();
            return $setup->index();
        }

        /**
		 *
         * Run a method
         *
		 * @param   Request     $request
		 * @param   string      $path
		 * @return  \Illuminate\View\View|mixed|string
		 */
		public function run(Request $request, $path = '')
		{
            $request->query->remove('_call');
            return $this->sketchpad->run($path, $request->all());
		}

        /**
         * Returns the JSON for a single controller
         *
         * @param $path
         * @return mixed
         */
        public function load($path = null)
        {
            return response($this->sketchpad->getController($path));
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

}

require_once __DIR__ . '/../utils/utils.php';
