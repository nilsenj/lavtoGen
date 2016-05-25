<?php namespace Modules\Dummy\Http\Controllers;

use Core\Modular\Routing\Controller;

class DummyController extends Controller {
	
	public function index()
	{
		return view('dummy::index');
	}
	
}