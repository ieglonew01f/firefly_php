@if ($location || $college || $home)
  <div class="about-container">
    <div class="well-snow">
      <h4 class="nmt">About</h4>
      <ul class="list-unstyled">
        @if ($location)
        <li class="mrb10">
          <ul class="list-inline">
            <li><span class="text-muted" data-icon="&#xe069;"></span></li>
            <li><b class="text-muted">Lives in</b> <b>{{ $location }}</b></li>
          </ul>
        </li>
        @endif
        @if ($college)
        <li class="mrb10">
          <ul class="list-inline">
            <li><span class="text-muted" data-icon="&#xe028;"></span></li>
            <li><b class="text-muted">Studied at</b> <b>{{ $college }}</b></li>
          </ul>
        </li>
        @endif
        <li class="mrb10">
          <ul class="list-inline">
            <li><span class="text-muted" data-icon="&#xe04b;"></span></li>
            <li><b class="text-muted">Works in</b> <b>Intel Corporation</b></li>
          </ul>
        </li>
        @if ($home)
        <li class="mrb10">
          <ul class="list-inline">
            <li><span class="text-muted" data-icon="&#xe096;"></span></li>
            <li><b class="text-muted">From</b> <b>{{ $home }}</b></li>
          </ul>
        </li>
        @endif
        <li class="mrb10">
          <ul class="list-inline">
            <li><span class="text-muted" data-icon="&#xe03b;"></span></li>
            <li><b>{{ $followers }} Followers</b></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
@endif