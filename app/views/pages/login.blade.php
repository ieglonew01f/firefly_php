@extends('layouts.default')
@section('title')
  Login
@stop
@section('page_css')
  {{ HTML::style('public/assets/css/jenkins.login.css'); }}
@stop
@section('content')
      <div class="container">
        <div class="col-md-4 col-md-offset-4">
          <div class="header text-center">
            <h2 class="font-family-stale">{{trans('messages.title')}}</h2>
          </div>
          <div class="well well-sm">
            <div class="form-group">
              <button class="btn btn-primary facebook-btn width-full"><i class="fa fa-facebook"></i> {{trans('messages.fb_login')}}</button>
              <button class="btn btn-primary google-btn width-full"><i class="fa fa-google-plus"></i> {{trans('messages.gp_login')}}</button>
            </div>
            <hr class="symbol">
            <div id="alert_login_failed" class="alert alert-danger hidden" role="alert">
              <strong>Oops!</strong> {{trans('messages.log_err')}}
            </div>
            <form class="login_form">
              <div class="form-group">
                <input name="email_username" type="text" placeholder="{{trans('messages.email_un')}}" class="form-control"></input>
              </div>
              <div class="form-group">
                <input name="password" type="password" placeholder="{{trans('messages.password')}}" class="form-control"></input>
              </div>
              <div class="checkbox checkbox-primary">
                <input id="checkbox" class="styled" type="checkbox" checked></input>
                <label for="checkbox">{{trans('messages.remember')}}</label> 
              </div>
              <div class="form-group">
                <button id="login" class="btn btn-primary width-full">{{trans('messages.login')}}</button>
              </div>
            </div>
          </form>
          <div class="footer">
            <ul class="list-unstyled">
              <li>{{trans('messages.language')}} : <a class="setlanguage" href="javascript:;">English (US)</a> - <a class="setlanguage" href="javascript:;">Russian</a> - <a class="setlanguage" href="javascript:;">French</a> - <a class="setlanguage" href="javascript:;">German</a></li>
              <li>&copy; {{trans('messages.title')}} 2015</li>
            <ul>
          </div>
        </div>
      </div>
    </div>
@stop
@section('page_js')
  {{ HTML::script('public/assets/js/login/jenkins.login.js'); }}
@stop