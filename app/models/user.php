<?php

class User extends Eloquent {

	protected $fillable = ['username', 'fullname', 'email', 'password', 'ip', 'code', 'active'];

	public function add_user($input){
		//verifying new user data
		//checking for email duplication
		$user = $this::where('email', $input['email'])->first();
		
		if($user) {
			return json_encode(['error_message' => 'Email address is already registed', 'status' => 'error']);
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
				return json_encode(['status' => 'success']);
			}
			else
				return json_encode(['error_message' => 'Email address is already registed', 'status' => 'error']);
		}
	}

	/*NEW*/
	//signup by email
	public function new_email_signup($data){
		//verifying new user data
		//checking for email duplication
		$user = $this::where('email', $data['email'])->first();

		if($user) {
			return 0; // failure
		}
		else {

			$data = array(
				"email"  => $data['email'],
				"code"   => md5($data['email'].time()),
				"ip"     => $_SERVER['REMOTE_ADDR'],
				"active" => 0
			);

			$this::create($data);
			$user = $this::where('email', '=', $data['email'])->first();

			return ['id' => $user -> id, 'code' => $user -> code];
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
