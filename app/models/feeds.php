<?php

class Feeds extends Eloquent {

	protected $fillable = ['u_id', 'content', 'created', 'type', 'isWall', 'wall_id'];
	public $timestamps  = false;

	//timestamp builder function
	private function time_stamp_builder($time){
		date_default_timezone_set('Asia/Kolkata'); //get this from database under user settings
		return date('l F Y g:i:a', $time);
	}

	//insert post
	public function insert_post($data){

		//if a wall shared post then build target user details
		if($data['wall_id'] != 0 || $data['wall_id'] != '0'){
			$wall_user = DB::table('users')->where('id', $data['wall_id'])->first();
			$wall_user_dat = array(
				"id"       => $data['wall_id'],
				"username" => $wall_user -> username,
				"fullname" => $wall_user -> fullname
			);
		}
		else{
			$wall_user_dat = [];
		}


		//for normal feeds
		if($data['type'] === 0 || $data['type'] === "0"){

			$id   = Session::get('id');

			$time = time();
			//store feeds data into feeds
			$data = array(
				"u_id"    => $id,
				"content" => $data['content'],
				"created" => $time,
				"type"    => $data['type'],
				"isWall"  => $data['isWall'],
				"wall_id" => $data['wall_id']
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

			$post_card_buttons = htmlfactory::bake_html("3", array("ncomments" => "comment", "hasLikes" => "hidden", "likes_count" => "", "share" => "Share"));
			$user_data         = array("fullname" => $user -> fullname, "profile_picture" => $user -> profile_picture, "username" => $user -> username);
			$data              = array_merge($data, $user_data, array("feed_id" => $feed -> id, "class_lu" => "fa fa-heart-o like", "comments" => "", "post_card_btns" => $post_card_buttons, "view_prev_comment" => "hidden", "likes_count" => "", "post_activity" => "", "wall_user_details" => $wall_user_dat));
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
				"type"    => $data['type'],
				"isWall"  => $data['isWall'],
				"wall_id" => $data['wall_id']
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

			$post_card_buttons = htmlfactory::bake_html("3", array("ncomments" => "comment", "hasLikes" => "hidden", "likes_count" => "", "share" => "share"));
			$user_data         = array("fullname" => $user -> fullname, "profile_picture" => $user -> profile_picture, "username" => $user -> username);
			$data              = array_merge($data, $data_ombed, $user_data, array("feed_id" => $feed -> id, "class_lu" => "fa fa-heart-o like", "comments" => "", "post_card_btns" => $post_card_buttons, "view_prev_comment" => "hidden", "likes_count" => "", "post_activity" => "", "wall_user_details" => $wall_user_dat));

			//modifying created timestamp to readable timestamp
			$data['created'] = $this -> time_stamp_builder($time);

			return htmlfactory::bake_html("1", $data);
		}
		//for photo updates type 3 = multi photo / type 4 is single photo update
		else if($data['type'] === "3" || $data['type'] === 3 || $data['type'] === "4" || $data['type'] === 4){
			$id   = Session::get('id');
			$time = time();
			//store feeds data into feeds
			$data = array(
				"u_id"    => $id,
				"content" => $data['content'],
				"images"  => $data['images'],
				"created" => $time,
				"type"    => $data['type'],
				"isWall"  => $data['isWall'],
				"wall_id" => $data['wall_id']
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

			$post_card_buttons = htmlfactory::bake_html("3", array("ncomments" => "comment", "hasLikes" => "hidden", "likes_count" => "", "share" => "share"));

			$user_data         = array("fullname" => $user -> fullname, "profile_picture" => $user -> profile_picture, "username" => $user -> username);

			$data              = array_merge($data, $user_data, array("feed_id" => $feed -> id, "class_lu" => "fa fa-heart-o like", "comments" => "", "post_card_btns" => $post_card_buttons, "view_prev_comment" => "hidden", "likes_count" => "", "post_activity" => "", "wall_user_details" => $wall_user_dat));
			//modifying created timestamp to readable timestamp
			$data['created'] = $this -> time_stamp_builder($time);

			return htmlfactory::bake_html("1", $data);
		}
	}

	//getting feeds by type

	public function get_feeds($type, $data){

		//shows feed not owned by the session
		if($type == 0){ //normal feed
			$feeds = DB::table('feeds')->orderBy('created', 'desc')->get();
		}
		else if($type == 1){//profile feed
			$feeds = DB::table('feeds')->where('u_id', $data['u_id'])->orWhere('wall_id', $data['u_id'])->orderBy('created', 'desc')->get();
		}

		$content = "";

		foreach ($feeds as $feeds){
			$user = DB::table('users')->join('users_profile', 'users_profile.u_id', '=', 'users.id')->where('users.id', $feeds -> u_id)->first();
			$type = $feeds -> type;

			//if a wall shared post then build target user details
			if($feeds -> wall_id != 0 || $feeds -> wall_id != '0'){
				$wall_user = DB::table('users')->where('id', $feeds -> wall_id)->first();
				$wall_user_dat = array(
					"id"       => $feeds -> wall_id,
					"username" => $wall_user -> username,
					"fullname" => $wall_user -> fullname
				);
			}
			else{
				$wall_user_dat = [];
			}

			if($type === "0" || $type === 0){ //normal feed
				//data
				$data = array(
					"feed_id"         => $feeds -> id,
					"profile_picture" => $user -> profile_picture,
					"username"        => $user -> username,
					"fullname"        => $user -> fullname,
					"content"         => $feeds -> content,
					"type"            => $feeds -> type,
					"isWall"          => $feeds -> isWall,
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
						"isWall"          => $feeds -> isWall,
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
						"isWall"          => $feeds -> isWall,
						"created"         => $this -> time_stamp_builder($feeds -> created)
					);
				}
			}
			else if($type === "3" || $type === 3 || $type === "4" || $type === 4){ //for photo updates

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
					"isWall"          => $feeds -> isWall,
					"created"         => $this -> time_stamp_builder($feeds -> created)
				);
			}

			//parsing likes
			//check if this feed_id has been liked by the session id
			$feed = DB::table('feed_likes')->where('u_id', Session::get('id'))->where('feed_id', $feeds -> id)->first();

			//counting number of likes in this feed
			$likes_count = DB::table('feed_likes')->where('feed_id', $feeds -> id)->count();

			//building like button
			//if has 0 likes
			if($likes_count == 0){
				$hasLikes = "hidden";
			}
			else if($likes_count == 1){
				$likes_count = '<b>'.$likes_count.'</b> Like';
				$hasLikes    = "";
			}
			else{
				$likes_count = '<b>'.$likes_count.'</b> Likes';
				$hasLikes = "";
			}

			//if exist if post has been liked aready heart filled
			if($feed){
				$data = array_merge($data, array("class_lu" => "fa fa-heart text-heart unlike"));
			}
			else{ //if not liked just show a heart
				$data = array_merge($data, array("class_lu" => "fa fa-heart-o like"));
			}

			//appending comments array
			if($type === "3" || $type === 3){ // for multi photos
				$comments_count = DB::table('comments')->where('feed_id', $feeds -> id)->where('type', 0)->count();
				$comments       = DB::table('comments')->join('users', 'comments.u_id', '=', 'users.id')->join('users_profile','comments.u_id', '=', 'users_profile.u_id')->where('feed_id', $feeds -> id)->where('type', 0)->select('comments.id', 'comments.comment', 'comments.created', 'users.fullname', 'users.username', 'users_profile.profile_picture')->orderBy('created', 'asc')->skip($comments_count - 3)->take(3)->get();
			}
			else{ //for other type of feed and single photos
				$comments_count = DB::table('comments')->where('feed_id', $feeds -> id)->count();
				$comments       = DB::table('comments')->join('users', 'comments.u_id', '=', 'users.id')->join('users_profile','comments.u_id', '=', 'users_profile.u_id')->where('feed_id', $feeds -> id)->select('comments.id', 'comments.comment', 'comments.created', 'users.fullname', 'users.username', 'users_profile.profile_picture')->orderBy('created', 'asc')->skip($comments_count - 3)->take(3)->get();
			}

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
				$comments_count    = "comment";
				$view_prev_comment = "hidden";
			}
			else{
				$comments_count = $comments_count." comments";
			}

			//getting any activity
			$activity = $this -> generate_activity($feeds -> id);

			//getting shares count
			$shares  = DB::table('feed_share')->where('feed_id', $feeds -> id)->count();

			($shares > 0 ? ($shares  = $shares.' '.($shares > 1 ? 'Shares' : 'Share')) : $shares = 'Share');

			$post_card_buttons = htmlfactory::bake_html("3", array("ncomments" => $comments_count, "hasLikes" => $hasLikes, "likes_count" => $likes_count, "share" => $shares));

			$data     = array_merge($data, array("likes_count" => $likes_count, "comments" => $comment_array, "post_card_btns" => $post_card_buttons, "view_prev_comment" => $view_prev_comment, "post_activity" => $activity, "wall_user_details" => $wall_user_dat));
		    $content .= htmlfactory::bake_html("1", $data);
		}

		return $content;
	}

	/* post activity generator
	/* post activity like 12 people has liked this, 12 others has shared this etc
	/* checking for this post activity
	/* checking for any latest like not by post owner himself
	*/

	public function generate_activity($feed_id){

		//checking for any latest like not by post owner himself/herself
		$time_like = DB::table('feed_likes')->select('timestamp')->where('feed_id', $feed_id)->where('u_id', '!=', Session::get('id'))->orderBy('id', 'desc')->take(1)->get();
		if($time_like){
			$latest_like_time = $time_like[0] -> timestamp;
		}
		else{
			$latest_like_time = 0;
		}

		//checking for any latest comment not by post owner himself/herself
		$time_comment = DB::table('comments')->select('created')->where('feed_id', $feed_id)->where('u_id', '!=', Session::get('id'))->orderBy('id', 'desc')->take(1)->get();
		if($time_comment){
			$latest_comment_time = $time_comment[0] -> created;

		}
		else{
			$latest_comment_time = 0;
		}

		//checking for any latest shared can be owner himself/herself
		$time_shared = DB::table('feed_share')->select('timestamp')->where('feed_id', $feed_id)->orderBy('id', 'desc')->take(1)->get();
		if($time_shared){
			$latest_shared_time = $time_shared[0] -> timestamp;

		}
		else{
			$latest_shared_time = 0;
		}

		$arr     = array( 'liked' => $latest_like_time, 'commented' => $latest_comment_time, 'shared' => $latest_shared_time );
		$max_key = array_search(max($arr),$arr);
		//return $latest_comment_time.' => '.$latest_shared_time.'->'.var_dump($time_shared).'->'.$feed_id;

		switch ($max_key) {
		    case "liked":
				//get unique likes count
				$unique_like_count_query = DB::select('SELECT COUNT(*) AS count FROM users WHERE id IN (SELECT u.id FROM feed_likes c, users u, feeds f WHERE c.u_id = u.id AND c.feed_id = f.id AND c.feed_id = '.$feed_id.' AND f.u_id != c.u_id AND u.id != '.Session::get('id').')');
				$unique_like_count_query = $unique_like_count_query[0] -> count;

				if($unique_like_count_query){
					if($unique_like_count_query == 1) { //if there is only a single like
						//getting last guy who liked
						$user = DB::select('SELECT username, fullname FROM users WHERE id IN (SELECT u.id FROM feed_likes c, users u, feeds f WHERE c.u_id = u.id AND c.feed_id = f.id AND c.feed_id = '.$feed_id.' AND f.u_id != c.u_id AND u.id != '.Session::get('id').') ORDER BY id DESC LIMIT 1');

						return '<a href="/profile/'.$user[0] -> username.'"><b>'.$user[0] -> fullname.'</b></a> like this';
						//return var_dump($user);
					}
					else if($unique_like_count_query == 2){
						$activity_fullname = array();
						$activity_username = array();

						//getting last two guys who liked
						$user = DB::select('SELECT username, fullname FROM users WHERE id IN (SELECT u.id FROM feed_likes c, users u, feeds f WHERE c.u_id = u.id AND c.feed_id = f.id AND c.feed_id = '.$feed_id.' AND f.u_id != c.u_id AND u.id != '.Session::get('id').') ORDER BY id DESC LIMIT 2');

						foreach ($user as $user) {
							array_push($activity_fullname, $user -> fullname);
							array_push($activity_username, $user -> username);
						}

						return '<a href="/profile/'.$activity_username[0].'"><b>'.$activity_fullname[0].'</b></a> and <a href="/profile/'.$activity_username[1].'"><b>'.$activity_fullname[1].'</b></a> likes this';
						//return '';
					}
					else if($unique_like_count_query > 2){
						//getting last guy who liked
						$user = DB::select('SELECT username, fullname FROM users WHERE id IN (SELECT u.id FROM feed_likes c, users u, feeds f WHERE c.u_id = u.id AND c.feed_id = f.id AND c.feed_id = '.$feed_id.' AND f.u_id != c.u_id AND u.id != '.Session::get('id').') ORDER BY id DESC LIMIT 1');

						return '<a href="/profile/'.$user[0] -> username.'"><b>'.$user[0] -> fullname.'</b></a> and <a href=""><b>'.$unique_like_count_query.' others </b></a> likes this';
					}
				}
				else{
					return '';
				}
		        break;
		    case "commented":
				//get unique comments count
				$unique_comments_count_query = DB::select('SELECT COUNT(*) AS count FROM users WHERE id IN (SELECT DISTINCT(u.id) FROM comments c, users u, feeds f WHERE c.u_id = u.id AND c.feed_id = f.id AND c.feed_id = '.$feed_id.' AND c.type = 0 AND f.u_id != c.u_id AND u.id != '.Session::get('id').')');

				$unique_comments_count_query = $unique_comments_count_query[0] -> count;

				if($unique_comments_count_query){
					if($unique_comments_count_query == 1) { //if there is only a single comment
						//getting last guy who commented
						$user = DB::select('SELECT username, fullname FROM users WHERE id IN (SELECT DISTINCT(u.id) FROM comments c, users u, feeds f WHERE c.u_id = u.id AND c.feed_id = f.id AND c.feed_id = '.$feed_id.' AND c.type = 0 AND f.u_id != c.u_id AND u.id != '.Session::get('id').') ORDER BY id DESC LIMIT 1');

						return '<a href="/profile/'.$user[0] -> username.'"><b>'.$user[0] -> fullname.'</b></a> commented on this';
					}
					else if($unique_comments_count_query == 2){
						$activity_fullname = array();
						$activity_username = array();

						//getting last two guys who commented
						$user = DB::select('SELECT username, fullname FROM users WHERE id IN (SELECT DISTINCT(u.id) FROM comments c, users u, feeds f WHERE c.u_id = u.id AND c.feed_id = f.id AND c.feed_id = '.$feed_id.' AND c.type = 0 AND f.u_id != c.u_id AND u.id != '.Session::get('id').') ORDER BY id DESC LIMIT 2');

						foreach ($user as $user) {
							array_push($activity_fullname, $user -> fullname);
							array_push($activity_username, $user -> username);
						}

						return '<a href="/profile/'.$activity_username[0].'"><b>'.$activity_fullname[0].'</b></a> and <a href="/profile/'.$activity_username[1].'"><b>'.$activity_fullname[1].'</b></a> commented on this';
					}
					else if($unique_comments_count_query > 2){
						//getting last guy who commented
						$user = DB::select('SELECT username, fullname FROM users WHERE id IN (SELECT DISTINCT(u.id) FROM comments c, users u, feeds f WHERE c.u_id = u.id AND c.feed_id = f.id AND c.feed_id = '.$feed_id.' AND c.type = 0 AND f.u_id != c.u_id AND u.id != '.Session::get('id').') ORDER BY id DESC LIMIT 1');

						return '<a href="/profile/'.$user[0] -> username.'"><b>'.$user[0] -> fullname.'</b></a> and <a href=""><b>'.$unique_comments_count_query.' others </b></a> commented on this';
					}
				}
				else{
					return '';
				}
		        break;
		    case "shared":
				//get unique shares count
				$unique_share_count = DB::select('SELECT COUNT(*) AS count FROM users WHERE id IN (SELECT u.id FROM feed_share c, users u, feeds f WHERE c.u_id = u.id AND c.feed_id = f.id AND c.feed_id = '.$feed_id.')');
				$unique_share_count = $unique_share_count[0] -> count;

				if($unique_share_count){
					if($unique_share_count == 1) { //if there is only a single share
						//getting last guy who liked
						$user = DB::select('SELECT username, fullname FROM users WHERE id IN (SELECT u.id FROM feed_share c, users u, feeds f WHERE c.u_id = u.id AND c.feed_id = f.id AND c.feed_id = '.$feed_id.' AND f.u_id != c.u_id AND u.id != '.Session::get('id').') ORDER BY id DESC LIMIT 1');

						return '<a href="/profile/'.$user[0] -> username.'"><b>'.$user[0] -> fullname.'</b></a> shared this';
						//return var_dump($user);
					}
					else if($unique_share_count == 2){
						$activity_fullname = array();
						$activity_username = array();

						//getting last two guys who shared
						$user = DB::select('SELECT username, fullname FROM users WHERE id IN (SELECT u.id FROM feed_share c, users u, feeds f WHERE c.u_id = u.id AND c.feed_id = f.id AND c.feed_id = '.$feed_id.' AND f.u_id != c.u_id AND u.id != '.Session::get('id').') ORDER BY id DESC LIMIT 2');

						foreach ($user as $user) {
							array_push($activity_fullname, $user -> fullname);
							array_push($activity_username, $user -> username);
						}

						return '<a href="/profile/'.$activity_username[0].'"><b>'.$activity_fullname[0].'</b></a> and <a href="/profile/'.$activity_username[1].'"><b>'.$activity_fullname[1].'</b></a> shared this';
						//return '';
					}
					else if($unique_share_count > 2){
						//getting last guy who shared
						$user = DB::select('SELECT username, fullname FROM users WHERE id IN (SELECT u.id FROM feed_share c, users u, feeds f WHERE c.u_id = u.id AND c.feed_id = f.id AND c.feed_id = '.$feed_id.' AND f.u_id != c.u_id AND u.id != '.Session::get('id').') ORDER BY id DESC LIMIT 1');

						return '<a href="/profile/'.$user[0] -> username.'"><b>'.$user[0] -> fullname.'</b></a> and <a href=""><b>'.$unique_share_count.' others </b></a> shared this';
					}
				}
				else{
					return '';
				}
		        break;
		}
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
			//for feed like
			if(!$feed){
				$data = array(
					"u_id"      => Session::get('id'),
					"feed_id"   => $data_id,
					"timestamp" => time()
				);
				DB::table('feed_likes')->insert($data);
			}
		}
		else if($type === "2" || $type === 2){ //for comments like
			$comment = DB::table('comment_likes')->where('u_id', Session::get('id'))->where('comment_id', $data_id)->first();

			//if current user has not liked this comment insert comment like
			if(!$comment){
				$data = array(
					"u_id"       => Session::get('id'),
					"comment_id" => $data_id,
					"timestamp"  => time()
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
			if(empty($data['single'])){ //if non single image show particular comments
				$comments_count = DB::table('comments')->where('image', $data['image'])->count();
				$comments       = DB::table('comments')->join('users', 'comments.u_id', '=', 'users.id')->join('users_profile','comments.u_id', '=', 'users_profile.u_id')->where('image', $data['image'])->select('comments.id', 'comments.comment', 'comments.created', 'users.fullname', 'users.username', 'users_profile.profile_picture')->orderBy('created', 'asc')->get();
			}
			else if($data['single']){ //if single image show its comment
				$comments_count = DB::table('comments')->where('image', $data['image'])->count();
				$comments       = DB::table('comments')->join('users', 'comments.u_id', '=', 'users.id')->join('users_profile','comments.u_id', '=', 'users_profile.u_id')->where('feed_id', $data['feed_id'])->select('comments.id', 'comments.comment', 'comments.created', 'users.fullname', 'users.username', 'users_profile.profile_picture')->orderBy('created', 'asc')->get();
			}
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

	//share post

	public function share_post($data){
		//do not insert if already shared
		$shared = DB::table('feed_share')->where('u_id', Session::get('id'))->where('feed_id', $data['feed_id'])->get();

		//insert only if not shared already
		if(!$shared){
			DB::table('feed_share')->insert($data);
		}
		else{ //else just update the timestamp
			DB::table('feed_share')->where('u_id', Session::get('id'))->where('feed_id', $data['feed_id'])->update(['timestamp' => time()]);
		}
	}
}
