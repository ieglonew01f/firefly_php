    <div class="sidebar">
      <ul class="list-unstyled top-sidebar-ul">
        <li>
          <div class="media">
            <div class="media-left">
              <a href="/profile/{{ $username }}">
                <img width="42" height="42" class="media-object" src="/uploads/{{ $profile_picture }}">
              </a>
            </div>
            <div class="media-body line-height-1">
              <h4 class="media-heading" id="media-heading">{{ $fullname }}</h4>
              <a href="">Edit profile</a>
            </div>
          </div>
        </li>
      </ul>
      <ul class="list-unstyled sidebar-ul-links">
        <li><a href="/home"><span class="icon-home"></span> &nbsp; Home <span class="label label-primary pull-right">2</span></a></li>
        <li><a href="/photos/{{ $username }}"><span data-icon="&#xe032;"></span> &nbsp; Photos</a></li>
        <li><a href="/videos/{{ $username }}"><span data-icon="&#xe07f;"></span> &nbsp; Videos</a></li>
        <li><a href="/inbox/{{ $username }}"><span data-icon="&#xe03f;"></span> &nbsp; Inbox <span class="label label-primary pull-right">23</span></a></li>
        <li><a href="/notifications" ><span data-icon="&#xe027;"></span> &nbsp; Notifications <span class="label label-primary pull-right">11</span></a></li>
        <li class="border-bottom"><a href="/settings"><span data-icon="&#xe09a;"></span> &nbsp; Settings</a></li>
      </ul>
      <div class="sidebar-lables"><span class="text-success" data-icon="&#xe04a;"></span> &nbsp; Chat</div>
      <!-- CHAT ELEMENTS -->
      <span id="jenkins_session_data" type="hidden" data-pp="{{ $profile_picture }}"data-username="{{ $username }}" data-fullname="{{ $fullname }}" data-id="{{ $u_id }}"></span>
      <form class="navbar-form add-on-f" role="search">
        <div class="input-group add-on wf">
          <input type="text" class="form-control" placeholder="Search" name="srch-term" id="srch-term">
          <div class="input-group-btn">
            <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
          </div>
        </div>
      </form>
      <ul class="list-unstyled sidebar-chat-list">

      </ul>
    </div>
    <script type="text/javascript">
      var FRIEND_LIST_ARRAY = {{ $profile_data['friends_array'] }}
    </script>
