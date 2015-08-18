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

	public function index($username)
	{
		//$feeds   = new feeds;
		$profile = new profiles;
		$chat    = new chat;

		$inbox_chatter_data = $chat -> get_lastConvOf($username);

		//getting profile data
		$profile_data = $profile -> load_profile_data(Session::get('username'));
		$inbox_data   = $profile -> load_inbox_data();

		$data = array(
			"inbox_data"         => $inbox_data,
			"profile_data"       => $profile_data,
			"inbox_chatter_data" => $inbox_chatter_data
		);

		return View::make('pages.inbox', $data);
	}

	//get the conv
	public function get_conv(){
		$chat = new chat;
		return $chat -> get_convById(Input::get('id'));
	}

	//save conv
	public function save_conv(){
		$chat = new chat;
		$chat -> save_chatByUsername(['for_username' => Input::get('for_username'), 'message' => Input::get('message')]);
	}

	//search conv

	public function search_conv(){
		$chat = new chat;
		$chat -> search_conv_byKeyword(['keyword' => Input::get('keyword')]);
	}
}
