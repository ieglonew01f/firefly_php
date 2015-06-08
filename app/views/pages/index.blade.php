@extends('layouts.default')
@section('title')
  Social Networking Script
@stop
@section('page_css')
  {{ HTML::style('public/assets/css/jenkins.index.css'); }}
@stop
@section('content')
    <div class="content">
      <div class="jumbotron jumbotron-background">
        <nav class="navbar navbar-default background-transparent">
          <div class="container">
            <div class="navbar-header">
              <a class="navbar-brand" href="#">
                <b class="text-white">{{trans('messages.title')}}</b>
              </a>
            </div>
            <div class="collapse navbar-collapse">
              <ul class="nav navbar-nav navbar-right">
                <li><a href="#"><b>FEATURES</b></a></li>
                <li><a href="#"><b>DOCUMENTATION</b></a></li>
                <li><a href="#"><b>FAQ's</b></a></li>
                <li><button onclick="window.location='/login'" type="button" class="btn btn-success navbar-btn">Sign in</button></li>
              </ul>
            </div>
          </div>
        </nav>
        <div class="container">
          <div class="col-md-8">
            <h1>{{trans('messages.welcome')}}</h1>
            <p class="text-white ">Your social network <br/>Jenkins is the best way to create your own <br/> social network or online community in just 5 minutes without any programming knowledge.</p>
            <p class="text-white"><b>{{trans('messages.join')}}</b></p>
            <hr/>
            <div class="row"> 
              <div class="col-md-1">
                <img width="50" height="50" src="public/assets/img/avatars/female_1.jpg" class="img-circle members-icons">
              </div>
              <div class="col-md-1">
                <img width="50" height="50" src="public/assets/img/avatars/boy_1.jpg" class="img-circle members-icons">
              </div>
              <div class="col-md-1">
                <img width="50" height="50" src="public/assets/img/avatars/female_2.jpg" class="img-circle members-icons">
              </div>
              <div class="col-md-1">
                <img width="50" height="50" src="public/assets/img/avatars/boy_3.jpg" class="img-circle members-icons">
              </div>
              <div class="col-md-1">
                <img width="50" height="50" src="public/assets/img/avatars/female_3.jpg" class="img-circle members-icons">
              </div>
              <div class="col-md-1">
                <img width="50" height="50" src="public/assets/img/avatars/female_4.jpg" class="img-circle members-icons">
              </div>
              <div class="col-md-1">
                <img width="50" height="50" src="public/assets/img/avatars/boy_2.jpg" class="img-circle members-icons">
              </div>
              <div class="col-md-1">
                <img width="50" height="50" src="public/assets/img/avatars/boy_6.jpg" class="img-circle members-icons">
              </div>
              <div class="col-md-1">
                <img width="50" height="50" src="public/assets/img/avatars/female_6.jpg" class="img-circle members-icons">
              </div>
              <div class="col-md-1">
                <img width="50" height="50" src="public/assets/img/avatars/boy_5.jpg" class="img-circle members-icons">
              </div>
              <div class="col-md-1">
                <img width="50" height="50" src="public/assets/img/avatars/boy_4.jpg" class="img-circle members-icons">
              </div>
            </div>
          </div>
          <div class="col-md-4 npr">
            <div class="well well-sm">
              <h3 class="no-margin-top">{{trans('messages.free_acc')}}</h3>
              <form id="signup_form">
                <div class="form-group">
                  <span class="Label">{{trans('messages.fullname')}}</span>
                  <input name="fullname" type="text" placeholder="{{trans('messages.your')}} {{trans('messages.fullname')}}" class="form-control"></input>
                </div>
                <div class="form-group">
                  <span class="Label">{{trans('messages.email')}}</span>
                  <input id="email" name="email" type="email" placeholder="{{trans('messages.your')}} {{trans('messages.email')}}" class="form-control"></input>
                </div>
                <div class="form-group">
                  <span id="label_username" class="Label">{{trans('messages.username')}}</span>
                  <input id="username" name="username" type="text" placeholder="{{trans('messages.your')}} {{trans('messages.username')}}" class="form-control"></input>
                </div>
                <div class="form-group">
                  <span class="Label">{{trans('messages.password')}}</span>
                  <input name="password" id="password" type="password" placeholder="{{trans('messages.enter_p')}}" class="form-control"></input>
                </div>
                <div class="form-group">
                  <span class="Label">{{trans('messages.repsswd')}}</span>
                  <input name="repassword" type="password" placeholder="{{trans('messages.enter_pa')}}" class="form-control"></input>
                </div>
                <div class="checkbox checkbox-primary">
                  <input id="checkbox" class="styled" type="checkbox" checked></input>
                  <label for="checkbox">{{trans('messages.by_sign')}} <a href="#" class="spc-a">{{trans('messages.and')}}</a> {{trans('messages.terms')}} <a href="#" class="spc-a">{{trans('messages.privacy')}}</a></label>
                </div>
                <div class="form-group">
                  <button id="new_account" class="btn btn-primary width-full">{{trans('messages.new_acc')}}</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="container">
        <ul class="list-unstyled">
          <li>Language : <a class="setlanguage" href="javascript:;">English (US)</a> - <a class="setlanguage" href="javascript:;">Russian</a> - <a class="setlanguage" href="javascript:;">French</a> - <a class="setlanguage" href="javascript:;">German</a></li>
          <li>&copy; Jenkins 2015</li>
        <ul>
      </div>
    </div>
@stop
@section('page_js')
  {{ HTML::script('public/assets/js/index/jenkins.index.js'); }}
@stop