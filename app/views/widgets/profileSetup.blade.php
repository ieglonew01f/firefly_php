    <div class="well-snow profile-completion-panel {{ $hidden }}">
      <h4 class="nmt"><span class="text-success" data-icon="&#xe080;"></span> Profile completion</h4>
      <div class="progress">
        <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" data-value="{{ $percentage }}" style="width: {{ $percentage }}%">
        </div>
      </div>
      <h4 class="nmt complete-prec-txt">Your profile is {{ $percentage }}% complete</h4>
      <hr class="hr-dashed">
      <div class="media question-container">
        <div class="media-left">
          <a href="#">
            <img width="50" height="50" class="media-object" src="/uploads/thumb_{{ $profile_data['profile_picture'] }}">
          </a>
        </div>
        <div class="media-body line-height-1 question-bank">
          <p>{{ $question }}</p>
          <div class="input-container">{{ $dom }}</div>
        </div>
      </div>
      <br/>
      <div class="form-group">
        <button id="next_question" class="btn btn-primary btn-sm">next <span data-icon="&#xe079;"></span></button> <button id="skip_question" class="btn btn-success btn-sm">Close <span data-icon="&#xe082;"></span></button>
      </div>
    </div>
