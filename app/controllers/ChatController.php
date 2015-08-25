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

	//this saves the converstaion on to the database
	public function save_active_conversation(){
		$chat          = new chat;
		$with_username = Input::get('with_username');
		$type          = Input::get('type');

		$data = array(
			"with_username" => $with_username,
			"type"          => $type
 		);

		$chat -> save_active_conversation($data);
	}

	//get chat conv
	public function get_chat_conv(){
		$chat = new chat;
		return $chat -> get_chat_convById(Input::get('id'));
	}
}
