<?php

class Htmlfactory {

	//insert post
	public static function bake_html($type, $data){

		/*
		* type = 1 -> for baking posts
		* type = 2 -> for comments html data
		* type = 3 -> for baking post card buttons
		* type = 4 -> for profile setup
		* type = 5 -> for generating profile picture change modal
		*/

		$base_path = 'http://localhost'; //change it to site url

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
			else if($data['type'] === "3" || $data['type'] === 3){ //photo feed
				$img_list = '';
				$counter  = 4;
				foreach($data['images'] as $images){
					$img_list .= '<div class="item"><img src="'.$base_path.'/uploads/'.$images.'"></img></div>';

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
			return '
	            <div data-id="'.$data['feed_id'].'" class="post-container">
	              <div class="post-cards">
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
	                    <a href="'.$base_path.'/profile/'.$data['username'].'">
	                      <img width="64" height="64" class="media-object" src="'.$base_path.'/uploads/'.$data['profile_picture'].'">
	                    </a>
	                  </div>
	                  <div class="media-body">
	                    <h4 class="media-heading nmb" id="media-heading">'.$data['fullname'].'</h4>
	                    <small class="text-muted">'.$data['created'].'</small><br/>
	                    <span data-type="1" class="post-card-button-inner mr-r '.$data['class_lu'].'"></span> <span data-icon="&#xe051;" class="post-card-button-inner"></span>
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
		            	<p><a id="view_prev_comm" class="'.$data['view_prev_comment'].'" href="javascript:;">View previous comments</a></p>
		            	<div class="comments-holder">
		            		'.$data['comments'].'
		                </div>
		                <div class="comment-input">
			                <div class="media nmt">
			                  <div class="media-left">
			                    <a href="#">
			                      <img width="38" height="38" class="media-object" src="public/assets/img/avatars/man_2.jpg">
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
                    <a href="#">
                      <img width="32" height="32" class="media-object" src="public/assets/img/avatars/man_2.jpg">
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
                    <p class="nmt"><span data-type="2" class="button-tiny '.$data['is_liked'].' '.$data['like_class'].'" data-icon="&#xe068;"> <i class="text-bold text-primary">'.$data['comment_likes_count'].'</i></span> &nbsp; <span class="button-tiny" data-icon="&#xe051;"></span> &nbsp; <span class="button-tiny edit-comment '.$data['delete_edit_class'].'" data-icon="&#xe060;"></span> &nbsp; <span class="button-tiny delete-comment fs15spandeletecom '.$data['delete_edit_class'].'" data-icon="&#xe082;"></span><p>
                  </div>
                </div>
            </div>
			';	
		}
		else if($type === 3 || $type === "3"){ //post card buttons
			return '
				<ul class="list-inline no-margin-bottom"><li><img width="32" height="32" src="public/assets/img/avatars/man_1.jpg"></img></li><li><img width="32" height="32" src="public/assets/img/avatars/man_2.jpg"></img></li><li><img width="32" height="32" src="public/assets/img/avatars/real_female1.jpg"></img></li><li><span class="no-likes">23 more likes</span></li><li><span id="comments_btn" class="span-comments no-likes">'.$data['ncomments'].'</span></li><li><span class="no-likes">21 Shares</span></li></ul>
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
							<img data-name="boy_1.jpg" class="img-cthumbnail" src="'.$base_path.'/public/assets/img/avatars/boy_1.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="boy_2.jpg" class="img-cthumbnail" src="'.$base_path.'/public/assets/img/avatars/boy_2.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="boy_3.jpg" class="img-cthumbnail" src="'.$base_path.'/public/assets/img/avatars/boy_3.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="boy_4.jpg" class="img-cthumbnail" src="'.$base_path.'/public/assets/img/avatars/boy_4.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="boy_5.jpg" class="img-cthumbnail" src="'.$base_path.'/public/assets/img/avatars/boy_5.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="boy_6.jpg" class="img-cthumbnail" src="'.$base_path.'/public/assets/img/avatars/boy_6.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="female_1.jpg" class="img-cthumbnail" src="'.$base_path.'/public/assets/img/avatars/female_1.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="female_2.jpg" class="img-cthumbnail" src="'.$base_path.'/public/assets/img/avatars/female_2.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="female_3.jpg" class="img-cthumbnail" src="'.$base_path.'/public/assets/img/avatars/female_3.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="female_4.jpg" class="img-cthumbnail" src="'.$base_path.'/public/assets/img/avatars/female_4.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="female_5.jpg" class="img-cthumbnail" src="'.$base_path.'/public/assets/img/avatars/female_5.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="female_6.jpg"  class="img-cthumbnail" src="'.$base_path.'/public/assets/img/avatars/female_6.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="boy_7.jpg" class="img-cthumbnail" src="'.$base_path.'/public/assets/img/avatars/boy_7.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="boy_8.jpg" class="img-cthumbnail" src="'.$base_path.'/public/assets/img/avatars/boy_8.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="boy_9.jpg" class="img-cthumbnail" src="'.$base_path.'/public/assets/img/avatars/boy_9.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="boy_10.jpg" class="img-cthumbnail" src="'.$base_path.'/public/assets/img/avatars/boy_10.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="boy_11.jpg" class="img-cthumbnail" src="'.$base_path.'/public/assets/img/avatars/boy_11.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="female_7.jpg" class="img-cthumbnail" src="'.$base_path.'/public/assets/img/avatars/female_7.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="female_8.jpg" class="img-cthumbnail" src="'.$base_path.'/public/assets/img/avatars/female_8.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="female_9.jpg" class="img-cthumbnail" src="'.$base_path.'/public/assets/img/avatars/female_9.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="female_10.jpg" class="img-cthumbnail" src="'.$base_path.'/public/assets/img/avatars/female_10.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="female_11.jpg" class="img-cthumbnail" src="'.$base_path.'/public/assets/img/avatars/female_11.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="female_12.jpg" class="img-cthumbnail" src="'.$base_path.'/public/assets/img/avatars/female_12.jpg"></img>
						</div>
						<div class="col-xs-2">
							<img data-name="female_13.jpg"  class="img-cthumbnail" src="'.$base_path.'/public/assets/img/avatars/female_13.jpg"></img>
						</div>
					</div>
				</div>
			';
		}
	}
}
