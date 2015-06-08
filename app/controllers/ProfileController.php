<?php

class ProfileController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Profile Controller
	| Author: lonew01f
	| First commit: 6/3/2015 11:50 PM
	| Describtion: Profile page controller handlers all the aspect related to
	| the user profile
	|--------------------------------------------------------------------------
	|
	*/
	//show profiles
	public function profile($username){
		$feeds   = new feeds;
		$profile = new profiles;

		//getting profile data
		$profile_settings = $profile -> load_profile_settings();
		$profile_data     = $profile -> load_profile_data($username);

		$data = array(
			"feeds"              => $feeds -> get_feeds("0"),
			"profile_completion" => $profile_settings['percentage'],
			"dom"                => $profile_settings['dom'],
			"question"           => $profile_settings['question'],
			"hidden"             => $profile_settings['hidden'],
			"profile_data"       => $profile_data
		);

		return View::make('pages.profile', $data);
	}

	//baking profile data
	public function bake_profile()
	{
		$profiles = new profiles;
		$data     = array(
			"column_name"   => Input::get('question'),
			"value"         => Input::get('answer'),
			"profile_setup" => true
		);

		return $profiles -> update_or_bake_profile_data($data);
	}

}