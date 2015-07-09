@extends('layouts.internal')
@section('title')
  Social Networking Script
@stop
@section('page_css')
  {{ HTML::style('public/assets/css/jenkins.home.css'); }}
@stop
@section('content')
    <div class="content mrt80">
      @include('includes.sidebar')
      
      <div class="container">
        <div class="row">
          <div class="col-md-7">

            @include('includes.statusdiv')

            <div id="feeds_cont">
              {{$feeds}}
            </div>

          </div>
          <div class="col-md-5">
            @include('widgets.profileSetup', array('percentage' => $profile_completion))
            @include('widgets.suggestions')
          </div>
      </div>
        @include('includes.utility')
        @include('widgets.photoViewer', $profile_data)
    </div>
@stop
@section('page_js')
  {{ HTML::script('//connect.soundcloud.com/sdk.js'); }}
  {{ HTML::script('public/assets/js/plugins/bootbox.min.js') }}
  {{ HTML::script('public/assets/js/plugins/autogrow.js') }}
  {{ HTML::script('public/assets/js/plugins/jquery.form.js'); }}
  {{ HTML::script('public/assets/js/feeds/jenkins.feeds.js'); }}
@stop