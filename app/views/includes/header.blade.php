      <nav class="navbar navbar-inverse navbar-fixed-top navbar-default">
        <div class="container">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/home">Jenkins</a>
          </div>

          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
              <li>
                <form class="navbar-form navbar-left" role="search">
                    <div class="btn-group">
                      <input autocomplete="off" type="text" id="keywords" placeholder="Search for people and more ..." class="search-bar form-control dropdown-toggle" data-toggle="dropdown" style="width:300px !important;">
                      <ul class="dropdown-menu notification-dropdown margin-top-sm" role="menu">
                        <div class="search-results-container">

                        </div>
                        <li>

                          <a href="#" class="bg-grey notification-see-all">
                            <div class="text-center">
                              <small><b>SEE ALL</b></small>
                            </div>
                          </a>
                        </li>
                      </ul>
                    </div>
                </form>
              </li>
              <li class="dropdown notifications-dropdown-main">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span data-icon="&#xe027;"></span> Notifications <span class="badge notif-badge"></span></a>
                <ul class="dropdown-menu notification-dropdown" role="menu">
                  <div class="notifications-container">
                  </div>
                  <li>
                    <a href="#" class="bg-grey notification-see-all">
                      <div class="text-center">
                        <small><b>SEE ALL</b></small>
                      </div>
                    </a>
                  </li>
                </ul>
              </li>
              <li><a href="/inbox/{{ Session::get('username') }}"><span data-icon="&#xe03f;"></span> Inbox</a></li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span data-icon="&#xe005;"></span> Profile <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="#">Action</a></li>
                  <li><a href="#">Another action</a></li>
                  <li><a href="#">Something else here</a></li>
                  <li class="divider"></li>
                  <li><a href="#">Separated link</a></li>
                </ul>
              </li>
            </ul>
          </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
      </nav>
