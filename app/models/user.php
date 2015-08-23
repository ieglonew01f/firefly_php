<?php

class User extends Eloquent {

	protected $fillable = ['username', 'fullname', 'email', 'password', 'ip', 'code', 'active'];

	public function add_user($input){
		//verifying new user data
		//checking for email duplication
		$user = $this::where('email', $input['email'])->first();
		
		if($user) {
			return 0; // failure
		}
		else {
			//saving new user
			$this::create($input);
			$username = $this::where('email', $input['email']) -> where('password', $input['password']) ->first();
			if($username){
				$session_data  = array(
					"id"       => $username -> id,
					"email"    => $username -> email,
					"username" => $username -> username
				);

				Session::put($session_data);

				//jenkins bot says hello
				DB::table('messages')->insert(['by_id' => 20, 'for_id' => $username -> id, 'timestamp' => time(), 'message' => 'Hi I\'m Jenkins Bot']);
				return 1;
			}
			else
				return 0;		//if insertion into DB failed
		}
	}

	public function validate_username($username){

		//if session exists
		if(Session::get('id')){
			if($username == Session::get('username')){
				return 1;
			}
			else{
				$user = $this::where('username', $username)->first();
			}
		}
		else{
			$user = $this::where('username', $username)->first();
		}

		if($user) {
			return 0; // failure
		}
		else {
			return 1;
		}
	}

	public function login_user($data){
		//validate login
		//check if username and password combo works
		$username = $this::where('username', $data['email_username']) -> where('password', md5($data['password'])) ->first();

		if($username){
			//success
			//bake some cookies
			$session_data  = array(
				"id"       => $username -> id,
				"email"    => $username -> email,
				"username" => $username -> username
			);

			Session::put($session_data);
			return 1; 
		}
		else{
			//check for if email and password combo works
			$email = $this::where('email', $data['email_username']) -> where('password', md5($data['password'])) ->first();
			if($email){
				//bake some cookies
				$session_data  = array(
					"id"       => $email -> id,
					"email"    => $email -> email,
					"username" => $email -> username
				);

				Session::put($session_data);
				return 1; //success
			}
			else{
				return 0; //fail
			}
		}
	}
}
