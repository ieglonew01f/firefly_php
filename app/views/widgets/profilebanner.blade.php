<div class="profile-banner-container">
  <div class="banner-loader"><div class="loader loader-inner ball-pulse"><div></div><div></div><div></div></div> <span data-icon="&#xe084;"></span><span class="text"></span></div>
  <div id="banner_resize" class="well well-banner" @if ($banner) style="background:url('/uploads/{{ $banner }}');background-size:cover;background-position:{{ $banner_position }}" @endif>
    <div class="row">
      <div class="col-md-6">
        <div class="media media-profile">
          <div class="media-left">
            <a href="/profile/{{ $username }}">
              <img width="128" height="128" class="media-object profile-img" src="/uploads/thumb_{{ $profile_picture }}">
            </a>
          </div>
          <div class="media-body media-align-bottom">
            <h1 class="media-heading text-white text-shadow">{{ $fullname }}</h1>
            <small class="text-white text-shadow">{{ $followers }} Followers - {{ $friends }} Friends - {{ $following }} Following</small><br>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="profile-buttons-div pull-right">
          <div class="btn-group">
            {{ $friendship_button }}
            <ul class="dropdown-menu mrt10" role="menu">
              <li><a href="javascript:;" data-type="{{ $friendship_status }}"  data-id="{{ $u_id }}" class="friend"><b><span class="text-primary hidden" data-icon="&#xe004;"></span> <span class="friendliText">{{ $friendship_text }}</span></b></a></li>
              <li><a href="javascript:;" class="friend"><b><span class="text-primary hidden" data-icon="&#xe002;"></span> Add to favourites</b></a></li>
              <li class="divider"></li>
              <li><a href="#" class="friend"><b><span class="text-primary hidden" data-icon="&#xe080;"></span> Add to family</b></a></li>
            </ul>
          </div> {{ $follow_button }} {{ $message_button }} {{ $more_button }}
        </div>
      </div>
    </div>
  </div>
  <div class="well well-snow footer-links-well">
    <span>
      <ul class="list-inline nmb profile-buttons">
        <li data-type="profile"  @if($data_active == 'profile') class="li-icons li-icons-active" @else class="li-icons" @endif><a href="/profile/{{ $username }}" class="anchor-none"><span class="text-primary" data-icon="&#xe005;"></span> Profile</a></li>
        <li class="text-muted-md">|</li>
        <li data-type="about"  @if($data_active == 'about') class="li-icons li-icons-active" @else class="li-icons" @endif><a href="/profile/{{ $username }}/about" class="anchor-none"><span class="text-primary" data-icon="&#xe060;"></span> About</a></li>
        <li class="text-muted-md">|</li>
        <li data-type="friends"  @if($data_active == 'friends') class="li-icons li-icons-active" @else class="li-icons" @endif><a href="/profile/{{ $username }}/friends" class="anchor-none"><span class="text-primary" data-icon="&#xe001;"></span> Friends</a></li>
        <li class="text-muted-md">|</li>
        <li data-type="photos" @if($data_active == 'photos') class="li-icons li-icons-active" @else class="li-icons" @endif><a href="/profile/{{ $username }}/photos" class="anchor-none"><span data-icon="&#xe07f;" class="text-primary"></span> Photos</a></li>
      </ul>
    </span>
    <div class="resize-save-offset pull-right hidden"><button class="btn btn-transparent-primary banner-resize-save-btn">Save and Close</button> <button class="btn btn-transparent-primary banner-resize-close-btn">Cancel</button></div>
  </div>
</div>
<form class="banner_form" method="post" enctype="multipart/form-data" action="/change_banner">
    <input type="file" name="file" id="file_banner" class="hidden"/>
    <input type="hidden" name="type" value="1"/>
</form>
<form id="form_change_profile_picture" method="post" enctype="multipart/form-data" action="/change_pp">
    <input type="file" name="file" id="file_pp" class="hidden"/>
    <input type="hidden" name="type" value="1"/>
</form>
