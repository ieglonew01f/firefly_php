@extends('layouts.internal')
@section('title')
  About - {{ $profile_data['fullname'] }}
@stop
@section('page_css')
  {{ HTML::style('public/assets/css/jenkins.profile.css'); }}
@stop
@section('content')
    <div class="content mrt50">
      @include('includes.sidebar', $session_data)

      <div class="container">
        <div class="row">
          <div class="col-md-12">
            @include('widgets.profilebanner', $profile_data, ['data_active' => 'profile'])
          </div>
        </div>
        <div class="row" id="profile-container">
          <div class="col-md-12">
            <div class="well-snow">
              <h3> {{ $profile_data['followers'] }} Follower</h3>
            </div>
          </div>
        </div>
      </div>
        @include('includes.utility')
        @include('includes.modalcommon', $profile_data)
        @include('widgets.photoViewer', $session_data)
    </div>
@stop
@section('page_js')
  {{ HTML::script('//connect.soundcloud.com/sdk.js'); }}
  {{ HTML::script('public/assets/js/plugins/bootbox.min.js') }}
  {{ HTML::script('public/assets/js/plugins/autogrow.js') }}
  {{ HTML::script('public/assets/js/plugins/jquery.form.js'); }}
  {{ HTML::script('public/assets/js/feeds/jenkins.feeds.js'); }}
  {{ HTML::script('public/assets/js/plugins/jqueryDraggableBackground/draggable_background.js'); }}
  {{ HTML::script('public/assets/js/profile/jenkins.profile.js'); }}
@stop
