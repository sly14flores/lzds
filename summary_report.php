<?php

require_once 'authentication.php';
$page = "summary_report";

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
  </head>

  <body id="main-body" class="nav-md" ng-app="summaryReport" ng-controller="summaryReportCtrl" account-profile>
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
            </div>

            <div class="clearfix"></div>
			<div style="margin-top: 20px;"></div>
            <div class="row">
              <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Summary</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <!--<li><a class="close-link"><i class="fa fa-close"></i></a></li>-->
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div id="x_content" class="x_content">
                    <form name="formHolder.summary">
					  <div class="form-group" ng-class="{'has-error': formHolder.summary.coverage.$touched && formHolder.summary.coverage.$invalid}">
						<label>Coverage :</label>
						<select class="form-control" name="coverage" ng-model="report.summary.coverage" ng-change="form.reset.summary(this)" required>
							<option value="Daily">Daily</option>
							<option value="Weekly">Weekly</option>
							<option value="Monthly">Monthly</option>
							<option value="Annually">Annually</option>
							<option value="SY">School Year</option>
						</select>
						<span class="help-block" ng-show="formHolder.summary.coverage.$touched && formHolder.summary.coverage.$invalid">Coverage is required</span>
					  </div>
					  <div class="form-group" ng-show="report.summary.coverage=='Daily'" ng-class="{'has-error': formHolder.summary.date.$touched && formHolder.summary.date.$invalid}">
						<label>Date :</label>
						<input type="date" class="form-control" name="date" ng-model="report.summary.date" ng-required="report.summary.coverage=='Daily'">
						<span class="help-block" ng-show="formHolder.summary.date.$touched && formHolder.summary.date.$invalid">Date is required</span>
					  </div>
					  <div class="form-group" ng-show="report.summary.coverage=='Weekly'" ng-class="{'has-error': formHolder.summary.week_from.$touched && formHolder.summary.week_from.$invalid}">
						<label>From :</label>
						<input type="date" class="form-control" name="week_from" ng-model="report.summary.week_from" ng-required="report.summary.coverage=='Weekly'">
						<span class="help-block" ng-show="formHolder.summary.week_from.$touched && formHolder.summary.week_from.$invalid">From is required</span>
					  </div>
					  <div class="form-group" ng-show="report.summary.coverage=='Weekly'" ng-class="{'has-error': formHolder.summary.week_to.$touched && formHolder.summary.week_to.$invalid}">
						<label>To :</label>
						<input type="date" class="form-control" name="week_to" ng-model="report.summary.week_to" ng-required="report.summary.coverage=='Weekly'">
						<span class="help-block" ng-show="formHolder.summary.week_to.$touched && formHolder.summary.week_to.$invalid">To is required</span>
					  </div>
					  <div class="form-group" ng-show="report.summary.coverage=='Monthly'" ng-class="{'has-error': formHolder.summary.month.$touched && formHolder.summary.month.$invalid}">
						<label>Month :</label>
						<select class="form-control" name="month" ng-model="report.summary.month" ng-options="m.description for m in months track by m.id" ng-required="report.summary.coverage=='Monthly'"></select>
						<span class="help-block" ng-show="formHolder.summary.month.$touched && formHolder.summary.month.$invalid">Month is required</span>						
					  </div>
					  <div class="form-group" ng-show="report.summary.coverage=='Monthly' || report.summary.coverage=='Annually'" ng-class="{'has-error': formHolder.summary.year.$touched && formHolder.summary.year.$invalid}">
						<label>Year :</label>
						<input type="number" class="form-control" name="year" ng-model="report.summary.year" ng-required="report.summary.coverage=='Monthly' || report.summary.coverage=='Annually'">
						<span class="help-block" ng-show="formHolder.summary.year.$touched && formHolder.summary.year.$invalid">Year is required</span>
					  </div>
					  <div class="form-group" ng-show="report.summary.coverage!=undefined" ng-class="{'has-error': formHolder.summary.school_year.$touched && formHolder.summary.school_year.$invalid}">
						<label>School Year :</label>
						<select class="form-control" name="school_year" ng-model="report.summary.school_year" ng-options="sy as sy.school_year for sy in school_years_ track by sy.id"></select>
						<span class="help-block" ng-show="formHolder.summary.school_year.$touched && formHolder.summary.school_year.$invalid">School year is required</span>						
					  </div>					  
                      <span class="btn btn-primary pull-right" ng-click="form.summary(this)">Generate!</span>
                    </form>					
				  </div>
                </div>
              </div>
              <div class="col-md-6 col-sm-12 col-xs-12">
			  
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Collections</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <!--<li><a class="close-link"><i class="fa fa-close"></i></a></li>-->
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div id="x_content" class="x_content">
                    <form name="formHolder.collections">
					  <div class="form-group" ng-class="{'has-error': formHolder.collections.coverage.$touched && formHolder.collections.coverage.$invalid}">
						<label>Coverage :</label>
						<select class="form-control" name="coverage" ng-model="report.collections.coverage" ng-change="form.reset.collections(this)" required>
							<option value="Daily">Daily</option>
							<option value="Weekly">Weekly</option>
							<option value="Monthly">Monthly</option>
							<option value="Annually">Annually</option>
							<option value="SY">School Year</option>
						</select>
						<span class="help-block" ng-show="formHolder.collections.coverage.$touched && formHolder.collections.coverage.$invalid">Coverage is required</span>
					  </div>
					  <div class="form-group" ng-show="report.collections.coverage=='Daily'" ng-class="{'has-error': formHolder.collections.date.$touched && formHolder.collections.date.$invalid}">
						<label>Date :</label>
						<input type="date" class="form-control" name="date" ng-model="report.collections.date" ng-required="report.collections.coverage=='Daily'">
						<span class="help-block" ng-show="formHolder.collections.date.$touched && formHolder.collections.date.$invalid">Date is required</span>
					  </div>
					  <div class="form-group" ng-show="report.collections.coverage=='Weekly'" ng-class="{'has-error': formHolder.collections.week_from.$touched && formHolder.collections.week_from.$invalid}">
						<label>From :</label>
						<input type="date" class="form-control" name="week_from" ng-model="report.collections.week_from" ng-required="report.collections.coverage=='Weekly'">
						<span class="help-block" ng-show="formHolder.collections.week_from.$touched && formHolder.collections.week_from.$invalid">From is required</span>
					  </div>
					  <div class="form-group" ng-show="report.collections.coverage=='Weekly'" ng-class="{'has-error': formHolder.collections.week_to.$touched && formHolder.collections.week_to.$invalid}">
						<label>To :</label>
						<input type="date" class="form-control" name="week_to" ng-model="report.collections.week_to" ng-required="report.collections.coverage=='Weekly'">
						<span class="help-block" ng-show="formHolder.collections.week_to.$touched && formHolder.collections.week_to.$invalid">To is required</span>
					  </div>
					  <div class="form-group" ng-show="report.collections.coverage=='Monthly'" ng-class="{'has-error': formHolder.collections.month.$touched && formHolder.collections.month.$invalid}">
						<label>Month :</label>
						<select class="form-control" name="month" ng-model="report.collections.month" ng-options="m.description for m in months track by m.id" ng-required="report.collections.coverage=='Monthly'"></select>
						<span class="help-block" ng-show="formHolder.collections.month.$touched && formHolder.collections.month.$invalid">Month is required</span>						
					  </div>
					  <div class="form-group" ng-show="report.collections.coverage=='Monthly' || report.collections.coverage=='Annually'" ng-class="{'has-error': formHolder.collections.year.$touched && formHolder.collections.year.$invalid}">
						<label>Year :</label>
						<input type="number" class="form-control" name="year" ng-model="report.collections.year" ng-required="report.collections.coverage=='Monthly' || report.collections.coverage=='Annually'">
						<span class="help-block" ng-show="formHolder.collections.year.$touched && formHolder.collections.year.$invalid">Year is required</span>
					  </div>
					  <div class="form-group" ng-show="report.collections.coverage!=undefined" ng-class="{'has-error': formHolder.collections.school_year.$touched && formHolder.collections.school_year.$invalid}">
						<label>School Year :</label>
						<select class="form-control" name="school_year" ng-model="report.collections.school_year" ng-options="sy as sy.school_year for sy in school_years_ track by sy.id"></select>
						<span class="help-block" ng-show="formHolder.collections.school_year.$touched && formHolder.collections.school_year.$invalid">School year is required</span>						
					  </div>					  
                      <span class="btn btn-primary pull-right" ng-click="form.collections(this)">Generate!</span>
                    </form>					
				  </div>
                </div>
				
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

    <script src="vendors/jspdf/jspdf.min.js"></script>
    <script src="vendors/jspdf/jspdf.plugin.autotable.js"></script>
	
	<script src="angular/angular.min.js"></script>	
	<script src="angular/ui-bootstrap-tpls-2.5.0.min.js"></script>	
	
	<script src="modules/bootstrap-modal.js"></script>
	<script src="modules/x-panel.js"></script>
	<script src="modules/module-access.js"></script>
	<script src="modules/account.js?ver=1.0.0.1"></script>
	<script src="modules/fullscreen.js"></script>
	<script src="modules/blockui.js"></script>	
	<script src="modules/school-year.js"></script>
	<script src="modules/pnotify.js"></script>
	<script src="modules/jspdf.js"></script>	
	<script src="modules/summary-report.js?ver=1.0.0.1"></script>
	
	<script src="controllers/summary-report.js"></script>

  </body>
</html>