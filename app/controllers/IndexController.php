<?php

class IndexController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Index page controller
	| Author: lonew01f
	| First commit: 5/3/2015 12:38 AM
	| Describtion: Index page controller handlers all the aspect related to
	| index or landing page such as Signup and Login
	|--------------------------------------------------------------------------
	|
	*/

	public function index()
	{
		return View::make('pages.index');
	}

	public function signup(){

		$input  = Request::except('repassword');

		$input['password'] = md5($input['password']);

		//appending additional sign up information
		$add_d  = array(
			"code"   => md5($input['email']),
			"ip"     => $_SERVER['REMOTE_ADDR'],
			"active" => 0 //active set to 0 
		);

		$input  = array_merge($input, $add_d);

		$user   = new user;
		return $user -> add_user($input);
 	}

 	//test username 
 	public function check_username(){
		$user   = new user;
		return $user -> validate_username(Input::get('username'));
 	}

 	//get settings for index page

 	public function load_settings_index(){
 		$settings = new settings;
 		return json_encode($settings -> get_settings());
 	}
}
