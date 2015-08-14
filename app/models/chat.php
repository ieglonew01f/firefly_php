<?php

class Chat extends Eloquent {

	protected $fillable = ['u_id', 'gender', 'schooling', 'profile_picture'];
	public $timestamps  = false;
	protected $table    = 'users_profile';

	//throws chat list for view
	public function chat_list($data){
		$chat_list = '';

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

		return $chat_list;
	}
}
