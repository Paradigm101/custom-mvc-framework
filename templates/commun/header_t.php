<!-- Log-in OK modal -->
<div class="modal fade" id="loginOkModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-center">
        <div class="modal-content">

            <!-- body -->
            <div class="modal-body text-center">
                Welcome back!
            </div><!-- end body -->

        </div><!-- end content -->
    </div><!-- end dialog -->
</div><!-- end modal login ok -->


<!-- Sign-up OK modal -->
<div class="modal fade" id="signupOkModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-center">
        <div class="modal-content">

            <!-- body -->
            <div class="modal-body text-center">
                Congratulations, you have now signed up. Welcome!
            </div><!-- end body -->

        </div><!-- end content -->
    </div><!-- end dialog -->
</div><!-- end modal sign-up ok -->


<!-- Sign-up modal -->
<div class="modal fade" id="signupModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- signup header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">New? Sign-up Here!</h4>
            </div><!-- end signup header -->

            <!-- signup body -->
            <div class="modal-body">
                <form role="form" class="form-horizontal" id="signupForm">
                    <div class="form-group">
                      <label class="col-sm-4 control-label" for="inputEmailSU">Email address</label>
                      <div class="col-sm-8">
                        <input type="email" class="form-control" id="inputEmailSU" placeholder="Enter email">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-4 control-label" for="inputUsernameSU">Username</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" id="inputUsernameSU" placeholder="Enter username">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-4 control-label" for="inputPasswordSU">Enter Password</label>
                      <div class="col-sm-8">
                        <input type="password" class="form-control" id="inputPasswordSU" placeholder="Enter password">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-4 control-label" for="inputPassword2SU">Enter Password again</label>
                      <div class="col-sm-8">
                        <input type="password" class="form-control" id="inputPassword2SU" placeholder="Enter password again">
                      </div>
                    </div>
                  </form><!-- end form -->

                  <!-- User message -->
                  <hr>
                  <div class="text-center" id="signupFeedback"></div>
            </div><!-- end signup body -->

            <!-- footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="signupButton">Sign-up</button>
            </div><!-- end footer -->

        </div><!-- end content -->
    </div><!-- end dialog -->
</div><!-- end myModal -->


<!-- Log-in modal -->
<div class="modal fade" id="loginModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- signup header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Welcome back!</h4>
            </div><!-- end signup header -->

            <!-- signup body -->
            <div class="modal-body">
                <form role="form" class="form-horizontal" id="loginForm">
                    <div class="form-group">
                      <label class="col-sm-4 control-label" for="inputEmailLI">Email address</label>
                      <div class="col-sm-8">
                        <input type="email" class="form-control" id="inputEmailLI" placeholder="Enter email">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-4 control-label" for="inputPasswordLI">Enter Password</label>
                      <div class="col-sm-8">
                        <input type="password" class="form-control" id="inputPasswordLI" placeholder="Enter password">
                      </div>
                    </div>
                  </form><!-- end form -->

                  <!-- User message -->
                  <hr>
                  <div class="text-center" id="loginFeedback"></div>
            </div><!-- end signup body -->

            <!-- footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="loginButton">Log-in</button>
            </div><!-- end footer -->

        </div><!-- end content -->
    </div><!-- end dialog -->
</div><!-- end myModal -->

<!-- header -->
<header class="navbar navbar-inverse navbar-fixed-top" role="banner">
    <div class="container">
        <nav role="navigation">
          <div class="container-fluid">

            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="?request_type=page&request_name=main">Brand</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <!-- Menu -->
              <ul class="nav navbar-nav">
                <li class="<?= ( $data[ 'page' ] == 'main' ? 'active' : '' ) ?>"><a href="?request_type=page&request_name=main">Home</a></li>
                <li class="<?= ( $data[ 'page' ] == 'bootstrapdemo' ? 'active' : '' ) ?>"><a href="?request_type=page&request_name=bootstrapdemo">Bootstrap</a></li>
                <li class="<?= ( $data[ 'page' ] == 'about' ? 'active' : '' ) ?>"><a href="?request_type=page&request_name=about">About</a></li>
              </ul>

              <!-- On the right side: Sign up/Log in OR (TBD) Account management / Notification -->
              <ul class="nav navbar-nav navbar-right">

                <!-- Sign-up modal (Not logged in) -->
                <li><a href="#signupModal" data-toggle="modal"><span class="glyphicon glyphicon-user"></span> Sign up</a></li>

                <!-- Log-in modal (Not logged in) -->
                <li><a href="#loginModal" data-toggle="modal"><span class="glyphicon glyphicon-ok"></span> Log In</a>

                <!-- Account Management (logged in) -->
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-home"></span> My Account <strong class="caret"></strong></a>
                    <ul class="dropdown-menu">
                      <li><a href="#"><span class="glyphicon glyphicon-cog"></span> Settings</a></li>
                      <li><a href="#"><span class="glyphicon glyphicon-pencil"></span> Update profile</a></li>
                      <li><a href="#"><span class="glyphicon glyphicon-inbox"></span> Inbox</a></li>
                      <li class="divider"></li>
                      <li><a href="#"><span class="glyphicon glyphicon-off"></span> Sign out</a></li>
                    </ul>
                </li>

                <!-- Notification (logged in) -->
                <li><a href="#"><span class="glyphicon glyphicon-globe"></span></a></li>
              </ul>

            </div><!-- end navbar-collapse -->
          </div><!-- end container-fluid -->
        </nav><!-- end navigation -->
    </div><!-- end container -->
</header><!-- end header -->
