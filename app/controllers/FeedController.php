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
		$feeds = new feeds;

		$type    = Input::get('type');
		$content = Input::get('content');

		if($type === 0 || $type === "0"){ //normal feed
			$data = array(
				"u_id"    => Session::get('id'), 
				"content" => $content,
				"created" => time(),
				"type"    => 0
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
				"created" => time()
			);
		}
		else if($type === 2 || $type === "2"){  //soundcloud feed
			$code  = Input::get('soundcloud_code');

			$data  = array(
				"code"    => $code,
				"content" => $content,
				"u_id"    => Session::get('id'),
				"type"    => 2,
				"created" => time()
			);
		}

		return $feeds -> insert_post($data);
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
		$data  = array(
			"feed_id" => Input::get('feed_id'),
			"u_id"    => Session::get('id'),
			"comment" => Input::get('comment'),
			"created" => time()
 		);

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
}
