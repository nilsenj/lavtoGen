<?php namespace Modules\Newby\Http\Controllers;

use Core\Modular\Routing\Controller;

class NewbyController extends Controller {
	
	public function index()
	{
		return view('newby::index');
	}
	
}