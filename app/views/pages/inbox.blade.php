@extends('layouts.internal')
@section('title')
  Jenkins - Social Networking Script
@stop
@section('page_css')
  {{ HTML::style('public/assets/css/jenkins.inbox.css'); }}
@stop
@section('content')
    <div class="content mrt80">
      @include('includes.sidebar', $profile_data)
      @include('includes.inbox', $profile_data)
      @include('includes.utility')
    </div>
@stop
@section('page_js')
  {{ HTML::script('//connect.soundcloud.com/sdk.js'); }}
  {{ HTML::script('public/assets/js/plugins/bootbox.min.js') }}
  {{ HTML::script('public/assets/js/plugins/autogrow.js') }}
  {{ HTML::script('public/assets/js/plugins/jquery.form.js'); }}
  {{ HTML::script('public/assets/js/feeds/jenkins.feeds.js'); }}
@stop
