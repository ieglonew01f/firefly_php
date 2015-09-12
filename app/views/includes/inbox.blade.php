<div class="content">
    <div class="container inbox-container">
        <div class="row">
            <div class="col-md-4 npr">
                <div class="well conversation-list">
                    <div class="top-menu">
                        <div class="row">
                            <div class="col-xs-3"><img class="media-object" style="width:32px;height:32px;" src="/uploads/thumb_{{ $profile_data['profile_picture'] }}" alt="..."/></div>
                            <div class="col-xs-9"><a href="/settings" title="Inbox Settings" class="btn btn-transparent-primary btn-sm pull-right mrls"><span data-icon="&#xe060;"></span></a><button title="New Message" class="btn btn-transparent-primary btn-sm pull-right new-message-inbox"><i class="fa fa-plus"></i></button></div>
                        </div>
                    </div>
                    <div class="search-bar-inbox">
                        <div class="input-group">
                          <span class="input-group-addon background-transparent" id="search-addon"><i class="fa fa-search text-muted"></i></span>
                          <input type="text" class="custom-input search-conv" placeholder="Search" aria-describedby="search-addon">
                        </div>
                    </div>
                    <div class="contact-list-inbox">
                      {{ $inbox_data }}
                    </div>
                </div>
            </div>
            <div class="col-md-8 npl">
                <div class="well chatter-head nmb">
                    <div class="media">
                      <div class="media-left">
                        <a href="/profile/{{ $inbox_chatter_data['username'] }}">
                          <img id="inbox-whois" class="media-object img-circle" style="width:32px;height:32px;" src="/uploads/thumb_{{ $inbox_chatter_data['profile_picture'] }}" alt="...">
                        </a>
                      </div>
                      <div class="media-body">
                        <h5 id="inbox-whois-fullname" class="media-heading fwb nmb">{{ $inbox_chatter_data['fullname'] }}</h5>
                        <small>Online</small>
                      </div>
                    </div>
                </div>
                <div id="chatter-inbox" class="well chatter">
                  {{ $inbox_chatter_data['html'] }}
                </div>
                <div class="well input">
                    <input data-username = "{{ $inbox_chatter_data['username'] }}" data-fullname = "{{ $inbox_chatter_data['fullname'] }}" class="chatter-input-comet" placeholder="Type a message..."/>
                </div>
            </div>
        </div>
    </div>
</div>
