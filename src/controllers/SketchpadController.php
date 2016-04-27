<?php namespace davestewart\sketchpad\controllers;

use davestewart\sketchpad\services\SketchpadService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Response;

/**
 * Class TestController
 * @package app\Http\Controllers\test
 */
class SketchpadController extends Controller
{
	
	/**
	 * @var SketchpadService
	 */
	protected $service;

	/**
	 * SketchpadController constructor.
	 *
	 * @param SketchpadService $service
	 */
	public function __construct(SketchpadService $service)
	{
		$this->service = $service;
	}

	public function command($type, $data = null)
	{
		$data = $this->service->getVariables();
		return view('sketchpad::pages.' . $type, $data);
	}

	public function call($path = '')
	{
		return $this->service->call($path);
	}

	public function create(Request $request)
	{
		$input      = $request->all();
		$name       = $input['name'];
		$path       = $input['path'];
		$members    = $input['members'];
		$options    = $input['options'];
		$this->service->create($path, $name, $members, $options);
	}




	public function index()
	{
		return view('sketchpad::content.index', $this->service->getData(''));
	}

	public function view($path)
	{
		return view('sketchpad::content.index', $this->service->getData($path));
	}

	public function get($path = '')
	{
		return Response::json($this->service->getFolder($path));
	}

	public function all($path = '')
	{
		return Response::json($this->service->getFolder($path, true));
	}



}

