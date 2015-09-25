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
		$session_data     = $profile -> load_profile_data(Session::get('username'));

		$data = array(
			"feeds"              => $feeds -> get_feeds(1, array('offset' => 0, 'u_id' => $profile_data['u_id'])),
			"profile_completion" => $profile_settings['percentage'],
			"dom"                => $profile_settings['dom'],
			"question"           => $profile_settings['question'],
			"hidden"             => $profile_settings['hidden'],
			"profile_data"       => $profile_data,
			"session_data"       => $session_data,
			"isSelected"         => "profile"
		);

		return View::make('pages.profile', $data);
	}

	//show photos
	public function photos($username){
		$feeds   = new feeds;
		$profile = new profiles;

		//getting profile data
		$profile_settings = $profile -> load_profile_settings();
		$profile_data     = $profile -> load_profile_data($username);
		$session_data     = $profile -> load_profile_data(Session::get('username'));

		$data = array(
			"feeds"              => "",
			"profile_completion" => "",
			"dom"                => $profile_settings['dom'],
			"question"           => $profile_settings['question'],
			"hidden"             => $profile_settings['hidden'],
			"profile_data"       => $profile_data,
			"session_data"       => $session_data,
			"isSelected"         => "photos"
		);

		return View::make('pages.photos', $data);
	}

	//show albums
	public function albums($username){
		$feeds   = new feeds;
		$profile = new profiles;

		//getting profile data
		$profile_settings = $profile -> load_profile_settings();
		$profile_data     = $profile -> load_profile_data($username);
		$session_data     = $profile -> load_profile_data(Session::get('username'));

		$data = array(
			"feeds"              => "",
			"profile_completion" => "",
			"dom"                => $profile_settings['dom'],
			"question"           => $profile_settings['question'],
			"hidden"             => $profile_settings['hidden'],
			"profile_data"       => $profile_data,
			"session_data"       => $session_data,
			"isSelected"         => "photos"
		);

		return View::make('pages.albums', $data);
	}

	//get album photos by id
	public function albumsById($username, $id){
		$feeds   = new feeds;
		$profile = new profiles;

		//getting profile data
		$profile_settings = $profile -> load_profile_settings();
		$profile_data     = $profile -> load_profile_data($username);
		$session_data     = $profile -> load_profile_data(Session::get('username'));
		$album_photos     = $profile -> load_album_photos($id);

		$data = array(
			"feeds"              => "",
			"profile_completion" => "",
			"dom"                => $profile_settings['dom'],
			"question"           => $profile_settings['question'],
			"hidden"             => $profile_settings['hidden'],
			"profile_data"       => $profile_data,
			"session_data"       => $session_data,
			"isSelected"         => "photos",
			"album_photos"       => $album_photos
		);

		return View::make('pages.albumPhotos', $data);
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

	public function people_handler(){
		$profiles = new profiles;
		$data     = array(
			"profile_id"   => Input::get('profile_id'),
			"type"         => Input::get('type')
		);

		return $profiles -> add_or_remove_friends_follow_or_unfollow_people($data);
	}

	//search people
	public function search_people(){
		$profiles = new profiles;
		return $profiles -> get_peoples_array(["key" => Input::get('keyword'), 'type' => '']);
	}

	//search people
	public function search_people_json(){
		$profiles = new profiles;
		return $profiles -> get_people_json();
	}


	//show about
	public function about($username){
		$feeds   = new feeds;
		$profile = new profiles;

		//getting profile data
		$profile_settings = $profile -> load_profile_settings();
		$profile_data     = $profile -> load_profile_data($username);
		$session_data     = $profile -> load_profile_data(Session::get('username'));

		$data = array(
			"feeds"              => $feeds -> get_feeds(1, array('offset' => 0, 'u_id' => $profile_data['u_id'])),
			"profile_completion" => $profile_settings['percentage'],
			"dom"                => $profile_settings['dom'],
			"question"           => $profile_settings['question'],
			"hidden"             => $profile_settings['hidden'],
			"profile_data"       => $profile_data,
			"session_data"       => $session_data,
			"isSelected"         => "profile"
		);

		return View::make('pages.about', $data);
	}
}
