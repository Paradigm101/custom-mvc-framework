
<!-- Sign-up modal -->
<div class="modal fade dark" id="signupModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- signup header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Sign-up Form</h4>
            </div><!-- end signup header -->

            <!-- signup body -->
            <div class="modal-body">
                <form role="form" class="form-horizontal" id="signupForm">
                    <div class="form-group">
                      <label class="col-sm-4 control-label" for="inputEmail">Email address</label>
                      <div class="col-sm-8">
                        <input type="email" class="form-control" id="inputEmail" placeholder="Enter email">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-4 control-label" for="inputUsername">Username</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" id="inputUsername" placeholder="Enter username">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-4 control-label" for="inputPassword">Enter Password</label>
                      <div class="col-sm-8">
                        <input type="password" class="form-control" id="inputPassword" placeholder="Enter password">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-4 control-label" for="inputPassword2">Enter Password again</label>
                      <div class="col-sm-8">
                        <input type="password" class="form-control" id="inputPassword2" placeholder="Enter password again">
                      </div>
                    </div>
                  </form><!-- end form -->
            </div><!-- end signup body -->

            <!-- footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="signupButton">Sign-up</button>
            </div><!-- end footer -->

        </div><!-- end content -->
    </div><!-- end dialog -->
</div><!-- end myModal -->

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
              <a class="navbar-brand" href="?page=main">Brand</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <!-- Menu -->
              <ul class="nav navbar-nav">
                <li class="<?= ( $data[ 'page' ] == 'main' ? 'active' : '' ) ?>"><a href="?page=main">Home</a></li>
                <li class="<?= ( $data[ 'page' ] == 'bootstrapdemo' ? 'active' : '' ) ?>"><a href="?page=bootstrapdemo">Bootstrap</a></li>
                <li class="<?= ( $data[ 'page' ] == 'about' ? 'active' : '' ) ?>"><a href="?page=about">About</a></li>
              </ul>

              <!-- On the right side: Sign up/Log in OR (TBD) Account management / Notification -->
              <ul class="nav navbar-nav navbar-right">

                <!-- Sign-up modal (Not logged in) -->
                <li><a href="#signupModal" data-toggle="modal"><span class="glyphicon glyphicon-user"></span> Sign up</a></li>

                <!-- Log-in modal (Not logged in) -->
                <li><a href="#"><span class="glyphicon glyphicon-ok"></span> Log In</a></li>

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
