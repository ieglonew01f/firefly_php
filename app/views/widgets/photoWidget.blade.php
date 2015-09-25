@if ($friends)
  <div class="friends-container">
    <div class="well-snow">
      <h4 class="nmt"><b>Photos {{ $photo_count }}</b></h4>
      <div class="friends-inner">
        <div class="row">
          {{ $profile_data['photos_widget_html'] }}
        </div>
      </div>
    </div>
  </div>
@endif