<?php

class Htmlfactory {

	//insert post
	public static function bake_html($type, $data){

		/*
		* type = 1 -> for baking posts
		* type = 2 -> for comments html data
		* type = 3 -> for baking post card buttons
		*/

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
	                    <a href="#">
	                      <img width="64" height="64" class="media-object" src="public/assets/img/avatars/man_2.jpg">
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
                    <p class="nmt"><span data-type="2" class="button-tiny '.$data['is_liked'].' '.$data['like_class'].'" data-icon="&#xe068;"> <i class="text-bold text-primary">'.$data['comment_likes_count'].'</i></span> &nbsp; <span class="button-tiny" data-icon="&#xe051;"></span> &nbsp; <span class="button-tiny edit-comment" data-icon="&#xe060;"></span> &nbsp; <span class="button-tiny delete-comment fs15spandeletecom" data-icon="&#xe082;"></span><p>
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
	}
}
