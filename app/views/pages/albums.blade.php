@extends('layouts.internal')
@section('title')
  Albums - Social Networking Script
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
            @include('widgets.profilebanner', $profile_data, ['data_active' => 'photos'])
          </div>
        </div>
        @include('widgets.albums', $profile_data)
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
