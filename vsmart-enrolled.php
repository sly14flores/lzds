<?php

require_once 'authentication.php';
$page = "vsmart-import";

require_once 'updater.php';

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>School System | Lord of Zion Divine School</title>
	<link rel="icon" type="image/ico" href="favicon.ico">
	
    <!-- Bootstrap -->
    <link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
	  <!-- xeditable for angularjs -->	
    <link href="angular-xeditable/css/xeditable.css" rel="stylesheet">    
    <!-- Font Awesome -->
    <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- PNotify -->
    <link href="vendors/pnotify/dist/pnotify.css" rel="stylesheet">
    <link href="vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
    <link href="vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="build/css/custom.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
	
	<style type="text/css">
		
		.students-fees-download:hover {
			cursor: pointer;
		}
		
	</style>
  </head>

  <body id="main-body" class="nav-md" ng-app="app" ng-controller="appCtrl" account-profile>
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="index.html" class="site_title"><img class="brand-logo" src="img/lzds-logo.png"> <span>LZDS</span></a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <div class="profile clearfix">
              <div class="profile_pic">
                <img src="img/avatar.png" alt="..." class="img-circle profile_img">
              </div>
              <div class="profile_info">
                <span>Welcome,</span>
                <h2>{{accountProfile.profile.staff}}</h2>
              </div>
              <div class="clearfix"></div>
            </div>
            <!-- /menu profile quick info -->

            <br />

			<?php require_once 'menu.php'; ?>

            <!-- /menu footer buttons -->
            <div class="sidebar-footer hidden-small">
              <a data-toggle="tooltip" data-placement="top" title="FullScreen" href="javascript:;" ng-click="fullscreen.toggleFullScreen('#main-body')">
                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
              </a>			
              <a data-toggle="tooltip" data-placement="top" title="Settings" href="javascript:;">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Profile" href="javascript:;">
                <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
              </a>			  
              <a data-toggle="tooltip" data-placement="top" title="Logout" href="javascript:;" logout-account>
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
              </a>
            </div>
            <!-- /menu footer buttons -->
          </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
            <nav>
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>

              <ul class="nav navbar-nav navbar-right">
                <li class="">
                  <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <img src="img/avatar.png" alt="">{{accountProfile.profile.staff}}
                    <span class=" fa fa-angle-down"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-usermenu pull-right" drop-down></ul>
                </li>

                <!--<li role="presentation" class="dropdown">
                  <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-envelope-o"></i>
                    <span class="badge bg-green">6</span>
                  </a>
                  <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                    <li>
                      <a>
                        <span class="image"><img src="images/avatar.png" alt="Profile Image" /></span>
                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                      </a>
                    </li>
                    <li>
                      <a>
                        <span class="image"><img src="images/avatar.png" alt="Profile Image" /></span>
                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                      </a>
                    </li>
                    <li>
                      <a>
                        <span class="image"><img src="images/avatar.png" alt="Profile Image" /></span>
                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                      </a>
                    </li>
                    <li>
                      <a>
                        <span class="image"><img src="images/avatar.png" alt="Profile Image" /></span>
                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                      </a>
                    </li>
                    <li>
                      <div class="text-center">
                        <a>
                          <strong>See All Alerts</strong>
                          <i class="fa fa-angle-right"></i>
                        </a>
                      </div>
                    </li>
                  </ul>
                </li>-->
              </ul>
            </nav>
          </div>
        </div>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>{{views.title}}</h3>
              </div>
              <div class="title_right"></div>
            </div>
            <div class="clearfix"></div>
			<hr>
			
			<!--<form class="form-inline">
			  <div class="form-group">
				<label>School Year:</label>
				<select class="form-control" ng-model="filter.school_year" ng-options="sy.school_year for sy in school_years track by sy.id"></select>
			  </div>
			  <div class="form-group">
				<label>Level:</label>
				<select class="form-control" ng-model="filter.level" ng-options="level.description for level in levels track by level.id" ng-change="form.levelSelected(filter.level)"></select>
			  </div>
			  <div class="form-group">
				<label>Section:</label>
				<select class="form-control" ng-model="filter.section" ng-options="section as section.description for section in sections track by section.id"></select>
			  </div>			  
			  <button type="button" class="btn btn-default" style="margin-top: 5px;" ng-click="form.filter()">Go!</button>
			</form>-->
			
			<hr>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">

              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
          <div class="pull-right">
            Lord of Zion Divine School System
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>

    <!-- jQuery -->
    <script src="vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="vendors/nprogress/nprogress.js"></script>
    <!-- PNotify -->
    <script src="vendors/pnotify/dist/pnotify.js"></script>
    <script src="vendors/pnotify/dist/pnotify.buttons.js"></script>
    <script src="vendors/pnotify/dist/pnotify.nonblock.js"></script>    
    <!-- Custom Theme Scripts -->
    <script src="build/js/custom.js"></script>

    <script src="vendors/bootbox/bootbox.min.js"></script>
    <script src="vendors/blockUI/jquery.blockUI.js"></script>	
	
	<script src="angular/angular.min.js"></script>	
  <script src="angular/ui-bootstrap-tpls-2.5.0.min.js"></script>
  
	<script src="angular-xeditable/js/xeditable.js"></script>  

	<script src="modules/bootstrap-modal.js<?=$update?>"></script>
	<script src="modules/module-access.js<?=$update?>"></script>	
	<script src="modules/account.js?<?=$update?>"></script>
	<script src="modules/fullscreen.js<?=$update?>"></script>
	<script src="modules/blockui.js<?=$update?>"></script>	
	<script src="modules/pnotify.js<?=$update?>"></script>	
	<script src="modules/school-year.js<?=$update?>"></script>	
	<script src="modules/x-panel.js<?=$update?>"></script>
	<script src="modules/window-open-post.js<?=$update?>"></script>
	<script src="modules/vsmart-enrolled.js<?=$update?>"></script>
	
	<script src="controllers/vsmart-enrolled.js<?=$update?>"></script>

  </body>
</html>