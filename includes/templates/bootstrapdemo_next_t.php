
    <!-- First big row -->
    <div class="row" id="bigCallout">
        <div class="col-12">

            <!-- When user click on button below -->
            <div class="alert alert-success alert-block fade in" id="successAlert">
                <!-- <button type="button" class="close" data-dismiss="alert">&times;</button> -->
                <button type="button" class="close" id="closeAlert">&times;</button>
                <h4>Success! <small>You just made this element display by using JQuery. Click the "x" in the top right corner to make it disappear.</small></h4>
            </div>

            <!-- Only for small devices -->
            <div class="well well-small hidden-md hidden-lg">
                <a href="" class="btn btn-large btn-block btn-primary"><span class="glyphicon glyphicon-phone"> Give us a call</span></a>
            </div><!-- end well-small -->

            <!-- For everybody -->
            <div class="well">

                <!-- headers -->
                <div class="page-header">
                    <h1>A Fancy Header <small>A subheader for extra awesome</small></h1>
                </div><!-- end page header -->

                <p class="lead">This is a leading paragraph. Some solid leading copy will help get your users engaged. Use this area to come up with something real nice...</p>

                <p>This is as standard paragraph. <?= LOREM ?></p>

                <!-- Buttons -->
                <a href="" class="btn btn-large btn-primary" id="openAlert">Click a nice big button</a>
                <a href="" class="btn btn-large btn-link">Or a secondary link</a>

            </div><!-- end well -->
            
        </div><!-- end col 12 -->
    </div><!-- end row bigCallout -->

    
    <!-- Feature Heading -->
    <div class="row" id="featuresHeading">
        <div class="col-12">
            <h2>More Features</h2>
            <p class="lead"><?= LOREM ?><p>
        </div><!-- end col 12 -->
    </div><!-- end row featureHeading -->


    <div class="row" id="features">
        
        <div class="col-sm-4">
            <div class="panel panel-default">
                
                <div class="panel-heading">
                    <h3 class="panel-title">Markup with HTML5</h3>
                </div><!-- end heading -->

                <div class="panel-body">
                    <img src="includes/images/badge_html5.jpg" alt="HTML5" class="img-circle">
                    <p>HTML5 is a markup language used for structuring and presenting content for the World Wide Web and a core technology of the Internet.</p>
                    <a href="http://en.wikipedia.org/wiki/HTML5" target="_blank" class="btn btn-warning btn-block">HTML5 on wikipedia</a>
                </div><!-- end body -->
                
            </div><!-- end panel -->
        </div><!-- end feature -->
        
        <div class="col-sm-4">
            <div class="panel panel-default">
                
                <div class="panel-heading">
                    <h3 class="panel-title">Style with CSS3</h3>
                </div><!-- end heading -->
                
                <div class="panel-body">
                    <img src="includes/images/badge_css3.jpg" alt="CSS3" class="img-circle">
                    <p>Cascading Style Sheets is a style sheet language used for describing the look and formatting of a document written in a markup language.</p>
                    <a href="http://en.wikipedia.org/wiki/CSS" target="_blank" class="btn btn-danger btn-block">CSS3 on wikipedia</a>
                </div><!-- end body -->

            </div><!-- end panel -->
        </div><!-- end feature -->
        
        <div class="col-sm-4">
            <div class="panel panel-default">
                
                <div class="panel-heading">
                    <h3 class="panel-title">Framework by Bootstrap 3</h3>
                </div><!-- end heading -->
                
                <div class="panel-body">
                    <img src="includes/images/badge_bootstrap.jpg" alt="Bootstrap 3" class="img-circle">
                    <p>Bootstrap is a free collection of tools for creating websites and web applications. It contains HTML and CSS-based design templates.</p>
                    <a href="http://en.wikipedia.org/wiki/Bootstrap_%28front-end_framework%29" target="_blank" class="btn btn-info btn-block">Bootstrap on wikipedia</a>
                </div><!-- end body -->
                
            </div><!-- end panel -->
        </div><!-- end feature -->
        
    </div><!-- end features -->


    <div class="row" id="moreInfo">
        
    </div><!-- end row moreInfo -->

    
    <div class="row" id="moreCourses">
        
    </div><!-- end row moreCourses -->
