<?php namespace davestewart\sketchpad\controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;

use davestewart\sketchpad\config\SketchpadSettings;
use davestewart\sketchpad\config\SketchpadConfig;
use davestewart\sketchpad\services\Sketchpad;

/**
 * Class SketchpadController
 */
class ApiController extends Controller
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

        /**
         * Run a controller method
         *
		 * @param   Request     $request
		 * @param   string      $path
		 * @return  \Illuminate\View\View|mixed|string
		 */
		public function run(Request $request, $path = '')
		{
            $request->query->remove('_call');
            return $this->sketchpad->run($path, $request->get('data', []));
		}

        /**
         * Returns the JSON for a single controller
         *
         * @param $path
         * @return mixed
         */
        public function load($path = null)
        {
            return response()->json($this->sketchpad->getController($path));
		}

		/**
		 * Loads or saves settings data
		 *
		 * @method  POST
		 * @method  GET
		 * @param   Request $request
		 * @param   SketchpadConfig $config
		 * @return  SketchpadSettings
		 */
		public function settings(Request $request, SketchpadConfig $config)
		{
			if($request->isMethod('post'))
			{
				$data = json_decode($request->get('settings'));
				$config->settings->save($data);
			}
			return $config->settings;
		}

}

require_once __DIR__ . '/../utils/utils.php';
