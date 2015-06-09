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

		$data ? DB::table('users_profile')->where("u_id", Session::get('id'))->update([$question => $value]) : DB::table('users_profile')->insert(["u_id" => Session::get('id'), "schooling" => $value]);

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
		$data = DB::table('users_profile')->join('users', 'users.id', '=', 'users_profile.u_id')->where('users.username', '=', $username)->first();

		//generate friendship button
		$button_data = DB::table('friends')->join('users', 'users.id', '=', 'friends.for_id')->where('users.username', '=', $username)->first();

		if($data -> u_id == Session::get('id')){ //if user is viewing his own profile do no show friendship buton
			$friendship_button = '';
		}
		else{
			if($button_data){ //if exist
				if($button_data -> status == '01') //request sent
					$friendship_button = '<button type="button" class="btn btn-default btn-transparent btn-sm dropdown-toggle button_friend" data-toggle="dropdown" aria-expanded="false">Friend request sent <span class="caret"></span></button>';
			}
			else{ // nothing found show add friend button
				$friendship_button = '<button type="button" data-type="01"  data-id="'.$data -> u_id.'" class="btn btn-default btn-transparent btn-sm dropdown-toggle button_friend friend" aria-expanded="false">Add as a friend</button>';
			}
		}

		return array(
			"base_url"          => "http://localhost",
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
			"friendship_button" => $friendship_button
		);
	}

	//add or remove friends
	public function add_or_remove_friends($data){
		/* type = 01 -> add friend / request sent
		   type = 11 -> confirm friendship
		   type = 01 -> reject friend request
		   type = 10 -> cancle friend request
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
	}
}

