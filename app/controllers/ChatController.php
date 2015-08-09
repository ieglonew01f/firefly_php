<?php

class ChatController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Chat Controller
	| Author: lonew01f
	| First commit: 8/8/2015 1:13 PM
	| Describtion: Contains everything related to chat/inbox in jenkins
	|--------------------------------------------------------------------------
	|
	*/

	public function get_chatlist(){
		$chat = new chat;
		$data = Input::get('friend_list');
		
		return $chat -> chat_list($data);
	}
}
