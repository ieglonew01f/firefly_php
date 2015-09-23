<?php

class ViewController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| View page controller
	| Author: lonew01f
	| First commit: 9/29/2015 01:00 PM
	| Describtion: View page controller handlers all the aspect related to
	| generating listing notifications/search results and more
	|--------------------------------------------------------------------------
	|
	*/

	public function search($query)
	{
		$profile = new profiles;

		//getting profile data
		$profile_settings = $profile -> load_profile_settings();
		$profile_data     = $profile -> load_profile_data(Session::get('username'));

		//getting search results
		$profiles = new profiles;
		$search_results = $profiles -> get_peoples_array(["key" => $query, 'type' => 'hasCount']);

		if($search_results['count'] > 1){
        	$search_results_count = '<h4 class="nmt"><span class="text-muted icon-magnifier"></span> Search results '.$search_results['count'].' match found</h4>';
		}
		else{
        	$search_results_count = '<h4 class="nmt"><span class="text-muted icon-magnifier"></span> Search result '.$search_results['count'].' match found</h4>';
		}

		$data = array(
			"feeds"                => '',
			"profile_completion"   => $profile_settings['percentage'],
			"dom"                  => $profile_settings['dom'],
			"question"             => $profile_settings['question'],
			"hidden"               => $profile_settings['hidden'],
			"profile_data"         => $profile_data,
			"view_data"            => $search_results['users'],
			"view_header"          => $search_results_count
		);

		return View::make('pages.view', $data);
	}

	public function notifications($id = 0){
		$profile = new profiles;

		//getting profile data
		$profile_settings = $profile -> load_profile_settings();
		$profile_data     = $profile -> load_profile_data(Session::get('username'));

		//getting notifications by id if id
		$notifications      = new notifications;

		if($id){
			$notifications_data = $notifications -> get_notifications(1, ['id' => $id]);
		}
		else if($id == 0){ //get all notifications
			$notifications_data = $notifications -> get_notifications(0, []);
		}

		$notifications_header = '<h4 class="nmt"><span class="text-muted icon-bell"></span> Notifications</h4>';

		$data = array(
			"feeds"                => '',
			"profile_completion"   => $profile_settings['percentage'],
			"dom"                  => $profile_settings['dom'],
			"question"             => $profile_settings['question'],
			"hidden"               => $profile_settings['hidden'],
			"profile_data"         => $profile_data,
			"view_data"            => $notifications_data,
			"view_header"          => $notifications_header
		);

		return View::make('pages.view', $data);
	}
}
