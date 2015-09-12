<?php

class Profiles extends Eloquent {

	protected $fillable = ['u_id', 'gender', 'schooling', 'profile_picture'];
	public $timestamps  = false;
	protected $table    = 'users_profile';

	//update or bake profile data
	public function update_or_bake_profile_data($data){

		$question      = $data['column_name'];
		$value         = $data['value'];
		$profile_setup = $data['profile_setup'];

		//check if the colname has a value
		$data = DB::table('users_profile')->where("u_id", Session::get('id'))->first();

		$data ? DB::table('users_profile')->where("u_id", Session::get('id'))->update([$question => $value]) : DB::table('users_profile')->insert(["u_id" => Session::get('id'), $question => $value]);

		if($profile_setup) return $this -> get_next_empty_profile_data();
	}

	//gets the next empty profile column
	private function get_next_empty_profile_data(){

		$profile = DB::table('users_profile')->where("u_id", Session::get('id'))->first();

		if(empty($profile -> gender)) return "gender";

		else if(empty($profile -> schooling)) return "schooling";

		else if(empty($profile -> college)) return "college";

		else if(empty($profile -> location)) return "location";

		else if(empty($profile -> home)) return "home";

		else if(empty($profile -> relationship)) return "relationship";

		else if($profile -> profile_picture === 'jenkins_user_icon.jpg') return "profile_picture";
	}

	//get profile settings
	public function load_profile_settings(){
		//get profile completion status
		$id = Session::get('id');

		$data = DB::table('users_profile')->where("u_id", Session::get('id'))->first();

		if($data){
			$profile = DB::table('users_profile')->select(DB::raw("SUM((`gender` = '') + (`schooling` = '') + (`college` = '') + (`location` = '') + (`home` = '')) + (`relationship` = '') + (`profile_picture` = 'jenkins_user_icon.jpg') AS percentage"))->where("u_id", Session::get('id'))->get();

			//building html
			$html_data = htmlfactory::bake_html("4", array('type' => $this -> get_next_empty_profile_data()));

			if($profile[0] -> percentage == 7) $percentage = 1;

			else if($profile[0] -> percentage == 0) $percentage = 100;

			else $percentage = round(100 - 100/7 - $profile[0] -> percentage);

			return array("percentage" => $percentage, "question" => $html_data['question'], "dom" => $html_data['dom'], "hidden" => $html_data['hidden']);
		}
		else{ //create the row
			DB::table('users_profile')->insert(["u_id" => Session::get('id')]);

			$this -> load_profile_settings(); // call me again
		}
	}

	/*	load profile data get all profile data
		relative to the user who is viewing the profile including friendship status
		privacy settings.
	*/
	public function load_profile_data($username){
		$website_url       = 'http://localhost'; //change this as required
		$friends_array     = [];
		$friendship_status = '10';
		$friendship_text   = 'Cancle friend request';

		$data = DB::table('users_profile')->join('users', 'users.id', '=', 'users_profile.u_id')->where('users.username', '=', $username)->first();

		$profile_id = $data -> u_id;

		//generate friendship button
		$button_data        = DB::table('friends')->join('users', 'users.id', '=', 'friends.for_id')->where('users.username', '=', $username)->where('friends.by_id', '=', Session::get('id'))->first();
		$follow_button_data = DB::table('followers')->join('users', 'users.id', '=', 'followers.following')->where('users.username', '=', $username)->first();

		//generate friends array for the session user
		$first = DB::table('friends')->join('users', 'users.id', '=', 'friends.by_id')->where('friends.for_id', '=', Session::get('id'));
		$friends_list = DB::table('friends')->join('users', 'users.id', '=', 'friends.for_id')->where('friends.by_id', '=', Session::get('id'))->union($first)->get();

		foreach($friends_list as $friends_list){
			array_push($friends_array, $friends_list -> username);
		}

		if($data -> u_id == Session::get('id')){ //if user is viewing his own profile do no show friendship buton and follower button
			//getting modal for profile picture change

			$modal_data =  htmlfactory::bake_html("5", array());

			//generating buttons
			$friendship_button = '';
			$follow_button     = '';
			$message_button    = '<a href="/inbox/'.Session::get('username').'" class="btn btn-success btn-transparent btn-sm">Messages</a>';
			$more_button       = '
	          <div class="btn-group">
	            <button type="button" class="btn btn-default btn-transparent btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">More <span class="caret"></span></button>
	            <ul class="dropdown-menu mrt10" role="menu">
	              <li><a href="javascript:;" class="friend"><b><span class="text-muted" data-icon="&#xe060;"></span> Edit profile</b></a></li>
	              <li><a href="javascript:;" class="change-banner"><b><span class="text-muted" data-icon="&#xe07f;"></span> Change Banner</b></a></li>
	              <li><a href="javascript:;" class="btn-resize"><b><span class="text-muted icon-frame"></span> Resize Banner</b></a></li>
	              <li><a href="javascript:;" class="friend"><b><span class="text-muted" data-icon="&#xe082;"></span> Remove banner</b></a></li>
	              <li class="divider"></li>
	              <li><a href="javascript:;" data-toggle="modal" data-target="#total_utility_modal" class="change-profile-picture"><b><span class="text-muted" data-icon="&#xe07f;"></span> Change Profile Picture</b></a></li>
	              <li><a href="#" class="friend"><b><span class="text-muted" data-icon="&#xe09a;"></span> Profile settings</b></a></li>
	            </ul>
	          </div>
			';
		}
		else{
			if($button_data){ //if exist
				if($button_data -> status == '01'){ //request sent
					$friendship_button = '<button type="button" class="btn btn-default btn-transparent btn-sm dropdown-toggle button_friend" data-toggle="dropdown" aria-expanded="false">Friend request sent <span class="caret"></span></button>';
				}
				else if($button_data -> status == '11'){ //friends
					$friendship_button = '<button type="button" class="btn btn-default btn-transparent btn-sm dropdown-toggle button_friend" data-toggle="dropdown" aria-expanded="false">Friends <span class="caret"></span></button>';
					$friendship_status = '00';
					$friendship_text   = 'Unfriend';
				}
			}
			else{ // nothing found check if this profile has sent you a friend request you = session id
				$button_data_inner = DB::table('friends')->join('users', 'users.id', '=', 'friends.by_id')->where('users.username', '=', $username)->where('friends.for_id', '=', Session::get('id'))->first();
				if($button_data_inner){ //if so then show accept friend request button
					if($button_data_inner -> status == '01'){ //request sent
						$friendship_button = '<button type="button" data-type="11"  data-id="'.$data -> u_id.'" class="btn btn-default btn-transparent btn-sm dropdown-toggle button_friend friend" aria-expanded="false">Accept friend request</button>';
					}
					else if($button_data_inner -> status == '11'){ //friends
						$friendship_button = '<button type="button" class="btn btn-default btn-transparent btn-sm dropdown-toggle button_friend" data-toggle="dropdown" aria-expanded="false">Friends <span class="caret"></span></button>';
						$friendship_status = '00';
						$friendship_text   = 'Unfriend';
					}
				}
				else{
					$friendship_button = '<button type="button" data-type="01"  data-id="'.$data -> u_id.'" class="btn btn-default btn-transparent btn-sm dropdown-toggle button_friend friend" aria-expanded="false">Add as a friend</button>';
				}
			}

			//generate follower button
			if($follow_button_data){
				$follow_button = '<button data-type="3"  data-id="'.$data -> u_id.'" class="btn btn-success btn-transparent btn-sm follow">Following </button>';
			}
			else{
				$follow_button = '<button data-type="2"  data-id="'.$data -> u_id.'" class="btn btn-success btn-transparent btn-sm follow">Follow</button>';
			}

			//generate message button
			$message_button = '<a href="/inbox/'.$username.'" class="btn btn-success btn-transparent btn-sm">Message</a>';
			$more_button    = '';
			$modal_data     = '';
		}

		//load friends/followers/following data
		//$friends_count = DB::select('SELECT count(id) as count FROM friends WHERE id IN (SELECT id FROM friends WHERE by_id = "$profile_id" UNION SELECT id FROM friends WHERE for_id = "$profile_id")');
		$friends_count = DB::table('friends')->select('id')->where('for_id', $profile_id)->orWhere('by_id', $profile_id)->where('status', '11')->count();
		$followers     = DB::table('followers')->where('following', $profile_id)->count();
		$following     = DB::table('followers')->where('follower', $profile_id)->count();

		//get open conversations 
		$open_chat_query = DB::table('conversation')->join('users', 'users.id', '=', 'conversation.with_id')->where('u_id', Session::get('id'))->get();

		if($open_chat_query){
			$open_chats_html = "";
			$right = 230;
			foreach ($open_chat_query as $open_chat_query) {
				$chat = new chat;
				$messages_data = $chat -> get_chat_convById($open_chat_query -> with_id);

				$open_chats_html .= htmlfactory::bake_html("13", ['fullname' => $open_chat_query -> fullname, 'username' => $open_chat_query -> username, 'right' => $right, 'messages' => $messages_data]);
				$right = $right + (230 - 35);
			}
		}
		else{
			$open_chats_html = "";
		}

		//loading profile photos
		$photos      = DB::table('photo_update')->join('feeds', 'feeds.id', '=', 'photo_update.feed_id')->where('feeds.u_id', '=', $data-> u_id)->get();
		$photo_array = array();
		$photo_count = 0;

		foreach($photos as $photos){
			array_push($photo_array, ['image' => $photos -> image, 'feed_id' => $photos -> feed_id]);
			$photo_count ++;
		}

		$photo_array = htmlfactory::bake_html("15", ['images' => $photo_array]);


		//throw all profile data

		return array(
			"base_url"          => $website_url,
			/* ----------- PROFILE DATA  ------------*/
			"u_id"              => $data -> u_id,
			"fullname"          => $data -> fullname,
			"username"          => $username,
			"email"             => $data -> email,
			"gender"            => $data -> gender,
			"location"          => $data -> location,
			"home"              => $data -> home,
			"schooling"         => $data -> schooling,
			"college"           => $data -> college,
			"relationship"      => $data -> relationship,
			"profile_picture"   => $data -> profile_picture,
			"banner"            => $data -> banner,
			"banner_position"   => $data -> banner_position,
			"about"             => $data -> about,
			"friends"           => $friends_count,
			"followers"         => $followers,
			"following"         => $following,
			/* ----------- META DATA  ------------*/
			"friendship_button" => $friendship_button,
			"friendship_text"   => $friendship_text,
			"friendship_status" => $friendship_status,
			"follow_button"     => $follow_button,
			"message_button"    => $message_button,
			"more_button"       => $more_button,
			"modal_body"        => $modal_data,
			"friends_array"     => json_encode($friends_array),
			"open_chat_list"    => $open_chats_html,
			"photo_array"       => $photo_array,
			"photo_count"       => $photo_count
		);
	}

	//add or remove friends
	public function add_or_remove_friends_follow_or_unfollow_people($data){
		/* type = 01  -> add friend / request sent
		   type = 11  -> confirm friendship
		   type = 10  -> cancle friend request
		   type = 22  -> reject friend request
		   type = 00  -> remove from friend list / unfriend
		   type = 2   -> follow target id
		   type = 3   -> unfollow target id
		*/

		$for_id = $data['profile_id'];
		$type   = $data['type'];

		if($data['type'] == 01 || $data['type'] == "01"){ //send a friend request
			//send friend request
			//check if request already exists
			$join = DB::table('friends')->where('by_id', $for_id)->where('for_id', Session::get('id'));
			$data = DB::table('friends')->where('by_id', Session::get('id'))->where('for_id', $for_id)->union($join)->first();

			if(!$data){ //if returned nothing then insert friend request
				DB::table('friends')->insert(array('by_id' => Session::get('id'), 'for_id' => $for_id, 'status' => '01'));
				notifications::throw_notification('3', array('by_id' => Session::get('id'), 'for_id' => $for_id));
			}
		}
		else if($data['type'] == 10 || $data['type'] == "10"){ //cancle friend request
			DB::table('friends')->where('by_id', Session::get('id'))->where('for_id', $for_id)->where('status', '01')->delete(); //delete the request
		}
		else if($data['type'] == 11 || $data['type'] == "11"){ //accept friend request
			DB::table('friends')->where('for_id', Session::get('id'))->where('by_id', $for_id)->update(['status' => 11]);
			//delete pervious notification (friend request notification)
			DB::table('notifications')->where('type', '3')->where('for_id', Session::get('id'))->where('by_id', $for_id)->delete();
			notifications::throw_notification('4', array('for_id' => $for_id, 'by_id' => Session::get('id')));
		}
		else if($data['type'] == 00 || $data['type'] == "00"){ //unfriend
			$query = DB::table('friends')->where('by_id', Session::get('id'))->where('for_id', $for_id)->delete();
			if(!$query){
				DB::table('friends')->where('for_id', Session::get('id'))->where('by_id', $for_id)->delete();
			}
		}
		else if($data['type'] == 22 || $data['type'] == "22"){ //reject friend request
			DB::table('friends')->where('by_id', $for_id)->where('for_id', Session::get('id'))->delete();
			//delete pervious notification (friend request notification)
			DB::table('notifications')->where('type', '3')->where('for_id', Session::get('id'))->where('by_id', $for_id)->delete();
		}

		else if($data['type'] == 2 || $data['type'] == "2"){ //follow some one ;)
			//follow profile_id

			//check if follow id already exist
			$data = DB::table('followers')->where('follower', Session::get('id'))->where('following', $for_id)->first();

			if(!$data){ //if returned nothing then insert follower data
				DB::table('followers')->insert(array('follower' => Session::get('id'), 'following' => $for_id));
				notifications::throw_notification('5', array('by_id' => Session::get('id'), 'for_id' => $for_id));
			}
		}
		else if($data['type'] == 3 || $data['type'] == "3"){ //unfollow somone ;D
			//unfollow profile_id

			//check if follow id already exist
			$data = DB::table('followers')->where('follower', Session::get('id'))->where('following', $for_id)->first();

			if($data){ //if following
				DB::table('followers')->where('follower', Session::get('id'))->where('following', $for_id)->delete(); //delete the request
			}
		}
	}

	//get peoples array by keyword
	public function get_peoples_array($data){
		$query = DB::table('users')->join('users_profile', 'users_profile.u_id', '=', 'users.id')->where('users.fullname', 'LIKE', '%'.$data['key'].'%')->get();
		$users = '';

		foreach($query as $query){
			$users .= htmlfactory::bake_html("8", ['id' => $query -> id, 'fullname' => $query -> fullname, 'username' => $query -> username, 'profile_picture' => $query -> profile_picture, 'college' => $query -> college]);
		}

		return $users;
	}

	//load inbox data
	public function load_inbox_data(){
	
		$id     = Session::get('id');
		$users  = [];
		$html   = "";
		//get all unique user inbound/outbound for current user
		$query  = DB::select("SELECT * FROM messages WHERE for_id = '$id' UNION SELECT * FROM messages WHERE by_id = '$id' ORDER BY id DESC");

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
			$query     = DB::table('users')->join('users_profile', 'users_profile.u_id', '=', 'users.id')->where('users.id', '=', $for_id)->first();

			$html     .= htmlfactory::bake_html("11", ['id' => $query -> u_id, 'fullname' => $query -> fullname, 'username' => $query -> username, 'profile_picture' => $query -> profile_picture, 'message' => $message[0] -> message, 'timestamp' => timeago::time_ago($message[0] -> timestamp)]);
		}

		return $html;
	}

	//load new message modal content
	public function load_inbox_modal_data(){
		$first        = DB::table('friends')->join('users', 'users.id', '=', 'friends.by_id')->where('friends.for_id', '=', Session::get('id'))->join('users_profile', 'users.id', '=', 'users_profile.u_id');
		$friends_list = DB::table('friends')->join('users', 'users.id', '=', 'friends.for_id')->join('users_profile', 'users.id', '=', 'users_profile.u_id')->where('friends.by_id', '=', Session::get('id'))->union($first)->get();
		$html         = "";

		foreach($friends_list as $friends_list) {
			$html .= htmlfactory::bake_html("12", ['fullname' => $friends_list -> fullname, 'username' => $friends_list -> username, 'profile_picture' => $friends_list -> profile_picture, 'college' => $friends_list -> college]);
		}
		return $html;
	}

}
