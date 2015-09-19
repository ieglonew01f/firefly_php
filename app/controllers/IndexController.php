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

 	public function short_signup(){

 		$data = array(
	 		"fullname" => ucwords(Input::get('fullname')),
	 		"email"    => Input::get('email'),
	 		"password" => md5(Input::get('password')),
	 		"time"     => time(),
	 		"code"     => md5(Input::get('email')),
	 		"active"   => 0,
	 		"ip"       => $_SERVER['REMOTE_ADDR'],
	 		"username" => explode('@', Input::get('email'))[0].time()
 		);

 		$user = new user;
 		return $user -> add_user($data);
 	}

 	/* NEW */
 	//signup by email address
 	public function signup_with_email(){
 		$email = Input::get('email');
 		$user  = new user;
 		return json_encode($user -> new_email_signup(['email' => $email]));
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
