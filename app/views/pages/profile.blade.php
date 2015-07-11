@extends('layouts.internal')
@section('title')
  Social Networking Script
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
            @include('widgets.profilebanner', $profile_data)
          </div>
        </div>
        <div class="row">
          <div class="col-md-7">
            @include('widgets.profileSetup', array('percentage' => $profile_completion, 'profile_data' => $profile_data))
            @include('includes.statusdiv')

            <div id="feeds_cont">
              {{$feeds}}
            </div>

          </div>
          <div class="col-md-5">
            @include('widgets.about', $profile_data)
            @include('widgets.suggestions')
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
  {{ HTML::script('public/assets/js/profile/jenkins.profile.js'); }}
  {{ HTML::script('public/assets/js/people/jenkins.people.js'); }}
@stop