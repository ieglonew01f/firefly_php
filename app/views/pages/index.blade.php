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
  <div class="ui vertical sidebar menu">
    <a class="item navbar-brand">Jenkins</a>
  </div>


  <!-- Page Contents -->
  <div class="pusher">
    <div class="ui vertical masthead aligned segment has-background">

      <div class="ui container">
        <div class="ui large secondary pointing menu no-border">
          <a class="toc item">
            <i class="sidebar icon"></i>
          </a>
          <a class="item navbar-brand">Jenkins</a>
          <div class="right item">
            <a href="/login" class="ui blue basic button">Log in</a>
            <a href="/signup" class="ui active blue basic button">Sign Up</a>
          </div>
        </div>
      </div>

      <div class="ui container">
        <div class="ui grid">
          <div class="seven wide column">
            <h1 class="ui header grey">
              <strong class="has-custom-font">Jenkins</strong>
            </h1>
            <p class="cool-font has-custom-font"> 
              Simple Social networking solution
              <br/> and easy deployment <br/>
              for everyone
            </p>
            <hr/>
            <p class="cool-font has-custom-font"> 
              Register your free account and try it out for free
            </p>
            <div id="error-message" class="ui warning message hidden"></div>
            <div class="ui big fluid input margin-bottom-sm">
              <input data-content="Please enter your fullname" type="text" id="fullname" placeholder="Fullname">
            </div>
            <div class="ui big fluid input margin-bottom-sm">
              <input data-content="Please enter your email address" type="text" id="email" placeholder="Email Address">
            </div>
            <div class="ui big fluid input margin-bottom-sm">
              <input data-content="Please enter a password" type="password" id="password" placeholder="Password">
            </div>
            <button id="signup" class="fluid ui massive blue button">Create My Account</button>
          </div>
          <div class="nine wide column has-large-margin">
            <img src="public/assets/img/website/macbook.jpg" class="ui Massive fluid image right demo-img">
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
