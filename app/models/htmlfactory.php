<?php

class Htmlfactory {

	//insert post
	public static function bake_html($type, $data){

		/*
		* type =  1 -> for baking posts
		* type =  2 -> for comments html data
		* type =  3 -> for baking post card buttons
		* type =  4 -> for profile setup
		* type =  5 -> for generating profile picture change modal
		* type =  6 -> for generating chat list
		* type =  7 -> for generating notification list
		* type =  8 -> Search results generator
		* type =  9 -> Inbox Contact list
		* type = 10 -> Generating inbox conversation list
		* type = 11 -> for conversation listing
		* type = 12 -> for generating inbox modal new message data
		* type = 13 -> for generating chat boxes
		* type = 14 -> for listing chat conversations get conv
		* type = 15 -> for listing photos in photo albums
		* type = 16 -> for listing photo albums
		* type = 17 -> for listing album photos
		*/

		#$base_path = 'http://localhost'; //change it to site url

		/* session data and declerables */

		$u_id_session            = Session::get('id');
		$fullname_session        = Session::get('fullname');
		$username_session        = Session::get('username');
		$profile_picture_session = DB::table('users_profile')->where('u_id', $u_id_session)->first()->profile_picture; //get session user profile
		$activity                = '';



		if($type === "1" || $type === 1){ //post card
			if($data['type'] === "0" || $data['type'] === 0){ //normal feed
				$content = '<p class="mrt10">'.$data['content'].'</p>';
			}
			else if($data['type'] === "1" || $data['type'] === 1){ //youtube feed
				$content = '
							<p class="mrt10">'.$data['content'].'</p>
							<div id="video_frame">
						        <div class="media vframe_media">
						          <div class="media-left">
						            <a target="_blank" href="https://www.youtube.com/watch?v='.$data['vcode'].'">
						              <img width="200" src="'.$data['vthumb'].'" class="media-object">
						            </a>
						          </div>
						          <div class="media-body">
						            <h4 class="media-heading">'.$data['vtitle'].'</h4>
						            <p>'.$data['vdesc'].'</p>
						          </div>
						        </div>
						    </div>';
			}
			else if($data['type'] === "2" || $data['type'] === 2){ //soundcloud feed
				$content = '
						<p class="mrt10">'.$data['content'].'</p>
						<iframe width="100%" class="margin-top-sm" height="166" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/'.$data['vcode'].'&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;visual=true"></iframe>
						';
			}
			else if($data['type'] === "3" || $data['type'] === 3 || $data['type'] === "4" || $data['type'] === 4){ //photo feed
				$img_list = '';
				$counter  = 3;
				foreach($data['images'] as $images){
					$img_list .= '<div class="item"><img width="100" class="feedPhotos" data-id="'.$data['feed_id'].'" data-img="'.$images.'" src="/uploads/'.$images.'"></img></div>';

					if($counter == 0) break; //load only 4 images

					$counter --;
				}

				$content = '
						<p class="mrt10">'.$data['content'].'</p>
						<div class="img-collage-div">
							'.$img_list.'
						</div>
						';
			}


			/*Generating post activity */
			//if a wall post then do this
			if($data['isWall']){
				$data['post_activity'] = '<a href="/profile/'.$data['username'].'"><b>'.$data['fullname'].'</b></a> <span class="text-muted text-bold">shared on</span> <a href="/profile/'.$data['wall_user_details']['username'].'"><b>'.$data['wall_user_details']['fullname'].'\'s</b></a> <span class="text-muted text-bold">wall</span>';
			}
			//if there is a post activity then do this
			if($data['post_activity']){

	            $activity = '
	            	<div class="activity">
		              	<h5 class="nmb nmt">'.$data['post_activity'].'</h5>
		              	<hr class="hr-sm"/>
	              	</div>';
			}

			return '
	            <div data-id="'.$data['feed_id'].'" class="post-container">
	              <div class="post-cards">
	              	'.$activity.'
	              	<div id="delete_loader" class="loader loader-inner ball-pulse pull-right hidden"><div></div><div></div><div></div></div>
					<div id="post_dropdown" class="btn-group pull-right">
	                  <button type="button" class="close font-size-18 text-muted dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
	                    <span class="fa fa-chevron-circle-down text-muted"></span>
	                  </button>
						<ul class="dropdown-menu post-card-dropdown" role="menu">
							<li><b><span class="icon-flag"></span> Report this post</b> <br/> &nbsp; &nbsp; &nbsp; <small>Give it for review</small></li>
							<li><b><span data-icon="&#xe004;"></span> Unfollow Mukunda Gogoi</b> </br> &nbsp; &nbsp; &nbsp; <small>Stop seeing post but stay friends</small></li>
							<li><b><span data-icon="&#xe082;"></span> Stop notifications </b> </br> &nbsp; &nbsp; &nbsp; <small>Stop recieving updates from this post</small></li>
							<li class="editpost"><b><span data-icon=""></span> Edit this post </b> </br></li>
							<li class="deletepost"><b><span data-icon=""></span> Delete this post</b> </br></li>
						</ul>
	                </div>
	                <div class="media nmt">
	                  <div class="media-left">
	                    <a href="/profile/'.$data['username'].'">
	                      <img width="64" height="64" class="media-object" src="/uploads/thumb_'.$data['profile_picture'].'">
	                    </a>
	                  </div>
	                  <div class="media-body">
	                    <h4 class="media-heading nmb" id="media-heading">'.$data['fullname'].'</h4>
	                    <small class="text-muted">'.$data['created'].'</small><br/>
	                    <span data-type="1" class="post-card-button-inner mr-r '.$data['class_lu'].'"></span> <span data-icon="&#xe051;" class="post-card-button-inner share-btn"></span>
	                  </div>
	                </div>
					<div id="edit_panel" class="panel panel-default mrt10 hidden">
					  <div class="panel-body">
					    <textarea id="edit_text" class="text-area">'.$data['content'].'</textarea>
					  </div>
					  <div class="panel-footer"><button id="done_editing" class="btn btn-primary btn-sm">Done Editing</button> <button id="close_edit" class="btn btn-primary btn-sm">Close</button> </div>
					</div>
					<div id="edit_loader" class="loader loader-inner ball-pulse mrt10 hidden"><div></div><div></div><div></div></div>
	                '.$content.'
	              </div>
					<div class="post-card-footer">
						'.$data['post_card_btns'].'
					</div>
		            <div class="comment-container hidden">
		            	<div class="loader loader-inner ball-pulse hidden"><div></div><div></div><div></div></div>
		            	<p><a id="view_prev_comm" class="'.$data['view_prev_comment'].'" href="javascript:;"><b>View previous comments</b></a></p>
		            	<div class="comments-holder">
		            		'.$data['comments'].'
		                </div>
		                <div class="comment-input">
			                <div class="media nmt">
			                  <div class="media-left">
			                    <a href="/profile/'.$username_session.'">
			                      <img width="38" height="38" class="media-object" src="/uploads/thumb_'.$profile_picture_session.'">
			                    </a>
			                  </div>
			                  <div class="media-body">
			                  	<input id="comment" placeholder="Type a comment.." type="text" class="form-control input-mm"></input>
			                  </div>
			                </div>
		                </div>
		            </div>
	            </div>
	        ';
		}
		else if($type === 2 || $type === "2"){ //comments

			return '
        	<div data-id="'.$data['comment_id'].'" class="comment">
                <div class="media nmt">
                  <div class="media-left">
                    <a href="/profile/'.$data['username'].'">
                      <img width="32" height="32" class="media-object" src="/uploads/thumb_'.$data['profile_picture'].'">
                    </a>
                  </div>
                  <div class="media-body">
                    <h5 class="media-heading nmb" id="media-heading">'.$data['fullname'].' - <small class="text-muted">'.$data['created'].'</small></h5>
                    <p class="nmt nmb comment-data">'.$data['comment'].'</p>
                    <div id="comment_edit_loader" class="loader loader-inner ball-pulse mrt10 hidden"><div></div><div></div><div></div></div>
                    <div class="edit-comment-box hidden">
	                    <ul class="list-inline">
		                    <li>
		                    	<input class="form-control input-sm input-width" type="text" value="'.$data['comment'].'"></input>
		                    </li>
		                    <li>
		                    	<button class="btn btn-sm btn-flat comment-edit-save"><i class="fa fa-check"></i></button> <button class="btn btn-sm btn-flat comment-edit-closer"><i class="fa fa-times"></i></button>
		                    </li>
		                </ul>
	                </div>
                    <p class="nmt"><span data-type="2" class="button-tiny '.$data['is_liked'].' '.$data['like_class'].'" data-icon="&#xe068;"> <i class="text-bold text-primary">'.$data['comment_likes_count'].'</i></span> &nbsp; <span class="button-tiny edit-comment '.$data['delete_edit_class'].'" data-icon="&#xe060;"></span> &nbsp; <span class="button-tiny delete-comment fs15spandeletecom '.$data['delete_edit_class'].'" data-icon="&#xe082;"></span><p>
                  </div>
                </div>
            </div>
			';
		}
		else if($type === 3 || $type === "3"){ //post card buttons
			return '
				<ul class="list-inline margin-top-sm"><li class="'.$data['hasLikes'].'"><span class="btn-flat-post-cards like-span"><i class="fa fa-heart-o"></i> '.$data['likes_count'].'</span></li><li><span id="comments_btn" class="btn-flat-post-cards"><span data-icon="&#xe04a;"></span> '.$data['ncomments'].'</span></li><li><span class="btn-flat-post-cards share"><span data-icon="&#xe051;"></span> <span class="share-text">'.$data['share'].'</span></span></li></ul>
			';
		}
		else if($type === 4 || $type === "4"){ //for profile setup
			if($data['type'] === "schooling"){
				$dom = '<input id="answer" data-name="schooling" class="form-control input-sm" placeholder="Example Spring Field Highschool"></input>';
				$question_text = 'Where did you went for schooling';
				$hidden = '';
			}
			else if($data['type'] === "gender"){
				$dom = '<select id="answer" data-name="gender" class="form-control input-sm"><option>Male</option><option>Female</option><option>Other</option></select>';
				$question_text = 'Please specify your gender';
				$hidden = '';
			}
			else if($data['type'] === "college"){
				$dom = '<input id="answer" data-name="college" class="form-control input-sm" placeholder="Example Hadvard University"></input>';
				$question_text = 'Where did you go for college';
				$hidden = '';
			}
			else if($data['type'] === "location"){
				$dom = '<input id="answer" data-name="location" class="form-control input-sm" placeholder="Where are you now living currently ?"></input>';
				$question_text = 'Where are you living ?';
				$hidden = '';
			}
			else if($data['type'] === "home"){
				$dom = '<input id="answer" data-name="home" class="form-control input-sm" placeholder="Example: New York"></input>';
				$question_text = 'Where is your home town ?';
				$hidden = '';
			}
			else if($data['type'] === "relationship"){
				$dom = '<select id="answer" data-name="relationship" class="form-control input-sm"><option>Single</option><option>In a relationship</option><option>Engaged</option><option>Married</option><option>In a civil partnership</option><option>In a domestic partnership</option><option>In an open relationship</option><option>Complicated</option><option>Separated</option><option>Divorced</option><option>Widowed</option></select>';
				$question_text = 'Whats your relationship status';
			}
			else{
				$dom = '';
				$question_text = '';
				$hidden = 'hidden';
			}

			return array('dom' => $dom, 'question' => $question_text, 'hidden' => $hidden);
		}
		else if($type === 5 || $type === "5"){
			return '
				<div class="buttons-container">
					<button id="upload_from_computer" class="btn btn-transparent-primary btn-lg"><span data-icon="&#xe084;"></span> Upload from computer</button>
					<button class="btn btn-transparent-primary btn-lg"><span data-icon="&#xe07f;"></span> Take a snapshot</button>
					<button class="btn btn-transparent-primary btn-lg"><span data-icon="&#xe05f;"></span> Edit profile picture</button>
				</div>
				<hr class="hr-sm"/>
				<p>or you could pick an avatar</p>
				<div class="slimscroll-avatar">
					<div class="row">
						<div class="col-xs-2">
							<img data-name="boy_1.jpg" class="img-cthumbnail" src="/public/assets/img/avatars/boy_1.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="boy_2.jpg" class="img-cthumbnail" src="/public/assets/img/avatars/boy_2.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="boy_3.jpg" class="img-cthumbnail" src="/public/assets/img/avatars/boy_3.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="boy_4.jpg" class="img-cthumbnail" src="/public/assets/img/avatars/boy_4.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="boy_5.jpg" class="img-cthumbnail" src="/public/assets/img/avatars/boy_5.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="boy_6.jpg" class="img-cthumbnail" src="/public/assets/img/avatars/boy_6.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="female_1.jpg" class="img-cthumbnail" src="/public/assets/img/avatars/female_1.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="female_2.jpg" class="img-cthumbnail" src="/public/assets/img/avatars/female_2.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="female_3.jpg" class="img-cthumbnail" src="/public/assets/img/avatars/female_3.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="female_4.jpg" class="img-cthumbnail" src="/public/assets/img/avatars/female_4.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="female_5.jpg" class="img-cthumbnail" src="/public/assets/img/avatars/female_5.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="female_6.jpg"  class="img-cthumbnail" src="/public/assets/img/avatars/female_6.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="boy_7.jpg" class="img-cthumbnail" src="/public/assets/img/avatars/boy_7.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="boy_8.jpg" class="img-cthumbnail" src="/public/assets/img/avatars/boy_8.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="boy_9.jpg" class="img-cthumbnail" src="/public/assets/img/avatars/boy_9.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="boy_10.jpg" class="img-cthumbnail" src="/public/assets/img/avatars/boy_10.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="boy_11.jpg" class="img-cthumbnail" src="/public/assets/img/avatars/boy_11.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="female_7.jpg" class="img-cthumbnail" src="/public/assets/img/avatars/female_7.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="female_8.jpg" class="img-cthumbnail" src="/public/assets/img/avatars/female_8.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="female_9.jpg" class="img-cthumbnail" src="/public/assets/img/avatars/female_9.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="female_10.jpg" class="img-cthumbnail" src="/public/assets/img/avatars/female_10.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="female_11.jpg" class="img-cthumbnail" src="/public/assets/img/avatars/female_11.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="female_12.jpg" class="img-cthumbnail" src="/public/assets/img/avatars/female_12.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="female_13.jpg"  class="img-cthumbnail" src="/public/assets/img/avatars/female_13.jpg"></img>
						</div>
					</div>
				</div>
			';
		}
		else if($type === "6" || $type === 6){
			return '
				<li data-fullname="'.$data['fullname'].'" data-username="'.$data['username'].'" data-pp="'.$data['profile_picture'].'" data-id="'.$data['id'].'"">
		          <div class="media">
		            <div class="media-left">
		              <a href="/profile/'.$data['username'].'">
		                <img width="32" height="32" class="media-object" src="/uploads/thumb_'.$data['profile_picture'].'">
		              </a>
		            </div>
		            <div class="media-body vam">
		              <h5 class="media-heading nmb mrt3"><b>'.$data['fullname'].'</b> <span class="online-icon-clist pull-right hidden"><i class="fa fa-circle text-success"></i></span></h5>
		            </div>
		          </div>
		        </li>
			';
		}
		else if($type === "7" || $type === 7){

			$body = "";

			switch ($data['type']) {
				case '1':
					$body = '
						<p class="nmb nmt padding-none">
							<h4 class="media-heading small-mh nmb" id="media-heading">'.$data['fullname'].' - '.$data['timestamp'].'</h4>
							<small class="text-muted"><span class="icon-like text-primary"></span> <b>Liked your post</b></small>
						</p>
					';
				break;
				case '2':
					$body = '
						<p class="nmb nmt padding-none">
							<h4 class="media-heading small-mh nmb" id="media-heading">'.$data['fullname'].' - '.$data['timestamp'].'</h4>
							<small class="text-muted"><span class="icon-like text-primary"></span> <b>Commented on your post</b></small>
						</p>
					';
					break;

				case '3':
					$body = '
						<p class="pull-right">
						 <button data-origin="notification" data-id="'.$data['id'].'" data-type="11" class="btn btn-sm btn-flat friend"><i class="fa fa-check"></i></button>
						 <button data-origin="notification" data-id="'.$data['id'].'" data-type="22" class="btn btn-sm btn-flat friend"><i class="fa fa-times"></i></button>
						</p>
						<p class="nmb nmt padding-none">
							<h4 class="media-heading small-mh nmb" id="media-heading">'.$data['fullname'].'</h4>
							<small class="text-muted"><span class="icon-user-follow text-success"></span> <b>New friend request</b></small>
						</p>
					';
				break;

				case '4':
					$body = '
						<p class="nmb nmt padding-none">
							<h4 class="media-heading small-mh nmb" id="media-heading">'.$data['fullname'].' - '.$data['timestamp'].'</h4>
							<small class="text-muted"><span class="icon-like text-primary"></span> <b>Accepted your friend request</b></small>
						</p>
					';
				break;

				case '5':
					$body = '
						<p class="nmb nmt padding-none">
							<h4 class="media-heading small-mh nmb" id="media-heading">'.$data['fullname'].' - '.$data['timestamp'].'</h4>
							<small class="text-muted"><span class="icon-like text-primary"></span> <b>Started following you</b></small>
						</p>
					';
				break;

					case '6':
						$body = '
							<p class="nmb nmt padding-none">
							<h4 class="media-heading small-mh nmb" id="media-heading">'.$data['fullname'].' - '.$data['timestamp'].'</h4>
								<small class="text-muted"><span class="icon-like text-primary"></span> <b>Liked your comment</b></small>
							</p>
						';
					break;

					case '7':
						$body = '
							<p class="nmb nmt padding-none">
							<h4 class="media-heading small-mh nmb" id="media-heading">'.$data['fullname'].' - '.$data['timestamp'].'</h4>
								<small class="text-muted"><span class="icon-like text-primary"></span> <b>Shared your post</b></small>
							</p>
						';
					break;
			}

			return '
				<li class="li-notif">
					<a href="#">
						<div class="media nmt">
							<div class="media-left">
								<span>
									<img width="32" height="32" class="media-object" src="/uploads/thumb_'.$data['profile_picture'].'">
								</span>
							</div>
							<div class="media-body notif-mediabody">
								'.$body.'
							</div>
						</div>
					</a>
				</li>
			';
		}
		else if($type === "8" || $type === 8){
			return '
				<li class="li-notif">
					<a href="/profile/'.$data['username'].'">
						<div class="media nmt">
							<div class="media-left">
								<span>
									<img width="32" height="32" class="media-object" src="/uploads/thumb_'.$data['profile_picture'].'">
								</span>
							</div>
							<div class="media-body notif-mediabody">
								<p class="nmb nmt padding-none">
								<h4 class="media-heading small-mh nmb" id="media-heading">'.$data['fullname'].'</h4>
									<small class="text-muted"><b>'.$data['college'].'</b></small>
								</p>
							</div>
						</div>
					</a>
				</li>
			';
		}
		else if($type === "9" || $type === 9){
			return '
				<div data-username="'.$data['username'].'" data-id="'.$data['id'].'" class="contact">
					<div class="media nmt">
						<div class="media-left">
							<span>
								<img width="42" height="42" class="media-object" src="/uploads/thumb_'.$data['profile_picture'].'">
							</span>
						</div>
						<div class="media-body notif-mediabody">
							<p class="nmb nmt padding-none">
							<h4 class="media-heading small-mh nmb" id="media-heading">'.$data['fullname'].'</h4>
								<small class="text-muted"><b>'.$data['college'].'</b></small>
							</p>
						</div>
					</div>
	            </div>
			';
		}
		else if($type === "10" || $type === 10){

			if($data['isOutbound']){
				return '
					<div class="row margin-bottom-sm">
						<div class="message-outgoing margin-bottom-md">
								<div class="message-box wa pull-right">
										<div class="message-out fs15">
												'.$data['message'].' <small class="mrl-sm fs10 text-muted">'.$data['timestamp'].'</small>
										</div>
								</div>
						</div>
					</div>
					';
			}
			else {
				return '
					<div class="row margin-bottom-sm">
						<div class="message-incomming">
				            <div class="message-box">
				                <div class="row">
				                    <div class="col-md-2">
				                        <img class="media-object img-circle" data-src="holder.js/64x64" alt="64x64" src="/uploads/thumb_'.$data['profile_picture'].'" data-holder-rendered="true" style="width: 54px; height: 54px;">
				                    </div>
				                    <div class="col-md-10 wa">
				                        <div class="message-in fs15">
				                            '.$data['message'].' <small class="mrl-sm fs10 text-muted">'.$data['timestamp'].'</small>
				                        </div>
				                    </div>
				                </div>
				            </div>
				      	</div>
					</div>
				';
			}
		}
		else if($type === "11" || $type === 11){
			return '
				<div data-username="'.$data['username'].'" data-id="'.$data['id'].'" class="contact">
					<div class="media nmt">
						<div class="media-left">
							<span>
								<img width="42" height="42" class="media-object" src="/uploads/thumb_'.$data['profile_picture'].'">
							</span>
						</div>
						<div class="media-body notif-mediabody"> <span class="fs10 text-muted pull-right">'.$data['timestamp'].'</span>
							<p class="nmb nmt padding-none">
							<h4 class="media-heading small-mh nmb" id="media-heading">'.$data['fullname'].'</h4>
								<small class="text-muted"><b>'.$data['message'].'</b></small>
							</p>
						</div>
					</div>
	            </div>
			';
		}
		else if($type === "12" || $type === 12){
			return '
				<div data-username="'.$data['username'].'" class="contact-modal">
					<div class="media nmt">
						<div class="media-left">
							<span>
								<img width="42" height="42" class="media-object" src="/uploads/thumb_'.$data['profile_picture'].'">
							</span>
						</div>
						<div class="media-body notif-mediabody">
							<p class="nmb nmt padding-none">
							<h4 class="media-heading small-mh nmb" id="media-heading">'.$data['fullname'].'</h4>
								<small class="text-muted"><b>'.$data['college'].'</b></small> <a href="/inbox/'.$data['username'].'" class="btn btn-transparent-primary btn-sm pull-right">Message</a>
							</p>
						</div>
					</div>
	            </div>
			';
		}
		else if($type === "13" || $type === 13){
			return '
				<div style="right:'.$data['right'].'px !important;" data-username="'.$data['username'].'" data-fullname="'.$data['fullname'].'" class="chat-box-outer">
				    <div class="well chat-box-head nmb">
						<button id="close-chat-box" type="button" class="close" data-dismiss="alert" aria-label="Close">
						  <span aria-hidden="true" class="text-black">&times;</span>
						</button>
				        <div class="media nmt">
				          <div class="media-left hidden">
				            <a href="/profile/'.$data['username'].'">
				              <img id="inbox-whois" class="media-object img-circle" style="width:28px;height:28px;" src="" alt="...">
				            </a>
				          </div>
				          <div class="media-body">
				            <h5 id="inbox-whois-fullname" class="media-heading fwb nmb"><span class="online-icon hidden"><i class="fa fa-circle text-success"></i></span> '.$data['fullname'].'</h5>
				          </div>
				        </div>
				    </div>
				    <div class="well chat-box-inner nmb">
				    	'.$data['messages'].'
				    </div>
				    <div class="well chat-box-well-input">
				    	<div class="emoj-modal hidden">
					      <h4 class="nmt margin-top-sm"><b>People</b></h4>
					      <p>
					            :bowtie:
					            :smile:
					            :laughing:
					            :blush:
					            :smiley:
					            :relaxed:
					            :smirk:
					            :heart_eyes:
					            :kissing_heart:
					            :kissing_closed_eyes:
					            :flushed:
					            :relieved:
					            :satisfied:
					            :grin:
					            :wink:
					            :stuck_out_tongue_winking_eye:
					            :stuck_out_tongue_closed_eyes:
					            :grinning:
					            :kissing:
					            :kissing_smiling_eyes:
					            :stuck_out_tongue:
					            :sleeping:
					            :worried:
					            :frowning:
					            :anguished:
					            :open_mouth:
					            :grimacing:
					            :confused:
					            :hushed:
					            :expressionless:
					            :unamused:
					            :sweat_smile:
					            :sweat:
					            :weary:
					            :pensive:
					            :disappointed:
					            :confounded:
					            :fearful:
					            :cold_sweat:
					            :persevere:
					            :cry:
					            :sob:
					            :joy:
					            :astonished:
					            :scream:
					            :neckbeard:
					            :tired_face:
					            :angry:
					            :rage:
					            :triumph:
					            :sleepy:
					            :yum:
					            :mask:
					            :sunglasses:
					            :dizzy_face:
					            :imp:
					            :smiling_imp:
					            :neutral_face:
					            :no_mouth:
					            :innocent:
					            :alien:
					            :yellow_heart:
					            :blue_heart:
					            :purple_heart:
					            :heart:
					            :green_heart:
					            :broken_heart:
					            :heartbeat:
					            :heartpulse:
					            :two_hearts:
					            :revolving_hearts:
					            :cupid:
					            :sparkling_heart:
					            :sparkles:
					            :star:
					            :star2:
					            :dizzy:
					            :boom:
					            :collision:
					            :anger:
					            :exclamation:
					            :question:
					            :grey_exclamation:
					            :grey_question:
					            :zzz:
					            :dash:
					            :sweat_drops:
					            :notes:
					            :musical_note:
					            :fire:
					            :hankey:
					            :poop:
					            :shit:
					            :+1:
					            :thumbsup:
					            :-1:
					            :thumbsdown:
					            :ok_hand:
					            :punch:
					            :facepunch:
					            :fist:
					            :v:
					            :wave:
					            :hand:
					            :open_hands:
					            :point_up:
					            :point_down:
					            :point_left:
					            :point_right:
					            :raised_hands:
					            :pray:
					            :point_up_2:
					            :clap:
					            :muscle:
					            :metal:
					            :walking:
					            :runner:
					            :running:
					            :couple:
					            :family:
					            :two_men_holding_hands:
					            :two_women_holding_hands:
					            :dancer:
					            :dancers:
					            :ok_woman:
					            :no_good:
					            :information_desk_person:
					            :raised_hand:
					            :bride_with_veil:
					            :person_with_pouting_face:
					            :person_frowning:
					            :bow:
					            :couplekiss:
					            :couple_with_heart:
					            :massage:
					            :haircut:
					            :nail_care:
					            :boy:
					            :girl:
					            :woman:
					            :man:
					            :baby:
					            :older_woman:
					            :older_man:
					            :person_with_blond_hair:
					            :man_with_gua_pi_mao:
					            :man_with_turban:
					            :construction_worker:
					            :cop:
					            :angel:
					            :princess:
					            :smiley_cat:
					            :smile_cat:
					            :heart_eyes_cat:
					            :kissing_cat:
					            :smirk_cat:
					            :scream_cat:
					            :crying_cat_face:
					            :joy_cat:
					            :pouting_cat:
					            :japanese_ogre:
					            :japanese_goblin:
					            :see_no_evil:
					            :hear_no_evil:
					            :speak_no_evil:
					            :guardsman:
					            :skull:
					            :feet:
					            :lips:
					            :kiss:
					            :droplet:
					            :ear:
					            :eyes:
					            :nose:
					            :tongue:
					            :love_letter:
					            :bust_in_silhouette:
					            :busts_in_silhouette:
					            :speech_balloon:
					            :thought_balloon:
					            :feelsgood:
					            :finnadie:
					            :goberserk:
					            :godmode:
					            :hurtrealbad:
					            :rage1:
					            :rage2:
					            :rage3:
					            :rage4:
					            :suspect:
					            :trollface:
					      </p>
					      <h4 class="nmt margin-top-sm"><b>Nature</b></h4>
					      <p>
					            :sunny:
					            :umbrella:
					            :cloud:
					            :snowflake:
					            :snowman:
					            :zap:
					            :cyclone:
					            :foggy:
					            :ocean:
					            :cat:
					            :dog:
					            :mouse:
					            :hamster:
					            :rabbit:
					            :wolf:
					            :frog:
					            :tiger:
					            :koala:
					            :bear:
					            :pig:
					            :pig_nose:
					            :cow:
					            :boar:
					            :monkey_face:
					            :monkey:
					            :horse:
					            :racehorse:
					            :camel:
					            :sheep:
					            :elephant:
					            :panda_face:
					            :snake:
					            :bird:
					            :baby_chick:
					            :hatched_chick:
					            :hatching_chick:
					            :chicken:
					            :penguin:
					            :turtle:
					            :bug:
					            :honeybee:
					            :ant:
					            :beetle:
					            :snail:
					            :octopus:
					            :tropical_fish:
					            :fish:
					            :whale:
					            :whale2:
					            :dolphin:
					            :cow2:
					            :ram:
					            :rat:
					            :water_buffalo:
					            :tiger2:
					            :rabbit2:
					            :dragon:
					            :goat:
					            :rooster:
					            :dog2:
					            :pig2:
					            :mouse2:
					            :ox:
					            :dragon_face:
					            :blowfish:
					            :crocodile:
					            :dromedary_camel:
					            :leopard:
					            :cat2:
					            :poodle:
					            :paw_prints:
					            :bouquet:
					            :cherry_blossom:
					            :tulip:
					            :four_leaf_clover:
					            :rose:
					            :sunflower:
					            :hibiscus:
					            :maple_leaf:
					            :leaves:
					            :fallen_leaf:
					            :herb:
					            :mushroom:
					            :cactus:
					            :palm_tree:
					            :evergreen_tree:
					            :deciduous_tree:
					            :chestnut:
					            :seedling:
					            :blossom:
					            :ear_of_rice:
					            :shell:
					            :globe_with_meridians:
					            :sun_with_face:
					            :full_moon_with_face:
					            :new_moon_with_face:
					            :new_moon:
					            :waxing_crescent_moon:
					            :first_quarter_moon:
					            :waxing_gibbous_moon:
					            :full_moon:
					            :waning_gibbous_moon:
					            :last_quarter_moon:
					            :waning_crescent_moon:
					            :last_quarter_moon_with_face:
					            :first_quarter_moon_with_face:
					            :moon:
					            :earth_africa:
					            :earth_americas:
					            :earth_asia:
					            :volcano:
					            :milky_way:
					            :partly_sunny:
					            :octocat:
					            :squirrel:
					      </p>
					      <h4 class="nmt margin-top-sm"><b>Objects</b></h4>
					      <p>
					            :bamboo:
					            :gift_heart:
					            :dolls:
					            :school_satchel:
					            :mortar_board:
					            :flags:
					            :fireworks:
					            :sparkler:
					            :wind_chime:
					            :rice_scene:
					            :jack_o_lantern:
					            :ghost:
					            :santa:
					            :christmas_tree:
					            :gift:
					            :bell:
					            :no_bell:
					            :tanabata_tree:
					            :tada:
					            :confetti_ball:
					            :balloon:
					            :crystal_ball:
					            :cd:
					            :dvd:
					            :floppy_disk:
					            :camera:
					            :video_camera:
					            :movie_camera:
					            :computer:
					            :tv:
					            :iphone:
					            :phone:
					            :telephone:
					            :telephone_receiver:
					            :pager:
					            :fax:
					            :minidisc:
					            :vhs:
					            :sound:
					            :speaker:
					            :mute:
					            :loudspeaker:
					            :mega:
					            :hourglass:
					            :hourglass_flowing_sand:
					            :alarm_clock:
					            :watch:
					            :radio:
					            :satellite:
					            :loop:
					            :mag:
					            :mag_right:
					            :unlock:
					            :lock:
					            :lock_with_ink_pen:
					            :closed_lock_with_key:
					            :key:
					            :bulb:
					            :flashlight:
					            :high_brightness:
					            :low_brightness:
					            :electric_plug:
					            :battery:
					            :calling:
					            :email:
					            :mailbox:
					            :postbox:
					            :bath:
					            :bathtub:
					            :shower:
					            :toilet:
					            :wrench:
					            :nut_and_bolt:
					            :hammer:
					            :seat:
					            :moneybag:
					            :yen:
					            :dollar:
					            :pound:
					            :euro:
					            :credit_card:
					            :money_with_wings:
					            :e-mail:
					            :inbox_tray:
					            :outbox_tray:
					            :envelope:
					            :incoming_envelope:
					            :postal_horn:
					            :mailbox_closed:
					            :mailbox_with_mail:
					            :mailbox_with_no_mail:
					            :door:
					            :smoking:
					            :bomb:
					            :gun:
					            :hocho:
					            :pill:
					            :syringe:
					            :page_facing_up:
					            :page_with_curl:
					            :bookmark_tabs:
					            :bar_chart:
					            :chart_with_upwards_trend:
					            :chart_with_downwards_trend:
					            :scroll:
					            :clipboard:
					            :calendar:
					            :date:
					            :card_index:
					            :file_folder:
					            :open_file_folder:
					            :scissors:
					            :pushpin:
					            :paperclip:
					            :black_nib:
					            :pencil2:
					            :straight_ruler:
					            :triangular_ruler:
					            :closed_book:
					            :green_book:
					            :blue_book:
					            :orange_book:
					            :notebook:
					            :notebook_with_decorative_cover:
					            :ledger:
					            :books:
					            :bookmark:
					            :name_badge:
					            :microscope:
					            :telescope:
					            :newspaper:
					            :football:
					            :basketball:
					            :soccer:
					            :baseball:
					            :tennis:
					            :8ball:
					            :rugby_football:
					            :bowling:
					            :golf:
					            :mountain_bicyclist:
					            :bicyclist:
					            :horse_racing:
					            :snowboarder:
					            :swimmer:
					            :surfer:
					            :ski:
					            :spades:
					            :hearts:
					            :clubs:
					            :diamonds:
					            :gem:
					            :ring:
					            :trophy:
					            :musical_score:
					            :musical_keyboard:
					            :violin:
					            :space_invader:
					            :video_game:
					            :black_joker:
					            :flower_playing_cards:
					            :game_die:
					            :dart:
					            :mahjong:
					            :clapper:
					            :memo:
					            :pencil:
					            :book:
					            :art:
					            :microphone:
					            :headphones:
					            :trumpet:
					            :saxophone:
					            :guitar:
					            :shoe:
					            :sandal:
					            :high_heel:
					            :lipstick:
					            :boot:
					            :shirt:
					            :tshirt:
					            :necktie:
					            :womans_clothes:
					            :dress:
					            :running_shirt_with_sash:
					            :jeans:
					            :kimono:
					            :bikini:
					            :ribbon:
					            :tophat:
					            :crown:
					            :womans_hat:
					            :mans_shoe:
					            :closed_umbrella:
					            :briefcase:
					            :handbag:
					            :pouch:
					            :purse:
					            :eyeglasses:
					            :fishing_pole_and_fish:
					            :coffee:
					            :tea:
					            :sake:
					            :baby_bottle:
					            :beer:
					            :beers:
					            :cocktail:
					            :tropical_drink:
					            :wine_glass:
					            :fork_and_knife:
					            :pizza:
					            :hamburger:
					            :fries:
					            :poultry_leg:
					            :meat_on_bone:
					            :spaghetti:
					            :curry:
					            :fried_shrimp:
					            :bento:
					            :sushi:
					            :fish_cake:
					            :rice_ball:
					            :rice_cracker:
					            :rice:
					            :ramen:
					            :stew:
					            :oden:
					            :dango:
					            :egg:
					            :bread:
					            :doughnut:
					            :custard:
					            :icecream:
					            :ice_cream:
					            :shaved_ice:
					            :birthday:
					            :cake:
					            :cookie:
					            :chocolate_bar:
					            :candy:
					            :lollipop:
					            :honey_pot:
					            :apple:
					            :green_apple:
					            :tangerine:
					            :lemon:
					            :cherries:
					            :grapes:
					            :watermelon:
					            :strawberry:
					            :peach:
					            :melon:
					            :banana:
					            :pear:
					            :pineapple:
					            :sweet_potato:
					            :eggplant:
					            :tomato:
					            :corn:
					      </p>
					      <h4 class="nmt margin-top-sm"><b>Places</b></h4>
					      <p>
					            :109:
					            :house:
					            :house_with_garden:
					            :school:
					            :office:
					            :post_office:
					            :hospital:
					            :bank:
					            :convenience_store:
					            :love_hotel:
					            :hotel:
					            :wedding:
					            :church:
					            :department_store:
					            :european_post_office:
					            :city_sunrise:
					            :city_sunset:
					            :japanese_castle:
					            :european_castle:
					            :tent:
					            :factory:
					            :tokyo_tower:
					            :japan:
					            :mount_fuji:
					            :sunrise_over_mountains:
					            :sunrise:
					            :stars:
					            :statue_of_liberty:
					            :bridge_at_night:
					            :carousel_horse:
					            :rainbow:
					            :ferris_wheel:
					            :fountain:
					            :roller_coaster:
					            :ship:
					            :speedboat:
					            :boat:
					            :sailboat:
					            :rowboat:
					            :anchor:
					            :rocket:
					            :airplane:
					            :helicopter:
					            :steam_locomotive:
					            :tram:
					            :mountain_railway:
					            :bike:
					            :aerial_tramway:
					            :suspension_railway:
					            :mountain_cableway:
					            :tractor:
					            :blue_car:
					            :oncoming_automobile:
					            :car:
					            :red_car:
					            :taxi:
					            :oncoming_taxi:
					            :articulated_lorry:
					            :bus:
					            :oncoming_bus:
					            :rotating_light:
					            :police_car:
					            :oncoming_police_car:
					            :fire_engine:
					            :ambulance:
					            :minibus:
					            :truck:
					            :train:
					            :station:
					            :train2:
					            :bullettrain_front:
					            :bullettrain_side:
					            :light_rail:
					            :monorail:
					            :railway_car:
					            :trolleybus:
					            :ticket:
					            :fuelpump:
					            :vertical_traffic_light:
					            :traffic_light:
					            :warning:
					            :construction:
					            :beginner:
					            :atm:
					            :slot_machine:
					            :busstop:
					            :barber:
					            :hotsprings:
					            :checkered_flag:
					            :crossed_flags:
					            :izakaya_lantern:
					            :moyai:
					            :circus_tent:
					            :performing_arts:
					            :round_pushpin:
					            :triangular_flag_on_post:
					            :jp:
					            :kr:
					            :cn:
					            :us:
					            :fr:
					            :es:
					            :it:
					            :ru:
					            :gb:
					            :uk:
					            :de:
					      </p>
					      <h4 class="nmt margin-top-sm"><b>Symbols</b></h4>
					      <p>
					            :one:
					            :two:
					            :three:
					            :four:
					            :five:
					            :six:
					            :seven:
					            :eight:
					            :nine:
					            :keycap_ten:
					            :1234:
					            :zero:
					            :hash:
					            :symbols:
					            :arrow_backward:
					            :arrow_down:
					            :arrow_forward:
					            :arrow_left:
					            :capital_abcd:
					            :abcd:
					            :abc:
					            :arrow_lower_left:
					            :arrow_lower_right:
					            :arrow_right:
					            :arrow_up:
					            :arrow_upper_left:
					            :arrow_upper_right:
					            :arrow_double_down:
					            :arrow_double_up:
					            :arrow_down_small:
					            :arrow_heading_down:
					            :arrow_heading_up:
					            :leftwards_arrow_with_hook:
					            :arrow_right_hook:
					            :left_right_arrow:
					            :arrow_up_down:
					            :arrow_up_small:
					            :arrows_clockwise:
					            :arrows_counterclockwise:
					            :rewind:
					            :fast_forward:
					            :information_source:
					            :ok:
					            :twisted_rightwards_arrows:
					            :repeat:
					            :repeat_one:
					            :new:
					            :top:
					            :up:
					            :cool:
					            :free:
					            :ng:
					            :cinema:
					            :koko:
					            :signal_strength:
					            :u5272:
					            :u5408:
					            :u55b6:
					            :u6307:
					            :u6708:
					            :u6709:
					            :u6e80:
					            :u7121:
					            :u7533:
					            :u7a7a:
					            :u7981:
					            :sa:
					            :restroom:
					            :mens:
					            :womens:
					            :baby_symbol:
					            :no_smoking:
					            :parking:
					            :wheelchair:
					            :metro:
					            :baggage_claim:
					            :accept:
					            :wc:
					            :potable_water:
					            :put_litter_in_its_place:
					            :secret:
					            :congratulations:
					            :m:
					            :passport_control:
					            :left_luggage:
					            :customs:
					            :ideograph_advantage:
					            :cl:
					            :sos:
					            :id:
					            :no_entry_sign:
					            :underage:
					            :no_mobile_phones:
					            :do_not_litter:
					            :non-potable_water:
					            :no_bicycles:
					            :no_pedestrians:
					            :children_crossing:
					            :no_entry:
					            :eight_spoked_asterisk:
					            :eight_pointed_black_star:
					            :heart_decoration:
					            :vs:
					            :vibration_mode:
					            :mobile_phone_off:
					            :chart:
					            :currency_exchange:
					            :aries:
					            :taurus:
					            :gemini:
					            :cancer:
					            :leo:
					            :virgo:
					            :libra:
					            :scorpius:
					            :sagittarius:
					            :capricorn:
					            :aquarius:
					            :pisces:
					            :ophiuchus:
					            :six_pointed_star:
					            :negative_squared_cross_mark:
					            :a:
					            :b:
					            :ab:
					            :o2:
					            :diamond_shape_with_a_dot_inside:
					            :recycle:
					            :end:
					            :on:
					            :soon:
					            :clock1:
					            :clock130:
					            :clock10:
					            :clock1030:
					            :clock11:
					            :clock1130:
					            :clock12:
					            :clock1230:
					            :clock2:
					            :clock230:
					            :clock3:
					            :clock330:
					            :clock4:
					            :clock430:
					            :clock5:
					            :clock530:
					            :clock6:
					            :clock630:
					            :clock7:
					            :clock730:
					            :clock8:
					            :clock830:
					            :clock9:
					            :clock930:
					            :heavy_dollar_sign:
					            :copyright:
					            :registered:
					            :tm:
					            :x:
					            :heavy_exclamation_mark:
					            :bangbang:
					            :interrobang:
					            :o:
					            :heavy_multiplication_x:
					            :heavy_plus_sign:
					            :heavy_minus_sign:
					            :heavy_division_sign:
					            :white_flower:
					            :100:
					            :heavy_check_mark:
					            :ballot_box_with_check:
					            :radio_button:
					            :link:
					            :curly_loop:
					            :wavy_dash:
					            :part_alternation_mark:
					            :trident:
					            :black_square:
					            :white_square:
					            :white_check_mark:
					            :black_square_button:
					            :white_square_button:
					            :black_circle:
					            :white_circle:
					            :red_circle:
					            :large_blue_circle:
					            :large_blue_diamond:
					            :large_orange_diamond:
					            :small_blue_diamond:
					            :small_orange_diamond:
					            :small_red_triangle:
					            :small_red_triangle_down:
					            :shipit:
					      </p>
				    	</div>
				        <input class="chat-box-input" placeholder="Type a message..."/><button class="btn btn-xs btn-primary btn-tiny-emoj"><i class="fa fa-smile-o"></i></button>
				    </div>
				</div>
			';
		}
		else if($type === "14" || $type === 14){

			if($data['isOutbound']){
				return
					'<div class="row margin-bottom-sm">
						<div class="chat-outgoing margin-bottom-md">
							<div class="chat-message-box wa pull-right">
								<div class="chat-out fs15">
									<span>'.$data['message'].'</span> <br/>
									<small class="fs10 text-muted">'.$data['timestamp'].'</small>
								</div>
							</div>
						</div>
					</div>';
			}
			else {
				return '
				<div class="row margin-bottom-sm">
					<div class="chat-incomming">
			           <div class="chat-message-box">
			                <div class="row">
			                    <div class="col-md-1">
			                        <img class="media-object img-circle" data-src="holder.js/64x64" alt="64x64" src="/uploads/thumb_'.$data['profile_picture'].'" data-holder-rendered="true" style="width: 34px; height: 34px;">
			                    </div>
			                    <div class="col-md-10 wa">
			                        <div class="chat-in fs15">
			                            <span>'.$data['message'].'</span> <br/>
			                            <small class="fs10 text-muted">'.$data['timestamp'].'</small>
			                        </div>
			                    </div>
			                </div>
			            </div>
			      	</div>
				</div>
				';
			}
		}
		else if($type === "15" || $type === 15){
			$img_list = '';
			foreach($data['images'] as $images){
				$img_list .= '<div class="item photo-album-item"><span class="overlay-photos-delete-btn"> &times; </span> <img width="100" class="feedPhotos" data-id="'.$images['feed_id'].'" data-img="'.$images['image'].'" src="/uploads/'.$images['image'].'"></img></div>';
			}

			return '
				<div class="img-collage-div">
					'.$img_list.'
				</div>
				';
		}
		else if($type === "16" || $type === 16){
			if($data['album_data'] -> album_title == ''){
				$data['album_data'] -> album_title = 'Untitled Album';
			}
			$album_html = '
			  <div class="col-sm-6 col-md-4">
			    <div class="thumbnail">
			    	<a href="/profile/'.$data['username'].'/albums/'.$data['album_data'] -> id.'" class="">
			      		<img style="height: 200px;width: 100%;" src="/uploads/thumb_'.$data['image'].'" alt="...">
			    	</a>
			      <div class="caption">
	              	<div id="delete_loader" class="loader loader-inner ball-pulse pull-right hidden"><div></div><div></div><div></div></div>
					<div id="post_dropdown" class="btn-group pull-right">
	                  <button type="button" class="close font-size-18 text-muted dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
	                    <span class="fa fa-chevron-circle-down text-muted"></span>
	                  </button>
						<ul class="dropdown-menu post-card-dropdown" role="menu">
							<li data-id="'.$data['album_data'] -> feed_id.'" class="delete-album"><b><span data-icon=""></span> Delete this album</b> </br></li>
						</ul>
	                </div>
			        <h3 class="nmt nmb">'.$data['album_data'] -> album_title.'</h3>
			        <p>'.$data['album_data'] -> album_desc.'</p>
			      </div>
			    </div>
			  </div>
			';

			return $album_html;
		}
		else if($type === "17" || $type === 17){
			if(!$data['album_title']){
				$data['album_title'] = 'Untitled Album';
			}

			$img_list = '';
			foreach($data['images'] as $images){
				$img_list .= '<div class="item photo-album-item"><span class="overlay-photos-delete-btn"> &times; </span> <img width="100" class="feedPhotos" data-id="'.$images['feed_id'].'" data-img="'.$images['image'].'" src="/uploads/'.$images['image'].'"></img></div>';
			}

			return '
				<div class="img-collage-div">
					<h3 class="nmt">'.$data['album_title'].'</h3>
					<p>'.$data['album_desc'].'</p>
					'.$img_list.'
				</div>
				';
		}
	}
}
