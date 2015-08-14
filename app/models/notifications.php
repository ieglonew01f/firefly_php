<?php

class Notifications extends Eloquent {

	protected $fillable = ['by_id', 'for_id', 'type', 'meta', 'timestamp', 'seen'];
	public $timestamps  = false;
	protected $table    = 'notifications';

	/*
	*	1 Feed liked notification
	* 2 Commented notification
	* 3 New friend request notification
	* 4 Accepted friend request notification
	* 5 followed you notification
	* 6 liked comment notification
	* 7 shared your feed
	*/

	public static function throw_notification($type, $data){

		//check if notification already exist by the same
		$query = DB::table('notifications')->where('by_id', $data['by_id'])->where('for_id', $data['for_id'])->where('type', $type)->first();
		if(!$query){ //if not found then insert
			$meta = array(
				"by_id"     => $data['by_id'],
				"for_id"    => $data['for_id'],
				"timestamp" => time(),
				"type"      => $type
			);
			DB::table('notifications')->insert($meta);
		}
		else{
			//if found
			//then just update timestamp and seen variable
			$query = DB::table('notifications')->where('by_id', $data['by_id'])->where('for_id', $data['for_id'])->where('type', $type)->update(['seen' => 0, 'timestamp' => time()]);
		}
	}

	//get notifications api call
	public function get_notifications($type, $data){
		$html  = '';
		$query = DB::table('notifications')->where('for_id', Session::get('id'))->where('by_id', '!=', Session::get('id'))->get();

		foreach($query as $query){
			//set seen as 1 (seen);
			DB::table('notifications')->where('id', $query -> id)->update(['seen' => '1']);
			$user_details       = DB::table('users')->select('users.id as user_id', 'users.fullname', 'users.username', 'users_profile.profile_picture')->join('users_profile', 'users_profile.u_id', '=', 'users.id')->where('users.id', '=', $query -> by_id)->first();
			$timestamp          = timeago::time_ago($query -> timestamp);
			$data               = array(
				"fullname"        => $user_details -> fullname,
				"id"              => $user_details -> user_id,
				"username"        => $user_details -> username,
				"profile_picture" => $user_details -> profile_picture,
				"type"            => $query -> type,
				"timestamp"       => $timestamp
			);

			$html .= htmlfactory::bake_html('7', $data);
		}

		return $html;
	}

	//returns a json of latest data
	public function get_latest_data($type, $data){
		$query = DB::table('notifications')->where('for_id', Session::get('id'))->where('by_id', '!=', Session::get('id'))->where('seen', '0')->count();

		$data = array(
			'notifications' => $query
		);

		return json_encode($data);
	}
}
