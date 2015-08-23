<?php

class Settings extends Eloquent {

	protected $fillable = ['password_min_length', 'password_max_length', 'fullname_min_length', 'fullname_max_length', 'username_min_length', 'username_max_length'];

	//load global settings
	public function get_settings(){

		$settings = $this::find(1);

		$data = array(
			"password_min_length" =>  $settings -> password_min_length,
			"password_max_length" =>  $settings -> password_max_length,
			"fullname_min_length" =>  $settings -> fullname_min_length,
			"fullname_max_length" =>  $settings -> fullname_max_length,
			"username_min_length" =>  $settings -> username_min_length,
			"username_max_length" =>  $settings -> username_max_length
		);

		return $data;
	}

	//update profile settings
	public function update_settings($profile_data, $user_data){

		DB::table('users')->where('id', Session::get('id'))->update($user_data);
		DB::table('users_profile')->where('u_id', Session::get('id'))->update($profile_data);

	}

}
