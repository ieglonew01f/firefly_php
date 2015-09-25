@if ($friends)
  <div class="friends-container">
    <div class="well-snow">
      <h4 class="nmt"><b>Friends {{ $friends }} </b></h4>
      <div class="friends-inner">
        <div class="row">
          {{ $profile_data['friends_widget'] }}
        </div>
      </div>
    </div>
  </div>
@endif