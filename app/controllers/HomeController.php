<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Home page controller
	| Author: lonew01f
	| First commit: 5/9/2015 06:47 PM
	| Describtion: Home page controller handlers all the aspect related to
	| home page of the user
	|--------------------------------------------------------------------------
	|
	*/

	public function index()
	{
		$feeds   = new feeds;
		$profile = new profiles;

		//getting profile data
		$profile_settings = $profile -> load_profile_settings();
		$profile_data     = $profile -> load_profile_data(Session::get('username'));

		$data = array(
			"feeds"              => $feeds -> get_feeds(0, array('u_id' => Session::get('id'))),
			"profile_completion" => $profile_settings['percentage'],
			"dom"                => $profile_settings['dom'],
			"question"           => $profile_settings['question'],
			"hidden"             => $profile_settings['hidden'],
			"profile_data"       => $profile_data
		);

		return View::make('pages.home', $data);
	}

}
