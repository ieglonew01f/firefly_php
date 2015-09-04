@extends('layouts.sementic')
@section('title')
  Jenkins - Social Networking Script
@stop
@section('page_css')
  {{ HTML::style('public/assets/css/jenkins.index.css'); }}
@stop
@section('content')
  <!-- Following Menu -->
  <div class="ui large top fixed hidden menu">
    <div class="ui container">
      <a class="item navbar-brand padding-sm">Jenkins</a>
      <div class="right menu">
        <div class="item">
          <a class="ui button">Log in</a>
        </div>
        <div class="item">
          <a class="ui primary button">Sign Up</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Sidebar Menu -->
  <div class="ui vertical inverted sidebar menu">
    <a class="item navbar-brand">Jenkins</a>
  </div>


  <!-- Page Contents -->
  <div class="pusher">
    <div class="ui inverted vertical masthead center aligned segment has-background">

      <div class="ui container">
        <div class="ui large secondary inverted pointing menu no-border">
          <a class="toc item">
            <i class="sidebar icon"></i>
          </a>
          <a class="item navbar-brand">Jenkins</a>
          <div class="right item">
            <a href="/login" class="ui inverted button">Log in</a>
            <a href="/signup" class="ui inverted button">Sign Up</a>
          </div>
        </div>
      </div>

      <div class="ui text container">
        <h1 class="ui inverted header">Be awesome!</h1>
        <p class="inverted">Jenkins is the best way to create your own 
          social network or online community in just 5 minutes without any programming knowledge.</p>
        <div class="ui big width5 right labeled left icon input">
          <i class="mail outline icon"></i>
          <input type="email" class="w4 email" placeholder="Your email address">
          <a href="javascript:void(0)" class="ui blue tag label signup">
            Signup
          </a>
        </div>
      </div>

    </div>

    <div class="ui vertical stripe segment bg-white">
      <div class="ui middle aligned stackable grid container">
        <div class="row">
          <div class="eight wide column">
            <h3 class="ui header"><i class="checkmark icon green"></i> Features like never before</h3>
            <p class="grey">Create a stunning social network in minutes engaging your users with facebook like features live chat, news feed, timeline, profiles, events, notifications, likes, and more. See <a href="/elements/icon.html">here</a> for full list of features</p>
            <h3 class="ui header"><i class="checkmark icon green"></i> Easy to install</h3>
            <p>Yes that's right, our comprehensive <a href="">documentation</a> makes it easy for the user to deploy the script on to their private or public cloud in no time</p>
          </div>
          <div class="six wide right floated column">
            <img src="public/assets/img/website/macbook.jpg" class="ui Massive fluid image demo-img">
          </div>
        </div>
        <div class="row">
          <div class="center aligned column">
            <div class="ui animated blue huge button" tabindex="0">
              <div class="visible content"><b>Learn more</b></div>
              <div class="hidden content">
                <i class="right arrow icon"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


    <div class="ui vertical stripe quote segment bg-white">
      <div class="ui equal width stackable internally celled grid">
        <div class="ui container">
          <h1 class="ui header has-center">
            Join the awesome
          </h1>
          <p class="grey has-center">
            Click on any of these profies to get a preview
          </p>
          <div class="ui six doubling cards has-margin">
            <a class="blue card">
              <div class="image">
                <img src="public/assets/img/website/elliot.jpg">
              </div>
            </a>
            <a class="green card">
              <div class="image">
                <img src="public/assets/img/website/helen.jpg">
              </div>
            </a>
            <a class="grey card">
              <div class="image">
                <img src="public/assets/img/website/jenny.jpg">
              </div>
            </a>
            <a class="teal card">
              <div class="image">
                <img src="public/assets/img/website/veronika.jpg">
              </div>
            </a>
            <a class="pink card">
              <div class="image">
                <img src="public/assets/img/website/stevie.jpg">
              </div>
            </a>
            <a class="red card">
              <div class="image">
                <img src="public/assets/img/website/steve.jpg">
              </div>
            </a>
          </div>
        </div>
      </div>
    </div>

    <div class="ui inverted vertical footer segment">
      <div class="ui container">
        <div class="ui stackable inverted divided equal height stackable grid">
          <div class="three wide column">
            <h4 class="ui inverted header">Product</h4>
            <div class="ui inverted link list">
              <a href="#" class="item">Team</a>
              <a href="#" class="item">Contact Us</a>
              <a href="#" class="item">Documentation</a>
              <a href="#" class="item">API</a>
            </div>
          </div>
          <div class="three wide column">
            <h4 class="ui inverted header">Company</h4>
            <div class="ui inverted link list">
              <a href="#" class="item">Careers</a>
              <a href="#" class="item">Terms</a>
              <a href="#" class="item">Privacy Policy</a>
              <a href="#" class="item">Disclaimer</a>
            </div>
          </div>
          <div class="seven wide column">
            <h4 class="ui inverted header">&copy 2015 Jenkins</h4>
            <p>Powered by Laravel and X-Powered by HHVM</p>
          </div>
        </div>
      </div>
    </div>
  </div>
@stop
@section('page_js')
  {{ HTML::script('public/assets/js/index/jenkins.index.js'); }}
@stop
