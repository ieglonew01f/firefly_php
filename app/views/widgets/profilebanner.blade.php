<div class="profile-banner-container">
  <div class="well well-banner">
    <div class="row">
      <div class="col-md-7">
        <div class="media" style="margin-top:100px;color:white">
          <div class="media-left">
            <a href="#">
              <img width="128" height="128" class="media-object profile-img" src="{{ $base_url}}/uploads/{{ $profile_picture }}">
            </a>
          </div>
          <div class="media-body media-align-bottom">
            <h1 class="media-heading text-white">{{ $fullname }}</h1>
            <small class="text-white">50 Followers - 20 Friends - 12 Following</small><br>
          </div>
        </div>
      </div>
      <div class="col-md-5">
        <div class="profile-buttons-div pull-right">
          <div class="btn-group">
            {{ $friendship_button }}
            <ul class="dropdown-menu mrt10" role="menu">
              <li><a href="javascript:;" data-type="10"  data-id="{{ $u_id }}" class="friend"><b><span class="text-primary hidden" data-icon="&#xe004;"></span> Cancle friend request</b></a></li>
              <li><a href="javascript:;" class="friend"><b><span class="text-primary hidden" data-icon="&#xe002;"></span> Add to favourites</b></a></li>
              <li class="divider"></li>
              <li><a href="#" class="friend"><b><span class="text-primary hidden" data-icon="&#xe080;"></span> Add to family</b></a></li>
            </ul>
          </div> {{ $follow_button }} {{ $message_button }}
        </div>
      </div>
    </div>
  </div>
  <div class="well well-snow footer-links-well">
    <span>
      <ul class="list-inline nmb">
        <li class="li-icons li-icons-active"><span class="text-primary" data-icon="&#xe005;"></span> Profile</li>
        <li class="text-muted-md">|</li>
        <li class="li-icons"><span class="text-primary" data-icon="&#xe060;"></span> About</li>
        <li class="text-muted-md">|</li>
        <li class="li-icons"><span class="text-primary" data-icon="&#xe001;"></span> Friends</li>
        <li class="text-muted-md">|</li>
        <li class="li-icons"><span data-icon="&#xe07f;" class="text-primary"></span> Photos</li>
      </ul>
    </span>
  </div>
</div>