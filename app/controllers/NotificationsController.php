<?php

class NotificationsController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Notifications Controller
	| Author: lonew01f
	| First commit: 11/8/2015 09:53 PM
	| Describtion: Contains everything related to notifications in jenkins
	|--------------------------------------------------------------------------
	|
	*/

	//returns a latest list of notifications
	public function get_notifications(){
		$notifications = new notifications;
		return $notifications -> get_notifications('','');
	}

	//whats new gets everything thats new
	//polling
	public function whats_new(){
		$notifications = new notifications;
		return $notifications -> get_latest_data('', '');
	}
}
