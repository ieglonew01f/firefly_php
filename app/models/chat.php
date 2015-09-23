<?php

class Chat extends Eloquent {

	protected $fillable = ['u_id', 'gender', 'schooling', 'profile_picture'];
	public $timestamps  = false;
	protected $table    = 'users_profile';

	//throws chat list for view
	public function chat_list($data){
		$chat_list = '';
		if($data){
			foreach($data as $value){
				//get user details
				$user = DB::table('users_profile')->join('users', 'users.id', '=', 'users_profile.u_id')->where('users.username', '=', $value)->first();
				$data = array(
					"id"              => $user -> id,
					"fullname"        => $user -> fullname,
					"username"        => $user -> username,
					"profile_picture" => $user -> profile_picture
				);
				$chat_list .= htmlfactory::bake_html("6", $data);
			}
		}
		return $chat_list;
	}

	//get conv
	public function get_convById($id){
		$isOutbound = false;
		$s_id       = Session::get('id');

		$query      = DB::select("SELECT * FROM messages WHERE by_id = '$s_id' AND for_id = '$id' UNION SELECT * FROM messages WHERE by_id = '$id' AND for_id = '$s_id' ORDER BY id ASC");

		$html       = "";

		foreach($query as $query){
			//set seen to 1 for loaded messages
			DB::table('messages')->where('by_id', $query -> by_id)->where('for_id', $query -> for_id)->where('message', $query -> message)->update(['seen' => 1]);

			if($query -> by_id == $s_id){ //outbound message
				$isOutbound = true;
			}

			//getting user details
			$user = DB::table('users_profile')->join('users', 'users.id', '=', 'users_profile.u_id')->where('users.id', '=', $query -> by_id)->first();

			$data = array(
				"id"              => $id,
				"fullname"        => $user -> fullname,
				"profile_picture" => $user -> profile_picture,
				"username"        => $user -> username,
				"message"         => $query -> message,
				"timestamp"       => timeago::time_ago($query -> timestamp),
				"isOutbound"      => $isOutbound
			);

			$html .= htmlfactory::bake_html("10", $data);

			$isOutbound = false;
		}

		return $html;
	}

	//get chat conversation for chat box
	public function get_chat_convById($id){
		$isOutbound = false;
		$s_id       = Session::get('id');

		$query      = DB::select("SELECT * FROM messages WHERE by_id = '$s_id' AND for_id = '$id' UNION SELECT * FROM messages WHERE by_id = '$id' AND for_id = '$s_id' ORDER BY id ASC");

		$html       = "";

		foreach($query as $query){

			if($query -> by_id == $s_id){ //outbound message
				$isOutbound = true;
			}

			//getting user details
			$user = DB::table('users_profile')->join('users', 'users.id', '=', 'users_profile.u_id')->where('users.id', '=', $query -> by_id)->first();

			$data = array(
				"id"              => $id,
				"fullname"        => $user -> fullname,
				"profile_picture" => $user -> profile_picture,
				"username"        => $user -> username,
				"message"         => $query -> message,
				"timestamp"       => timeago::time_ago($query -> timestamp),
				"isOutbound"      => $isOutbound
			);

			$html .= htmlfactory::bake_html("14", $data);

			$isOutbound = false;
		}

		return $html;
	}

	//get latest conv by username
	public function get_lastConvOf($username){
		$isOutbound = false;
		$s_id       = Session::get('id');


		if($username != Session::get('username')){

			$user_array = DB::table('users_profile')->join('users', 'users.id', '=', 'users_profile.u_id')->where('users.username', '=', $username)->first();
			$id         = $user_array -> u_id;

			$query = DB::select("SELECT * FROM messages WHERE by_id = '$s_id' AND for_id = '$id' UNION SELECT * FROM messages WHERE by_id = '$id' AND for_id = '$s_id' ORDER BY id ASC");
		}
		else{ //else load the latest conv
			//get id of latest conv
			$by_id_query = DB::select("SELECT * FROM messages WHERE by_id = '$s_id' UNION SELECT * FROM messages WHERE for_id = '$s_id' ORDER BY id DESC LIMIT 1");

			if($by_id_query[0] -> by_id != $s_id){ //inner id must not be a session id
				$inner_id = $by_id_query[0] -> by_id;
			}
			else{
				$inner_id = $by_id_query[0] -> for_id;
			}

			$user_array = DB::table('users_profile')->join('users', 'users.id', '=', 'users_profile.u_id')->where('users.id', '=', $inner_id)->first();
			$id         = $inner_id;

			$query = DB::select("SELECT * FROM messages WHERE by_id = '$s_id' AND for_id = '$inner_id' UNION SELECT * FROM messages WHERE by_id = '$inner_id' AND for_id = '$s_id' ORDER BY id ASC");
		}


		$html = '';
		if(!$query){
			$html = '<div class="alert alert-warning alert-warning alert-dismissible text-center margin-top-lg" role="alert">
							  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							  <strong><span class="icon-bubble"></span> Nothing left to see here, there is no conversation between you and your friend. Psst ! don\'t be shy to say hello </strong>
							</div>';
		}
		else {
			foreach($query as $query){

				if($query -> by_id == $s_id){ //outbound message
					$isOutbound = true;
				}

				//getting user details
				$user = DB::table('users_profile')->join('users', 'users.id', '=', 'users_profile.u_id')->where('users.id', '=', $query -> by_id)->first();

				$data = array(
					"id"              => $id,
					"fullname"        => $user -> fullname,
					"profile_picture" => $user -> profile_picture,
					"username"        => $user -> username,
					"message"         => $query -> message,
					"timestamp"       => timeago::time_ago($query -> timestamp),
					"isOutbound"      => $isOutbound
				);

				$html .= htmlfactory::bake_html("10", $data);

				$isOutbound = false;

				//set seen to 1 for loaded messages
				DB::table('messages')->where('by_id', $query -> by_id)->where('for_id', $query -> for_id)->where('message', $query -> message)->update(['seen' => 1]);
			}
		}

		return array('html' => $html, 'fullname' => $user_array -> fullname, 'username' => $user_array -> username, 'id' => $user_array -> id, 'profile_picture' => $user_array -> profile_picture);
	}

	//save Chat
	public function save_chatByUsername($data){
		$user_array = DB::table('users_profile')->join('users', 'users.id', '=', 'users_profile.u_id')->where('users.username', '=', $data['for_username'])->first();

		$data         = array(
			"for_id"    => $user_array -> u_id,
			"by_id"     => Session::get('id'),
			"timestamp" => time(),
			"message"   => $data['message'],
			"seen"      => 0
		);

		//check if already present
		$query = DB::table('messages')->where('message', $data['message'])->where('by_id', Session::get('id'))->where('for_id', $user_array -> u_id)->first();
		DB::table('messages')->insert($data);
	}

	//search conv
	public function search_conv_byKeyword($data){
		$keyword = $data['keyword'];
		$id      = Session::get('id');
		$users   = [];
		$html    = "";
		//get all unique user inbound/outbound for current user
		$query   = DB::select("SELECT * FROM messages WHERE for_id = '$id' UNION SELECT * FROM messages WHERE by_id = '$id' ORDER BY id DESC");

		foreach($query as $query) {

			$for_id = $query -> for_id;
			$by_id  = $query -> by_id;

			//generate for_id ie not equals session id
			if($for_id != $id){
				if(!in_array($for_id, $users)){
					array_push($users, $for_id); 
				}
			}
			else{
				if(!in_array($by_id, $users)){
					array_push($users, $by_id); //byId is not session id
				}
			}
		}

		foreach($users as $for_id) {

			//get the latest conv list for the current user
			$message   = DB::select("SELECT * FROM messages where (by_id = '$id' and for_id = '$for_id') OR (by_id = '$for_id' and for_id = '$id') ORDER BY id DESC LIMIT 1"); //if exist then
			$query     = DB::table('users')->join('users_profile', 'users_profile.u_id', '=', 'users.id')->where('users.id', '=', $for_id)->where('users.fullname', 'LIKE', '%'.$keyword.'%')->first();

			if($query)
				$html .= htmlfactory::bake_html("11", ['id' => $query -> u_id, 'fullname' => $query -> fullname, 'username' => $query -> username, 'profile_picture' => $query -> profile_picture, 'message' => $message[0] -> message, 'timestamp' => timeago::time_ago($message[0] -> timestamp)]);
		}

		return $html;
	}


	//saves active conv
	public function save_active_conversation($data){
		$with_id_query = DB::table('users')->select('id')->where('username', $data['with_username'])->first();
		$with_id       = $with_id_query -> id;

		$query  = DB::table('conversation')->where('with_id', $with_id)->where('u_id', Session::get('id'))->first();

		if(!$query){
			$data_i = array(
				"u_id"    => Session::get('id'),
				"with_id" => $with_id
			);
			DB::table('conversation')->insert($data_i);
		}
		else{
			if($data['type'] == 0 || $data['type'] == "0"){
				DB::table('conversation')->where('with_id', $with_id)->where('u_id', Session::get('id'))->delete();
			}
		}
	}
}
