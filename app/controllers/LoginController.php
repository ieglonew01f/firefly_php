<?php

class LoginController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Login page controller
	| Author: lonew01f
	| First commit: 5/8/2015 09:37 PM
	| Describtion: Login page controller handlers all the aspect related to
	| login
	|--------------------------------------------------------------------------
	|
	*/

	public function index()
	{
		return View::make('pages.login');
	}

	public function login(){

		$input  = Request::all();

		$user   = new user;
		return $user -> login_user($input);
 	}
}
