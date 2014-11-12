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

<!-- Log-out OK modal -->
<div class="modal fade" id="logoutOkModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-center">
        <div class="modal-content">

            <!-- body -->
            <div class="modal-body text-center">
                Good bye!
            </div><!-- end body -->

        </div><!-- end content -->
    </div><!-- end dialog -->
</div><!-- end modal logout ok -->


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

            <!-- Sign-up header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">New? Sign-up Here!</h4>
            </div><!-- end Sign-up header -->

            <!-- Sign-up body -->
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
            </div><!-- end Sign-up body -->

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

            <!-- Log-in header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Welcome back!</h4>
            </div><!-- end Log-in header -->

            <!-- Log-in body -->
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
            </div><!-- end Log-in body -->

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
                <a class="navbar-brand" href="?rt=<?= REQUEST_TYPE_PAGE ?>&rn=main">Brand</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

              <!-- Menu -->
              <ul class="nav navbar-nav">
<?php
                foreach ( Menu_LIB::getPageMenu() as $page ) {
                    echo '<li><a href="?rt=' . REQUEST_TYPE_PAGE . '&rn=' . $page['fileName'] . '">' . $page['pageName'] . '</a></li>';
                }
?>
              </ul>

              <!-- Sign up/Log in OR Account management / Notification -->
              <ul class="nav navbar-nav navbar-right">

                <?php if ( !Session_LIB::isUserLoggedIn() ) { ?>
                  
                    <!-- Sign-up modal -->
                    <li><a href="#signupModal" data-toggle="modal"><span class="glyphicon glyphicon-user"></span> Sign up</a></li>
                    
                    <!-- Log-in modal -->
                    <li><a href="#loginModal" data-toggle="modal"><span class="glyphicon glyphicon-ok"></span> Log In</a>

                <?php } else { ?>

                    <!-- Account Management -->
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-home"></span> My Account <strong class="caret"></strong></a>
                        <ul class="dropdown-menu">
                          <li><a href="?rt=<?= REQUEST_TYPE_PAGE ?>&rn=settings"><span class="glyphicon glyphicon-cog"></span> Settings</a></li>
                          <li><a href="?rt=<?= REQUEST_TYPE_PAGE ?>&rn=profile"><span class="glyphicon glyphicon-pencil"></span> Update profile</a></li>
                          <li><a href="?rt=<?= REQUEST_TYPE_PAGE ?>&rn=inbox"><span class="glyphicon glyphicon-inbox"></span> Inbox</a></li>
                          <li class="divider"></li>
                          <li><a id="logoutButton" href="#"><span class="glyphicon glyphicon-off"></span> Log out</a></li>
                        </ul>
                    </li>

                    <!-- Notification -->
                    <li><a href="?rt=<?= REQUEST_TYPE_PAGE ?>&rn=notification"><span class="glyphicon glyphicon-globe"></span></a></li>
                <?php } ?>

              </ul>

            </div><!-- end navbar-collapse -->
          </div><!-- end container-fluid -->
        </nav><!-- end navigation -->
    </div><!-- end container -->
</header><!-- end header -->
