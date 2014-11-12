
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
                <a href="" class="btn btn-large btn-primary" id="openAlert">Click to toggle success message</a>
                <a href="" class="btn btn-large btn-link">Or a secondary link that does reload the page</a>

            </div><!-- end well -->
            
        </div><!-- end col 12 -->
    </div><!-- end row bigCallout -->

    
    <!-- Feature Heading -->
    <div class="row" id="featuresHeading">
        <div class="col-12">
            <h2>More Features</h2>
            <p class="lead">Those 3 exclusive feature for your pleasure.<p>
        </div><!-- end col 12 -->
    </div><!-- end row featureHeading -->

    <!-- Features -->
    <div class="row" id="features">
        
        <div class="col-sm-4">
            <div class="panel panel-default">
                
                <div class="panel-heading">
                    <h3 class="panel-title">Markup with HTML5</h3>
                </div><!-- end heading -->

                <div class="panel-body">
                    <img src="page/bootstrapdemo/image/badge_html5.jpg" alt="HTML5" class="img-circle">
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
                    <img src="page/bootstrapdemo/image/badge_css3.jpg" alt="CSS3" class="img-circle">
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
                    <img src="page/bootstrapdemo/image/badge_bootstrap.jpg" alt="Bootstrap 3" class="img-circle">
                    <p>Bootstrap is a free collection of tools for creating websites and web applications. It contains HTML and CSS-based design templates.</p>
                    <a href="http://en.wikipedia.org/wiki/Bootstrap_%28front-end_framework%29" target="_blank" class="btn btn-info btn-block">Bootstrap on wikipedia</a>
                </div><!-- end body -->
                
            </div><!-- end panel -->
        </div><!-- end feature -->
        
    </div><!-- end features -->

    <!-- More info -->
    <div class="row" id="moreInfo">
        
        <!-- First half row -->
        <div class="col-sm-6">
            <h3>Neat Tabbable Content</h3>
            <div class="tabbable">
                
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab1" data-toggle="tab">Location</a></li>
                    <li><a href="#tab2" data-toggle="tab">Pic &AMP; Mod</a></li>
                </ul><!-- end tab nav/menu -->

                <div class="tab-content">
                    <div class="tab-pane active" id ="tab1">
                        <h4><span class="glyphicon glyphicon-map-marker"></span> Our location <small>More like where is Roger?</small></h4>
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2887.343539288194!2d-79.38940469470533!3d43.64102038886619!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x882b34d7b66a4a51%3A0xe210b2f6fe0b1405!2sRogers+Centre!5e0!3m2!1sen!2sca!4v1394248101362" width="100%" height="200" frameborder="0" style="border:0"></iframe>
                        <p><?= LOREM ?></p>
                        <p><?= LOREM ?></p>
                    </div><!-- end tab 1 -->

                    <div class="tab-pane" id ="tab2">
                        <h4><span class="glyphicon glyphicon-map-marker"></span> A Left Floated Picture <small>Using Placehold.it</small></h4>
                        <img src="http://placehold.it/140" class="img-thumbnail pull-left">
                        <p><?= LOREM ?></p>
                        <p><?= LOREM ?></p>
                        <hr>
                        <a href="#myModal" role="button" class="btn btn-warning" data-toggle="modal">
                            <span class="glyphicon glyphicon-hand-up"></span> Click for a Modal Window
                        </a>
                        
                        <!-- Modal window -->
                        <div class="modal fade" id="myModal">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">A Modal window</h4>
                                    </div><!-- end header -->
                                    <div class="modal-body">
                                        <h4>Text in a modal</h4>
                                        <p><?= LOREM ?></p>
                                        
                                        <h4>Popovers in a modal</h4>
                                        <a href="#" class="btn btn-danger pop" data-toggle="popover" data-placement="top" data-original-title="You clicked it!" data-content="I knew you would">Don't click this button!</a>
                                        
                                        <h4>Tooltips in modal</h4>
                                        <a href="#" data-original-title="Tooltip" rel="tooltip">This link</a> should have a tooltip, and so should <a href="#" data-original-title="Whooohoho" rel="tooltip">this one</a>.
                                        <hr>
                                        <p><small class="text-muted">PS: This form doesn't do anything. Just a heads up.</small></p>

                                        <form class="form-horizontal" role="form">
                                            <div class="form-group">
                                                <label class="col-lg-2 control-label" for="inputName">Name</label>
                                                <div class="col-lg-10">
                                                    <input class="form-control" id="inputName" placeHolder="Name" type="text">
                                                </div>
                                            </div><!-- end first group -->
                                            <div class="form-group">
                                                <label class="col-lg-2 control-label" for="inputEmail">Email</label>
                                                <div class="col-lg-10">
                                                    <input class="form-control" id="inputEmail" placeHolder="Email" type="text">
                                                </div>
                                            </div><!-- end second group -->
                                            <div class="form-group">
                                                <label class="col-lg-2 control-label" for="inputMessage">Message</label>
                                                <div class="col-lg-10">
                                                    <textarea class="form-control" id="inputMessage" placeHolder="Message" row="3"></textarea>
                                                    <button class="btn btn-success pull-right" id="bsDemoSaveBtn">Save</button>
                                                </div>
                                            </div><!-- end third group -->
                                        </form><!-- end form -->
                                    </div><!-- end body -->

                                    <div class="modal-footer">
                                        <button class="btn btn-default" data-dismiss="modal" type="button">Close</button>
                                        <button class="btn btn-primary" type="button">Save changes</button>
                                    </div>
                                </div><!-- end content -->
                            </div><!-- end dialog -->
                        </div><!-- end myModal -->
                        
                    </div><!-- end tab 2 -->
                </div><!-- end tab content -->
                
            </div><!-- end tabbable -->
        </div><!-- end col-sm-6 -->
        
        <!-- Second half row -->
        <div class="col-sm-6">
            <h3>Some more info</h3>
            <p><?= LOREM ?></p>
            <h4>What to eat? (List group)</h4>
            <div class="list-group">
                <a href="http://en.wikipedia.org/wiki/Kale" class="list-group-item" target="_blank">
                    <h4 class="list-group-item-heading">Kale</h4>
                    <p class="list-group-item-text">Kale or borecole (Brassica oleracea Acephala Group) is a vegetable with green or purple leaves, in which the central leaves do not form a head.</p>
                </a>
                <a href="http://en.wikipedia.org/wiki/Carrot" class="list-group-item" target="_blank">
                    <h4 class="list-group-item-heading">Carrots</h4>
                    <p class="list-group-item-text">The carrot (Daucus carota subsp. sativus; etymology: from Late Latin carōta, from Greek καρωτόν karōton, originally from the Indo-European root ker- (horn), due to its horn-like shape) is a root vegetable, usually orange in colour, though purple, red, white, and yellow varieties exist.</p>
                </a>
                <a href="http://en.wikipedia.org/wiki/Steel-cut_oats" class="list-group-item" target="_blank">
                    <h4 class="list-group-item-heading">Steel-cut oats</h4>
                    <p class="list-group-item-text">Steel-cut oats are whole grain groats (the inner portion of the oat kernel) which have been cut into pieces.</p>
                </a>
            </div><!-- end list group -->
            
            <h5>A small heading</h5>
            <p><?= LOREM ?></p>
        </div><!-- end col-sm-6 -->
        
    </div><!-- end row moreInfo -->

    
    <div class="row" id="moreCourses">
        
    </div><!-- end row moreCourses -->
