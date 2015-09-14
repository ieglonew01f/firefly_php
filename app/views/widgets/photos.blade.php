<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            @if ( $username == Session::get('username') )
              <button class="btn btn-sm btn-primary pull-right add_photos_btn"><i class="fa fa-camera"></i> Add photos</button>
            @endif
            <h4 class="nmt nmb"><span class="icon-camera"></span> Photos</h4>
            <ul class="list-inline margin-top-sm nmb"><li><a href="/profile/{{ $username }}/photos"><b>Photos @if ( $photo_count ) ({{ $photo_count }}) @endif</b></a></li><li>-</li><li><a href="/profile/{{ $username }}/albums"><b>Albums @if ( $album_count ) ({{ $album_count }}) @endif</b></a></li></ul>
          </div>
          <div class="panel-body">
            {{ $photo_array }}
          </div>
        </div>
    </div>
  <form class="photos_upload" method="post" enctype="multipart/form-data" action="/photos_uploader">
    <input class="hidden" type="file" id="photos_upload" name="file[]" multiple></input>
  </form>
</div>
