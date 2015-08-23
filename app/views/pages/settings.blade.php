@extends('layouts.internal')
@section('title')
  Settings - Social Networking Script
@stop
@section('page_css')
  {{ HTML::style('public/assets/css/jenkins.settings.css'); }}
@stop
@section('content')
    <div class="content mrt80">
      @include('includes.sidebar', $profile_data)

      <div class="container">
        <div class="row">
          <div class="col-md-3">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title text-muted">Settings</h3>
              </div>
              <div class="panel-body padding-none">
                <div class="list-group no-margin-bottom settings-options">
                  <a href="javascript:void(0)" id="basic_details" class="list-group-item active">
                    Profile settings
                  </a>
                  <a href="#" id="profile_picture_and_banner" class="list-group-item">Profile picture and banner</a>
                  <a href="#" id="privacy" class="list-group-item">Privacy Settings</a>
                  <a href="#" id="timeline" class="list-group-item">Timeline Settings</a>
                  <a href="#" id="notifications" class="list-group-item">Notifications Settings</a>
                  <a href="#" id="messages" class="list-group-item">Message Settings</a>
                  <a href="#" id="messages" class="list-group-item">Account Settings</a>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-9">
            <div id="alert-settings" class="alert alert-warning alert-dismissible hidden" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <strong>Success!</strong> Your settings has been updated
            </div>
            <div id="settings_basic_details" class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Public Profile</h3>
              </div>
              <div class="panel-body">
                <form id="settings_form" class="form-horizontal">
                  <div class="row">
                    <div class="col-md-6">
                      <fieldset>
                        <legend>Basic Details</legend>
                        <div class="form-group">
                          <div class="col-md-12">
                            <div class="input-group">
                              <span class="input-group-addon"><span class="icon-pencil"></span></span>
                              <input id="fullname" value="{{ $profile_data['fullname'] }}" name="fullname" class="form-control" placeholder="Fullname" type="text"/>
                            </div>
                            <small class="text-muted">Change or edit your fullname</small>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-md-12">
                            <div class="input-group">
                              <span class="input-group-addon"><span class="icon-user"></span></span>
                              <input id="username" value="{{ $profile_data['username'] }}" name="username" class="form-control" placeholder="Username" type="text">
                            </div>
                            <span id="label_username" class="display-block"></span>
                            <small id="edit_username_small" class="text-muted">Change or edit your username</small>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-md-12 margin-top-sm">
                            <label class="margin-bottom-sm" for="relationship"><b>Relationship status</b></label>
                              <select name="relationship" class="form-control input-sm">
                                <option @if ($profile_data['relationship'] == 'Single') selected @endif >Single</option>
                                <option @if ($profile_data['relationship'] == 'In a relationship') selected @endif >In a relationship</option>
                                <option @if ($profile_data['relationship'] == 'Engaged') selected @endif >Engaged</option>
                                <option @if ($profile_data['relationship'] == 'Married') selected @endif >Married</option>
                                <option @if ($profile_data['relationship'] == 'In a civil partnership') selected @endif >In a civil partnership</option>
                                <option @if ($profile_data['relationship'] == 'In a domestic partnership') selected @endif >In a domestic partnership</option>
                                <option @if ($profile_data['relationship'] == 'In an open relationship') selected @endif >In an open relationship</option>
                                <option @if ($profile_data['relationship'] == 'Complicated') selected @endif >Complicated</option>
                                <option @if ($profile_data['relationship'] == 'Separated') selected @endif >Separated</option>
                                <option @if ($profile_data['relationship'] == 'Divorced') selected @endif >Divorced</option>
                                <option @if ($profile_data['relationship'] == 'Widowed') selected @endif >Widowed</option>
                              </select>
                            <small class="text-muted">Change or edit your relationship status</small>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-md-12 margin-bottom-sm">     
                            <label class="margin-bottom-sm" for="textarea"><span class="icon-note"></span> <b>About</b></label>                
                            <textarea class="form-control" rows="4" placeholder="About yourself" id="textarea" name="about">{{ $profile_data['about'] }}</textarea>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-md-12">     
                            <button id="save_settings" data-loading-text="Saving settings..." class="btn btn-success">Confirm and Save</button>
                          </div>
                        </div>
                      </fieldset>
                    </div>
                    <div class="col-md-6">
                      <fieldset>
                        <legend>Personal Details</legend>
                        <div class="form-group">
                          <div class="col-md-12">
                            <div class="input-group">
                              <span class="input-group-addon"><span class="icon-pointer"></span></span>
                              <input id="location" value="{{ $profile_data['location'] }}" name="location" class="form-control" placeholder="Current Location" type="text">
                            </div>
                            <small class="text-muted">Change or edit your current location</small>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-md-12">
                            <div class="input-group">
                              <span class="input-group-addon"><span class="icon-home"></span></span>
                              <input id="home" value="{{ $profile_data['home'] }}" name="home" class="form-control" placeholder="Home" type="text">
                            </div>
                            <small class="text-muted">Change or edit your home</small>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-md-12">
                            <div class="input-group">
                              <span class="input-group-addon"><span class="icon-notebook"></span></span>
                              <input id="schooling" value="{{ $profile_data['schooling'] }}" name="schooling" class="form-control" placeholder="Schooling" type="text">
                            </div>
                            <small class="text-muted">Change or edit your schooling</small>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-md-12">
                            <div class="input-group">
                              <span class="input-group-addon"><span class="icon-badge"></span></span>
                              <input id="college" value="{{ $profile_data['college'] }}" name="college" class="form-control" placeholder="College" type="text">
                            </div>
                            <small class="text-muted">Change or edit your college</small>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-1 control-label" for="radios"><b>Gender</b></label>
                          <div class="col-md-11"> 
                            <div class="radio radio-primary radio-inline">
                                <input type="radio" name="gender" id="gender_male" value="Male" @if ($profile_data['gender'] == 'Male') checked @endif>
                                <label for="gender_male">
                                  Male
                                </label>
                            </div>
                            <div class="radio radio-primary radio-inline">
                                <input type="radio" name="gender" id="gender_female" value="Female" @if ($profile_data['gender'] == 'Female') checked @endif>
                                <label for="gender_female">
                                  Female
                                </label>
                            </div>
                          </div>
                        </div>
                      </fieldset>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <div id="settings_profile_picture_and_banner" class="panel panel-default hidden">
              <div class="panel-heading">
                <h3 class="panel-title">Profile Picture and Banner</h3>
              </div>
              <div class="panel-body">
                <div class="media">
                  <div class="media-left">
                    <a href="/profile/{{ $profile_data['username'] }}">
                      <img width="102" height="102" class="media-object" src="/uploads/thumb_{{ $profile_data['profile_picture'] }}">
                    </a>
                  </div>
                  <div class="media-body line-height-1">
                    <div class="form-group">
                      <label for="exampleInputFile"><b>Change rofile picture</b></label>
                      <input type="file" id="exampleInputFile">
                      <p class="help-block text-muted">JPEG and PNG Supported</p>
                    </div>
                  </div>
                  <hr/>
                  <div class="form-group">
                    <img style="width: 100%;" src="/uploads/{{ $profile_data['banner'] }}"></img>
                    <label for="exampleInputFile"><b>Change Banner Picture</b></label>
                    <input type="file" id="exampleInputFile">
                    <p class="help-block text-muted">JPEG and PNG Supported</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
        @include('includes.utility')
        @include('widgets.photoViewer', $profile_data)
    </div>
@stop
@section('page_js')
  {{ HTML::script('public/assets/js/plugins/bootbox.min.js') }}
  {{ HTML::script('public/assets/js/plugins/autogrow.js') }}
  {{ HTML::script('public/assets/js/settings/jenkins.settings.js'); }}
@stop
