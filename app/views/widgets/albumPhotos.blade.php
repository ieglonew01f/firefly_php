<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            @if ( $username == Session::get('username') )
              <button id="new-album-create" class="btn btn-sm btn-success pull-right mrl-sm"><i class="fa fa-plus"></i> Create an album</button>
            @endif
            <h4 class="nmt nmb"><span class="icon-picture"></span> Albums</h4>
            <ul class="list-inline margin-top-sm nmb"><li><a href="/profile/{{ $username }}/photos"><b>Photos @if ( $photo_count ) ({{ $photo_count }}) @endif</b></a></li><li>-</li><li><a href="/profile/{{ $username }}/albums"><b>Albums @if ( $album_count ) ({{ $album_count }}) @endif</b></a></li></ul>
          </div>
          <div class="panel-body">
            <div class="new-album-div hidden">
              <div class="row">
                <div class="col-md-4">
                  <h4>Create an album</h4>
                  <div class="form-group">
                    <input type="text" id="album-title" placeholder="Album title" class="form-control"></input>
                  </div>
                  <div class="form-group">
                    <textarea maxlength="150" rows="5" id="album-desc" placeholder="Album description" class="form-control"></textarea>
                  </div>
                  <div class="form-group">
                    <button id="save-album" class="btn btn-primary disabled">Save album</button>
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="loader-album-div hidden text-center">
                    <h4><span data-icon="&#xe084;"></span> Upload progress <span class="upload-perc">  </span> Complete</h4>
                    <div class="progress">
                      <div class="progress-bar progress-bar-striped active" id="album-upload-progress-bar" role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100" style="width: 1%">
                      </div>
                    </div>
                  </div>
                  <div class="images-div">

                  </div>
                </div>
              </div>
            </div>
            <div class="albums-view">
              {{ $album_photos }}
            </div>
          </div>
        </div>
      </div>
    </div>
  <form class="album_upload" method="post" enctype="multipart/form-data" action="/album_upload">
    <input class="hidden" type="file" id="album_upload" name="file[]" multiple></input>
  </form>
</div>
