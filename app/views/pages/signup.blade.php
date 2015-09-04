@extends('layouts.sementic')
@section('title')
  Signup
@stop
@section('page_css')
  {{ HTML::style('public/assets/css/jenkins.signup.css'); }}
@stop
@section('content')
<!-- PAGE CONTENT -->
<div class="ui grid">
  <div class="sixteen wide column">
    <div class="row">
      <div class="ui stackable menu">
        <div class="ui container">
          <div class="item">
            <img src="http://semantic-ui.com/images/logo.png">
          </div>
          <a class="item">Features</a>
          <a class="item">Testimonials</a>
          <a class="item">Sign-in</a>
        </div>
      </div>
      <div class="ui container mrt60">
        <div class="row">
          <div class="ui grid">
            <div class="ten wide column">
              <div class="ui three top attached steps">
                <div class="active step">
                  <i class="write grey icon"></i>
                  <div class="content">
                    <div class="title">Signup</div>
                    <div class="description">Fill in the basic details to get started</div>
                  </div>
                </div>
                <div class="disabled step">
                  <i class="camera icon"></i>
                  <div class="content">
                    <div class="title">Picture</div>
                    <div class="description">Add a profile picture</div>
                  </div>
                </div>
                <div class="disabled step">
                  <i class="check icon"></i>
                  <div class="content">
                    <div class="title">Done</div>
                    <div class="description">Enjoy the amazing stuff</div>
                  </div>
                </div>
              </div>
              <div class="ui attached segment">
                <div class="ui bottom active green attached progress" data-percent="5">
                  <div class="bar" style="transition-duration: 1000ms; width:5%;"></div>
                </div>
                <form class="ui form">
                  <div class="field">
                    <label>Full Name</label>
                    <input ng-model="fullname" type="text" name="fullname" placeholder="First Name">
                  </div>
                  <div class="field">
                    <label>Username</label>
                    <input ng-model="username" type="text" name="username" placeholder="Username">
                  </div>
                  <div class="field">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Password">
                  </div>
                  <div class="field">
                    <label>Re Password</label>
                    <input type="password" name="repassword" placeholder="Re enter your password">
                  </div>
                  <div class="inline field">
                    <div class="ui checkbox">
                      <input type="checkbox" name="terms" checked>
                      <label>By signing up I agree the <a href="#" class="spc-a">terms</a> and <a href="#" class="spc-a">privacy policy</a></label>
                    </div>
                  </div>
                  <div class="ui animated green basic submit button" tabindex="0">
                    <div class="visible content"><b>Next</b></div>
                    <div class="hidden content">
                      <i class="right arrow icon"></i>
                    </div>
                  </div>
                  <div class="ui error message"></div>
                </form>
              </div>
            </div>
            <div class="six wide column">
              <div class="ui special cards">
                <div class="card">
                  <div class="blurring dimmable image">
                    <div class="ui dimmer">
                      <div class="content">
                        <div class="center">
                          <div class="ui inverted button">Add a profile picture</div>
                        </div>
                      </div>
                    </div>
                    <img src="/public/assets/img/website/steve.jpg">
                  </div>
                  <div class="content">
                    <span class="header">Hi ! </span>
                  </div>
                  <div class="extra content">
                    <div class="meta">
                      <span class="date">Created in Sep 2015</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- EOF PAGE CONTENT -->
@stop
@section('page_js')
  {{ HTML::script('https://ajax.googleapis.com/ajax/libs/angularjs/1.4.4/angular.min.js'); }}
  {{ HTML::script('public/assets/js/signup/jenkins.signup.js'); }}
@stop