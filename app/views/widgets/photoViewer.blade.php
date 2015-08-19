<!-- Photo Viewer Modal -->
<div class="modal" id="viewerModal" tabindex="1" role="dialog">
  <div class="modal-dialog viewerDialog" role="document">
    <div class="modal-content">
      <div class="modal-body padding-none">
          <div class="row">
            <div class="col-md-8 npr">
              <div class="viewerModalImage">
                <div class="img-container">

                </div>
              </div>
            </div>
            <div class="col-md-4 npl">
              <div class="viewerDialogProfile">
                <div id="viewerDialogMediaBody" class="media">
                  <div class="media-left">
                    <a href="">
                      <img width="64" height="64" class="media-object" src="">
                    </a>
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading nmb" id="media-heading"></h4>
                    <small class="text-muted"></small><br/>
                    <span data-type="1" class="post-card-button-inner mr-r fa fa-heart-o like"></span> <span data-icon="" class="post-card-button-inner"></span>
                  </div>
                </div>
                <br/>
                <ul class="list-inline no-margin-bottom"><li><img width="32" height="32" src="public/assets/img/avatars/man_1.jpg"></li><li><img width="32" height="32" src="public/assets/img/avatars/man_2.jpg"></li><li><img width="32" height="32" src="public/assets/img/avatars/real_female1.jpg"></li><li><span class="no-likes">23 more likes</span></li><li><span class="no-likes">21 Shares</span></li></ul>
                <div class="viewerDialogComments">
                  <div class="viewerDialogCommentsHolder">
                    <p class="hidden"><span data-icon="&#xe04a;"></span> <span id="comments_count"></span>+ Comments</p>
                    <div class="overlay-loader">
                      <div id="gallery_comment_loader" class="loader loader-inner ball-pulse margin-bottom-sm hidden"><div></div><div></div><div></div></div>
                    </div>
                    <div id="gallery_comments" class="comment comment-nbg">
                    </div>
                    <div class="comment-input">
                      <div class="media nmt">
                        <div class="media-left">
                          <a href="/profile/{{ $username }}">
                            <img width="38" height="38" class="media-object" src="/uploads/{{ $profile_picture }}">
                          </a>
                        </div>
                        <div class="media-body">
                          <input data-image="" data-feed="" id="comment_gallery" placeholder="Type a comment.." type="text" class="form-control input-mm">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>
    </div>
  </div>
</div>
