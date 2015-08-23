<?php

class SettingsController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Settings page controller
	| Author: lonew01f
	| First commit: 20/08/2015 05:17 PM
	| Describtion: Settings page controller handlers all the aspect related to
	| user settings
	|--------------------------------------------------------------------------
	|
	*/

	public function index()
	{
		$profile      = new profiles;
		$profile_data = $profile -> load_profile_data(Session::get('username'));

		$data = array(
			"profile_data" => $profile_data
		);

		return View::make('pages.settings', $data);
	}

	//save settings
	public function save_settings(){
		$profile_data = array(
			"about"     => Input::get('about'),
			"location"  => Input::get('location'),
			"home"      => Input::get('home'),
			"schooling" => Input::get('schooling'),
			"college"   => Input::get('college'),
			"gender"    => Input::get('gender')
		);

		$user_data = array(
			"fullname"   => Input::get('fullname'),
			"username"   => Input::get('username'),
			"updated_at" => time()
		);

		$settings = new settings;
		$settings = $settings -> update_settings($profile_data, $user_data);
	}

}
