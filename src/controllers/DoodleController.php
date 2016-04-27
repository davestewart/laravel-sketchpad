<?php namespace davestewart\doodle\controllers;

use davestewart\doodle\services\DoodleService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Response;

/**
 * Class TestController
 * @package app\Http\Controllers\test
 */
class DoodleController extends Controller
{
	
	/**
	 * @var DoodleService
	 */
	protected $service;

	/**
	 * DoodleController constructor.
	 *
	 * @param DoodleService $service
	 */
	public function __construct(DoodleService $service)
	{
		$this->service = $service;
	}

	public function command($type, $data = null)
	{
		$data = $this->service->getVariables();
		return view('doodle::pages.' . $type, $data);
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
		return view('doodle::content.index', $this->service->getData(''));
	}

	public function view($path)
	{
		return view('doodle::content.index', $this->service->getData($path));
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

