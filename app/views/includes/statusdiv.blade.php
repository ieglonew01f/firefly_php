    <div class="status-box">
      <span><ul class="list-inline"><li><span class="text-primary" data-icon="&#xe060;"></span> Share something</li><li class="text-muted-md">|</li><li><span class="text-primary" data-icon="&#xe07f;"></span> Share photos</li><li class="text-muted-md">|</li><li><span class="text-primary" data-icon="&#xe062;"></span> Share Music</li><li class="text-muted-md">|</li><li><span data-icon="&#xe096;" class="text-primary"></span> Share a location</li></ul></span>
      <div class="tri-up"></div>
      <div class="form-group">
        <textarea id="status" placeholder="Share something new.." class="text-area"></textarea>
      </div>
      <div id="scWidget"></div>
      <div id="video_frame" class="hidden">
        <div class="media vframe_media">
          <div class="media-left">
            <a href="#">
              <img width="200" class="media-object">
            </a>
          </div>
          <div class="media-body">
            <h4 class="media-heading"></h4>
            <p></p>
          </div>
        </div>
      </div>
      <div id="loader" class="loader loader-inner ball-pulse pull-right mrt10 hidden"><div></div><div></div><div></div></div>
      <div class="form-group">  
        <button id="status_button" class="btn btn-primary btn-sm">Share</button>
        <div class="btn-group">
          <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            <span data-icon="&#xe001;"></span> &nbsp; Public <span class="caret"></span>
          </button>
          <ul class="dropdown-menu" role="menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li class="divider"></li>
            <li><a href="#">Separated link</a></li>
          </ul>
        </div>
      </div>
    </div>