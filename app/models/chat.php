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
		$website_url   = 'http://localhost'; //change this as required
		$friends_array = [];

		$data = DB::table('users_profile')->join('users', 'users.id', '=', 'users_profile.u_id')->where('users.username', '=', $username)->first();

		//generate friendship button
		$button_data        = DB::table('friends')->join('users', 'users.id', '=', 'friends.for_id')->where('users.username', '=', $username)->first();
		$follow_button_data = DB::table('followers')->join('users', 'users.id', '=', 'followers.following')->where('users.username', '=', $username)->first();

		//generate friends array for the session user
		$friends_list = DB::table('friends')->join('users', 'users.id', '=', 'friends.for_id')->where('friends.by_id', '=', Session::get('id'))->get();

		foreach($friends_list as $friends_list){
			array_push($friends_array, $friends_list -> username);
		}

		if($data -> u_id == Session::get('id')){ //if user is viewing his own profile do no show friendship buton and follower button
			//getting modal for profile picture change

			$modal_data =  htmlfactory::bake_html("5", array());


			//generating buttons
			$friendship_button = '';
			$follow_button  = '';
			$message_button = '<a href="'.$website_url.'/inbox/" class="btn btn-success btn-transparent btn-sm">Messages</a>';
			$more_button    = '
	          <div class="btn-group">
	            <button type="button" class="btn btn-default btn-transparent btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">More <span class="caret"></span></button>
	            <ul class="dropdown-menu mrt10" role="menu">
	              <li><a href="javascript:;" class="friend"><b><span class="text-muted" data-icon="&#xe060;"></span> Edit profile</b></a></li>
	              <li><a href="javascript:;" class="change-banner"><b><span class="text-muted" data-icon="&#xe07f;"></span> Change Banner</b></a></li>
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
				if($button_data -> status == '01') //request sent
					$friendship_button = '<button type="button" class="btn btn-default btn-transparent btn-sm dropdown-toggle button_friend" data-toggle="dropdown" aria-expanded="false">Friend request sent <span class="caret"></span></button>';
			}
			else{ // nothing found show add friend button
				$friendship_button = '<button type="button" data-type="01"  data-id="'.$data -> u_id.'" class="btn btn-default btn-transparent btn-sm dropdown-toggle button_friend friend" aria-expanded="false">Add as a friend</button>';
			}

			//generate follower button
			if($follow_button_data){
				$follow_button = '<button data-type="3"  data-id="'.$data -> u_id.'" class="btn btn-success btn-transparent btn-sm follow">Following </button>';
			}
			else{
				$follow_button = '<button data-type="2"  data-id="'.$data -> u_id.'" class="btn btn-success btn-transparent btn-sm follow">Follow</button>';
			}

			//generate message button
			$message_button = '<a href="'.$website_url.'/inbox/'.$username.'" class="btn btn-success btn-transparent btn-sm">Message</a>';
			$more_button    = '';
			$modal_data     = '';
		}

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
			/* ----------- META DATA  ------------*/
			"friendship_button" => $friendship_button,
			"follow_button"     => $follow_button,
			"message_button"    => $message_button,
			"more_button"       => $more_button,
			"modal_body"        => $modal_data,
			"friends_array"     => json_encode($friends_array)
		);
	}

	//add or remove friends
	public function add_or_remove_friends_follow_or_unfollow_people($data){
		/* type = 01 -> add friend / request sent
		   type = 11 -> confirm friendship
		   type = 01 -> reject friend request
		   type = 10 -> cancle friend request
		   type = 2  -> follow target id
		   type = 3  -> unfollow target id
		*/

		$for_id = $data['profile_id'];
		$type   = $data['type'];

		if($data['type'] == 01 || $data['type'] == "01"){ //sent a friend request
			//send friend request

			//check if request already exists
			$data = DB::table('friends')->where('by_id', Session::get('id'))->where('for_id', $for_id)->first();

			if(!$data){ //if returned nothing then insert friend request
				DB::table('friends')->insert(array('by_id' => Session::get('id'), 'for_id' => $for_id, 'status' => '01'));
			}
		}
		else if($data['type'] == 10 || $data['type'] == "10"){ //cancle friend request
			DB::table('friends')->where('by_id', Session::get('id'))->where('for_id', $for_id)->where('status', '01')->delete(); //delete the request
		}
		else if($data['type'] == 2 || $data['type'] == "2"){ //follow some one ;)
			//follow profile_id

			//check if follow id already exist
			$data = DB::table('followers')->where('follower', Session::get('id'))->where('following', $for_id)->first();

			if(!$data){ //if returned nothing then insert follower data
				DB::table('followers')->insert(array('follower' => Session::get('id'), 'following' => $for_id));
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
}