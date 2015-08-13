<?php

class FeedController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Feed Controller
	| Author: lonew01f
	| First commit: 5/9/2015 01:40 AM
	| Describtion: Feed page controller handlers all the aspect related to
	| sharing post
	|--------------------------------------------------------------------------
	|
	*/

	public function bake_post()
	{
		$feeds   = new feeds;
		$type    = Input::get('type');
		$content = Input::get('content');

		if($type === 0 || $type === "0"){ //normal feed
			$data = array(
				"u_id"    => Session::get('id'),
				"content" => $content,
				"created" => time(),
				"type"    => 0,
				"isWall"  => Input::get('isWall'),
				"wall_id" => Input::get('wall_id')
			);
		}
		else if($type === 1 || $type === "1"){ //youtube feed
			$code  = Input::get('youtube_vcode');
			$thumb = Input::get('youtube_vthumb');
			$desc  = Input::get('youtube_vdesc');
			$title = Input::get('youtube_vtitle');

			$data  = array(
				"code"    => $code,
				"thumb"   => $thumb,
				"desc"    => $desc,
				"title"   => $title,
				"content" => $content,
				"u_id"    => Session::get('id'),
				"type"    => 1,
				"created" => time(),
				"isWall"  => Input::get('isWall'),
				"wall_id" => Input::get('wall_id')
			);
		}
		else if($type === 2 || $type === "2"){  //soundcloud feed
			$code  = Input::get('soundcloud_code');

			$data  = array(
				"code"    => $code,
				"content" => $content,
				"u_id"    => Session::get('id'),
				"type"    => 2,
				"created" => time(),
				"isWall"  => Input::get('isWall'),
				"wall_id" => Input::get('wall_id')
			);
		}

		return $feeds -> insert_post($data);
	}

	//for image update / photo update special function causes its json bitches !
	public function bake_special_post()
	{
		$feeds = new feeds;
		$data  = json_decode(Input::get('data'));
		$type  = 0;

		/* Check type here */
		/* If is multi image then type = 3 */
		if(count($data -> photo_array) > 1){
			$type = 3;
		}
		/* If is a single image then type = 4 */
		else if(count($data -> photo_array) == 1){
			$type = 4;
		}


		$data  = array(
			"content" => $data -> content,
			"images"  => $data -> photo_array,
			"u_id"    => Session::get('id'),
			"type"    => $type, // for image update
			"created" => time(),
			"isWall"  => $data -> isWall,
			"wall_id" => $data -> wall_id
		);

		return $feeds -> insert_post($data);
		
	}

	//share post
	public function share_post(){

		$data = array(
			"u_id"      => Session::get('id'),
			"feed_id"   => Input::get('feed_id'),
			"timestamp" => time()
		);

		$feeds = new feeds;
		$feeds -> share_post($data);
	}

	public function edit_post(){
		$data  = array(
			"feed_id"    => Input::get('feed_id'),
			"content"    => Input::get('content'),
			"u_id"       => Session::get('id'),
			"updated_at" => time()
		);

		$feeds = new feeds;
		$feeds -> edit_post($data);
	}

	public function delete_post(){
		$feeds = new feeds;
		$feeds -> delete_post(Input::get('feed_id'));
	}

	public function unlike_like_post_and_comment(){
		$feeds = new feeds;

		if(Input::get('feed_id')){ //for post
			if(Input::get('type') === "like"){
				$feeds -> like_post_or_comment(1, Input::get('feed_id'));
			}
			else if(Input::get('type') === "unlike"){
				$feeds -> unlike_post_or_comment(1, Input::get('feed_id'));
			}
		}
		else if(Input::get('comment_id')){ //for comments
			if(Input::get('type') === "like"){
				$feeds -> like_post_or_comment(2, Input::get('comment_id'));
			}
			else if(Input::get('type') === "unlike"){
				$feeds -> unlike_post_or_comment(2, Input::get('comment_id'));
			}
		}
	}

	//add comment
	public function add_comment(){

		$feeds = new feeds;

		if(empty(Input::get('image'))){ //if comment on a post
			$data  = array(
				"feed_id" => Input::get('feed_id'),
				"u_id"    => Session::get('id'),
				"comment" => Input::get('comment'),
				"created" => time(),
				"type"    => 0 //type 0 for normal comments feed comments
	 		);
		}
		else if(!empty(Input::get('image'))){ //if a gallery comment
			$data  = array(
				"feed_id" => Input::get('feed_id'),
				"image"   => Input::get('image'),
				"u_id"    => Session::get('id'),
				"comment" => Input::get('comment'),
				"created" => time(),
				"type"    => 1 // for gallery photo comments
	 		);
		}

		$feeds -> add_comment_data($data);
	}

	//edit comment

	public function edit_comment(){
		$feeds = new feeds;
		$data  = array(
			"comment_id" => Input::get('comment_id'),
			"comment"    => Input::get('comment')
		);

		$feeds -> edit_comment_data($data);
	}

	//delete comment

	public function delete_comment(){
		$feeds = new feeds;
		$data  = array(
			"comment_id" => Input::get('comment_id')
		);

		$feeds -> delete_comment_data($data);
	}

	//show comment
	public function show_comment(){
		$feeds = new feeds;
		$data  = array(
			"feed_id"   => Input::get('feed_id'),
			"offset"    => Input::get('offset'),
			"remainder" => Input::get('remainder'),
			"image"		=> ""
		);

		return $feeds -> show_comment_data($data);
	}

	//load gallery comments
	public function show_gallery_comments(){
		$feeds = new feeds;

		if(Input::get('single')){ //if only a single image
			$data  = array(
				"image"   => Input::get('image'),
				"feed_id" => Input::get('feed_id'),
				"single"  => 1
			);
		}
		else{
			$data  = array(
				"image"   => Input::get('image'),
				"feed_id" => Input::get('feed_id')
			);
		}

		return $feeds -> show_comment_data($data);
	}

	//gets feed details for photo gallery
	public function get_photo_gallery_data(){
		$feeds = new feeds;
		return $feeds -> load_photo_gallery_data(Input::all());
	}
}
