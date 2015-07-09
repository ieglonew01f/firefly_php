<?php

class Feeds extends Eloquent {

	protected $fillable = ['u_id', 'content', 'created', 'type'];
	public $timestamps  = false;

	//timestamp builder function
	private function time_stamp_builder($time){
		date_default_timezone_set('Asia/Kolkata'); //get this from database under user settings
		return date('l F Y g:i:a', $time);
	}

	//insert post
	public function insert_post($data){

		//for normal feeds
		if($data['type'] === 0 || $data['type'] === "0"){

			$id   = Session::get('id');
			
			$time = time();
			//store feeds data into feeds
			$data = array(
				"u_id"    => $id, 
				"content" => $data['content'],
				"created" => $time,
				"type"    => $data['type']
			);

			$this::create($data);

			//get the stored feed id
			$feed = DB::table('feeds')->where('u_id', $id)->where('created', $time)->first();

			//getting user data
			$user = DB::table('users')->where('id', $data['u_id'])->first();


			//getting post card data buttons
			//getting number of comments
			$comments_count    = DB::table('comments')->where('feed_id', $feed -> id)->count();
			if($comments_count < 1 || $comments_count == 0){
				$comments_count = "comment";
			}
			else{
				$comments_count = $comments_count." comments";
			}

			$user = DB::table('users')->join('users_profile', 'users_profile.u_id', '=', 'users.id')->where('users.id', $data['u_id'])->first();

			$post_card_buttons = htmlfactory::bake_html("3", array("ncomments" => "comment"));
			$user_data         = array("fullname" => $user -> fullname, "profile_picture" => $user -> profile_picture, "username" => $user -> username);
			$data              = array_merge($data, $user_data, array("feed_id" => $feed -> id, "class_lu" => "fa fa-heart-o like", "comments" => "", "post_card_btns" => $post_card_buttons, "view_prev_comment" => "hidden"));
			//modifying created timestamp to readable timestamp
			$data['created'] = $this -> time_stamp_builder($time);

			return htmlfactory::bake_html("1", $data);
		} 
		//for oembd data
		else if($data['type'] === 1 || $data['type'] === "1" || $data['type'] === 2 || $data['type'] === "2"){

			$id   = Session::get('id');
			$time = time();
			//store feeds data into feeds
			$data_feed = array(
				"u_id"    => $id, 
				"content" => $data['content'],
				"created" => $time,
				"type"    => $data['type']
			);

			$this::create($data_feed);

			//get the stored feed id
			$feed = DB::table('feeds')->where('u_id', $id)->where('created', $time)->first();

			if($data['type'] === 1 || $data['type'] === "1"){ //FOR YOUTUBE FEED
				$data_ombed   = array(
					"feed_id" => $feed -> id,
					"vcode"   => $data['code'],
					"vthumb"  => $data['thumb'],
					"vdesc"   => $data['desc'],
					"vtitle"  => $data['title']
				);
			}
			else if($data['type'] === 2 || $data['type'] === "2"){ //IF SOUND CLOUD DATA
				$data_ombed   = array(
					"feed_id" => $feed -> id,
					"vcode"   => $data['code']
				);
			}

			//saving oembd data
			DB::table('oembd_data')->insert($data_ombed);


			//baking post
			//getting user data
			$user = DB::table('users')->join('users_profile', 'users_profile.u_id', '=', 'users.id')->where('users.id', $data['u_id'])->first();

			$post_card_buttons = htmlfactory::bake_html("3", array("ncomments" => "comment"));
			$user_data         = array("fullname" => $user -> fullname, "profile_picture" => $user -> profile_picture, "username" => $user -> username);
			$data              = array_merge($data, $data_ombed, $user_data, array("feed_id" => $feed -> id, "class_lu" => "fa fa-heart-o like", "comments" => "", "post_card_btns" => $post_card_buttons, "view_prev_comment" => "hidden"));

			//modifying created timestamp to readable timestamp
			$data['created'] = $this -> time_stamp_builder($time);

			return htmlfactory::bake_html("1", $data);
		} 
		//for photo updates
		if($data['type'] === 3 || $data['type'] === "3"){

			$id   = Session::get('id');
			$time = time();
			//store feeds data into feeds
			$data = array(
				"u_id"    => $id, 
				"content" => $data['content'],
				"images"  => $data['images'],
				"created" => $time,
				"type"    => $data['type']
			);

			$this::create($data);

			//get the stored feed id
			$feed = DB::table('feeds')->where('u_id', $id)->where('created', $time)->first();

			foreach($data['images'] as $image){
				DB::table('photo_update')->insert(array("feed_id" => $feed -> id, "image" => $image));
			}

			//getting user data
			$user = DB::table('users')->where('id', $data['u_id'])->first();


			//getting post card data buttons
			//getting number of comments
			$comments_count    = DB::table('comments')->where('feed_id', $feed -> id)->count();
			if($comments_count < 1 || $comments_count == 0){
				$comments_count = "comment";
			}
			else{
				$comments_count = $comments_count." comments";
			}

			$user = DB::table('users')->join('users_profile', 'users_profile.u_id', '=', 'users.id')->where('users.id', $data['u_id'])->first();

			$post_card_buttons = htmlfactory::bake_html("3", array("ncomments" => "comment"));
			$user_data         = array("fullname" => $user -> fullname, "profile_picture" => $user -> profile_picture, "username" => $user -> username);
			$data              = array_merge($data, $user_data, array("feed_id" => $feed -> id, "class_lu" => "fa fa-heart-o like", "comments" => "", "post_card_btns" => $post_card_buttons, "view_prev_comment" => "hidden"));
			//modifying created timestamp to readable timestamp
			$data['created'] = $this -> time_stamp_builder($time);

			return htmlfactory::bake_html("1", $data);
		} 
	}

	//getting feeds by type

	public function get_feeds($type){

		$feeds   = DB::table('feeds')->orderBy('created', 'desc')->get(); // custom feed query ( raw )
		$content = "";

		foreach ($feeds as $feeds){
			$user = DB::table('users')->join('users_profile', 'users_profile.u_id', '=', 'users.id')->where('users.id', $feeds -> u_id)->first();
			$type = $feeds -> type;

			if($type === "0" || $type === 0){ //normal feed
				//data
				$data = array(
					"feed_id"         => $feeds -> id,
					"profile_picture" => $user -> profile_picture,
					"username"        => $user -> username,
					"fullname"        => $user -> fullname,
					"content"         => $feeds -> content,
					"type"            => $feeds -> type,
					"created"         => $this -> time_stamp_builder($feeds -> created)
				);
			}
			else if($type === "1" || $type === 1 || $type === "2" || $type === 2){ //oembd feed
				$ombed = DB::table('oembd_data')->where('feed_id', $feeds -> id)->first();

				if($type === "1" || $type === 1){ //for youtube
					$data   = array(
						"feed_id"         => $feeds -> id,
						"vcode"           => $ombed -> vcode,
						"vthumb"          => $ombed -> vthumb,
						"vdesc"           => $ombed -> vdesc,
						"vtitle"          => $ombed -> vtitle,
						"fullname"        => $user -> fullname,
						"username"        => $user -> username,
						"profile_picture" => $user -> profile_picture,
						"content"         => $feeds -> content,
						"type"            => $feeds -> type,
						"created"         => $this -> time_stamp_builder($feeds -> created)
					);
				}
				else if($type === "2" || $type === 2){ //for sound cloud
					$data   = array(
						"feed_id"         => $feeds -> id,
						"vcode"           => $ombed -> vcode,
						"fullname"        => $user -> fullname,
						"username"        => $user -> username,
						"profile_picture" => $user -> profile_picture,
						"content"         => $feeds -> content,
						"type"            => $feeds -> type,
						"created"         => $this -> time_stamp_builder($feeds -> created)
					);
				}
			}
			else if($type === "3" || $type === 3){ //for photo updates

				$image  = DB::table('photo_update')->where('feed_id', $feeds -> id)->get();
				$images = array();

				foreach($image as $image){
					array_push($images, $image -> image);
				}

				$data   = array(
					"feed_id"         => $feeds -> id,
					"fullname"        => $user -> fullname,
					"username"        => $user -> username,
					"profile_picture" => $user -> profile_picture,
					"content"         => $feeds -> content,
					"images"          => $images,
					"type"            => $feeds -> type,
					"created"         => $this -> time_stamp_builder($feeds -> created)
				);
			}

			//parsing likes
			//check if this feed_id has been liked by the session id
			$feed = DB::table('feed_likes')->where('u_id', Session::get('id'))->where('feed_id', $feeds -> id)->first();

			//if exist if post has been liked aready heart filled
			if($feed){
				$data = array_merge($data, array("class_lu" => "fa fa-heart text-heart unlike"));
			}
			else{ //if not liked just show a heart
				$data = array_merge($data, array("class_lu" => "fa fa-heart-o like"));
			}

			//appending comments array
			$comments_count = DB::table('comments')->where('feed_id', $feeds -> id)->where('type', 0)->count();
			$comments       = DB::table('comments')->join('users', 'comments.u_id', '=', 'users.id')->join('users_profile','comments.u_id', '=', 'users_profile.u_id')->where('feed_id', $feeds -> id)->where('type', 0)->select('comments.id', 'comments.comment', 'comments.created', 'users.fullname', 'users.username', 'users_profile.profile_picture')->orderBy('created', 'asc')->skip($comments_count - 3)->take(3)->get();
			$comment_array = "";

			foreach ($comments as $comments){
				//getting comments like count
				$clike_count = DB::table('comment_likes')->where('comment_id', $comments -> id)->count();
				if($clike_count > 0){
					$clike_count = "+".$clike_count;
					$is_liked    = "text-primary";
				}
				else{
					$is_liked    = "";
					$clike_count = "";
				}

				//checking ownership of comment
				$query = DB::table('comments')->where('id', $comments -> id)->where('u_id', Session::get('id'))->count();
				if($query > 0){
					$delete_edit_class = "";
				}
				else{
					$delete_edit_class = "hidden";
				}

				//checking if the user has liked the comment or not
				$this_liked = DB::table('comment_likes')->where('comment_id', $comments -> id)->where('u_id', Session::get('id'))->count();

				if($this_liked > 0){
					$like_class = "unlike";
				}else{
					$like_class = "like";
				}

				//if comment count > 3
				if($comments_count > 3){
					$view_prev_comment = "";
				}
				else{
					$view_prev_comment = "hidden";
				}

				$data_c = array(
					"delete_edit_class"   => $delete_edit_class,
					"like_class"          => $like_class,
					"is_liked"            => $is_liked,
					"comment_likes_count" => $clike_count,
					"fullname"            => $comments -> fullname,
					"username"            => $comments -> username,
					"profile_picture"     => $comments -> profile_picture,
					"comment_id"          => $comments -> id,
					"comment"             => $comments -> comment,
					"created"             => $this -> time_stamp_builder($comments -> created)
				);
				$comment_array .= htmlfactory::bake_html("2", $data_c);
			}

			//getting post card data buttons
			if($comments_count < 1 || $comments_count == 0){
				$comments_count = "comment";
				$view_prev_comment = "hidden";
			}
			else{
				$comments_count = $comments_count." comments";
			}

			$post_card_buttons = htmlfactory::bake_html("3", array("ncomments" => $comments_count));

			$data     = array_merge($data, array("comments" => $comment_array, "post_card_btns" => $post_card_buttons, "view_prev_comment" => $view_prev_comment));
		    $content .= htmlfactory::bake_html("1", $data);
		} 

		return $content;
	}

	//editing existing feeds
	public function edit_post($data){
		//verify ownership
		$feed = DB::table('feeds')->where('u_id', $data['u_id'])->where('id', $data['feed_id'])->first();

		if($feed){
			$data_f = array(
				"content"    => $data['content'],
				"updated_at" => $data['updated_at']
			);

			DB::table('feeds')->where('id', '=', $data['feed_id'])->update($data_f);
		}
	}

	//deleting existing feeds
	public function delete_post($feed_id){
		//verify ownership
		$feed = DB::table('feeds')->where('u_id', Session::get('id'))->where('id', $feed_id)->first();

		if($feed){
			DB::table('feeds')->where('id', '=', $feed_id)->where('u_id', Session::get('id'))->delete();
		}
	}

	//insert like
	public function like_post_or_comment($type, $data_id){
		if($type === "1" || $type === 1){ //for post
			$feed = DB::table('feed_likes')->where('u_id', Session::get('id'))->where('feed_id', $data_id)->first();

			//if current user has not liked this post insert feed like
			if(!$feed){
				$data = array(
					"u_id"    => Session::get('id'),
					"feed_id" => $data_id
				);
				DB::table('feed_likes')->insert($data);
			}
		}
		else if($type === "2" || $type === 2){ //for comments
			$comment = DB::table('comment_likes')->where('u_id', Session::get('id'))->where('comment_id', $data_id)->first();

			//if current user has not liked this comment insert comment like
			if(!$comment){
				$data = array(
					"u_id"       => Session::get('id'),
					"comment_id" => $data_id
				);
				DB::table('comment_likes')->insert($data);
			}
		}
	}

	//delete like
	public function unlike_post_or_comment($type, $data_id){
		if($type === "1" || $type === 1){ //for post
			$feed = DB::table('feed_likes')->where('u_id', Session::get('id'))->where('feed_id', $data_id)->first();

			//if current user has liked this post delete feed like
			if($feed){
				DB::table('feed_likes')->where('u_id', Session::get('id'))->where('feed_id', $data_id)->delete();
			}
		}
		else if($type === "2" || $type === 2){ //for comments
			DB::table('comment_likes')->where('u_id', Session::get('id'))->where('comment_id', $data_id)->delete();
		}
	}

	//insert comment
	public function add_comment_data($data){
		$comment_id = DB::table('comments')->insertGetId($data);
		$created    = $data['created'];

		//getting user data
		$users = DB::table('users')->join('users_profile', 'users_profile.u_id', '=', 'users.id')->where('users.id', Session::get('id'))->first();

		if($comment_id){ 
			$data = array(
				"view_prev_comment"   => "",
				"delete_edit_class"   => "",
				"comment_likes_count" => "",
				"is_liked"            => "",
				"like_class"          => "like",
				"fullname"            => $users -> fullname,
				"username"            => $users -> username,
				"profile_picture"     => $users -> profile_picture,
				"comment_id"          => $comment_id,
				"comment"             => $data['comment'],
				"created"             => $this -> time_stamp_builder($created)
			);

			echo htmlfactory::bake_html("2", $data);
		}
	}

	//edit comment
	public function edit_comment_data($data){
		//verify ownership
		$comment = DB::table('comments')->where('u_id', Session::get('id'))->where('id', $data['comment_id'])->first();

		if($comment){ //if owner then update
			DB::table('comments')->where('u_id', Session::get('id'))->where('id', $data['comment_id'])->update(['comment' => $data['comment']]);
		}
	}

	//delete comment

	public function delete_comment_data($data){
		//verify ownership
		$comment = DB::table('comments')->where('u_id', Session::get('id'))->where('id', $data['comment_id'])->first();

		if($comment){ //if owner then delete
			DB::table('comments')->where('u_id', Session::get('id'))->where('id', $data['comment_id'])->delete();
		}
	}

	//show prev comments with limit
	public function show_comment_data($data){
		if($data['image']){ //if loading gallery comments
			$comments_count = DB::table('comments')->where('image', $data['image'])->count();
			$comments       = DB::table('comments')->join('users', 'comments.u_id', '=', 'users.id')->join('users_profile','comments.u_id', '=', 'users_profile.u_id')->where('image', $data['image'])->select('comments.id', 'comments.comment', 'comments.created', 'users.fullname', 'users.username', 'users_profile.profile_picture')->orderBy('created', 'asc')->get();
		}
		else if(empty($data['image'])){
			$comments_count = DB::table('comments')->where('feed_id', $data['feed_id'])->count();
			$comments       = DB::table('comments')->join('users', 'comments.u_id', '=', 'users.id')->join('users_profile','comments.u_id', '=', 'users_profile.u_id')->where('feed_id', $data['feed_id'])->select('comments.id', 'comments.comment', 'comments.created', 'users.fullname', 'users.username', 'users_profile.profile_picture')->orderBy('created', 'asc')->skip($data['offset'])->take($data['remainder'])->get();
		}

		$comment_array  = "";

		foreach($comments as $comments){
			//getting comments like count
			$clike_count = DB::table('comment_likes')->where('comment_id', $comments -> id)->count();
			if($clike_count > 0){
				$clike_count = "+".$clike_count;
				$is_liked    = "text-primary";
			}
			else{
				$is_liked    = "";
				$clike_count = "";
			}

			//checking ownership of comment
			$query = DB::table('comments')->where('id', $comments -> id)->where('u_id', Session::get('id'))->count();
			if($query > 0){
				$delete_edit_class = "";
			}
			else{
				$delete_edit_class = "hidden";
			}

			//checking if the user has liked the comment or not
			$this_liked = DB::table('comment_likes')->where('comment_id', $comments -> id)->where('u_id', Session::get('id'))->count();

			if($this_liked > 0){
				$like_class = "unlike";
			}else{
				$like_class = "like";
			}

			//if comment count > 3
			if($comments_count > 3){
				$view_prev_comment = "";
			}
			else{
				$view_prev_comment = "hidden";
			}

			$data_c = array(
				"delete_edit_class"   => $delete_edit_class,
				"like_class"          => $like_class,
				"is_liked"            => $is_liked,
				"comment_likes_count" => $clike_count,
				"fullname"            => $comments -> fullname,
				"username"            => $comments -> username,
				"profile_picture"     => $comments -> profile_picture,
				"comment_id"          => $comments -> id,
				"comment"             => $comments -> comment,
				"created"             => $this -> time_stamp_builder($comments -> created)
			);
			$comment_array .= htmlfactory::bake_html("2", $data_c);
		}

		return $comment_array;
	}

	//bake photo gallery
	public function load_photo_gallery_data($data){
		$feed_id = $data['feed_id'];
		$imgs    = array();
		//get user details by feed_id
		$user = DB::table('users')->join('feeds', 'users.id', '=', 'feeds.u_id')->join('users_profile', 'users.id', '=', 'users_profile.u_id')->where('feeds.id', '=', $feed_id)->first();

		//getting image data
		$images = DB::table('photo_update')->where('feed_id', $feed_id)->where('image', '!=', $data['image'])->get();

		foreach($images as $images){
			array_push($imgs, $images);
		}

		$result = array("user_data" => $user, "images" => $imgs);

		return json_encode($result);
	}
}
