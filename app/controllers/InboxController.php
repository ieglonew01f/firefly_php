<?php

class InboxController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Inbox page controller
	| Author: lonew01f
	| First commit: 6/8/2015 09:44 PM
	| Describtion: Inbox page controller handlers all the aspect related to
	| inbox
	|--------------------------------------------------------------------------
	|
	*/

	public function index()
	{
		//$feeds   = new feeds;
		$profile = new profiles;

		//getting profile data
		$profile_data = $profile -> load_profile_data(Session::get('username'));

		$data = array(
			"profile_data" => $profile_data
		);

		return View::make('pages.inbox', $data);
	}

}
