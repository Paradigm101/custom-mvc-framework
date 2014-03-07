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
              <a class="navbar-brand" href="?page=main"><img src="views/images/small_logo.png" alt="Brand"></a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <!-- Menu -->
              <ul class="nav navbar-nav">
                <li class="<?= ( $data[ 'page' ] == 'main' ? 'active' : '' ) ?>"><a href="?page=main">Home</a></li>
                <li class="<?= ( $data[ 'page' ] == 'bootstrapdemo' ? 'active' : '' ) ?>"><a href="?page=bootstrapdemo">Bootstrap</a></li>
                <li class="<?= ( $data[ 'page' ] == 'about' ? 'active' : '' ) ?>"><a href="?page=about">About</a></li>
              </ul>

              <!-- Search this website -->
              <form class="navbar-form navbar-left" role="search">
                <div class="form-group">
                  <input type="text" class="form-control" placeholder="Search">
                </div>
                <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
              </form>

              <!-- On the right side: sign in OR (TBD) Account management -->
              <ul class="nav navbar-nav navbar-right">
                <li><a href="#"><span class="glyphicon glyphicon-check"></span> Sign In</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> My Account <strong class="caret"></strong></a>
                    <ul class="dropdown-menu">
                      <li><a href="#"><span class="glyphicon glyphicon-cog"></span> Settings</a></li>
                      <li><a href="#"><span class="glyphicon glyphicon-edit"></span> Update profile</a></li>
                      <li><a href="#"><span class="glyphicon glyphicon-usd"></span> Billing</a></li>
                      <li class="divider"></li>
                      <li><a href="#"><span class="glyphicon glyphicon-off"></span> Sign out</a></li>
                    </ul>
                </li>
              </ul>

            </div><!-- end navbar-collapse -->
          </div><!-- end container-fluid -->
        </nav>
    </div>
</header>
