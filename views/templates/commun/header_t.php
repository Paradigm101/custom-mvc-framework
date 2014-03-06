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
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                  <ul class="dropdown-menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                    <li class="divider"></li>
                    <li><a href="#">One more separated link</a></li>
                  </ul>
                </li>
              </ul>

              <!-- Search this website -->
              <form class="navbar-form navbar-left" role="search">
                <div class="form-group">
                  <input type="text" class="form-control" placeholder="Search">
                </div>
                <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
              </form>

              <!-- A button -->
              <button type="button" class="btn btn-default navbar-btn">Clic me!</button>

              <!-- On the right side: sign in OR (TBD) Account management -->
              <ul class="nav navbar-nav navbar-right">
                <li><a href="#">Sign In</a></li>
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">My Account<b class="caret"></b></a>
                  <ul class="dropdown-menu">
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
    </div>
</header>
