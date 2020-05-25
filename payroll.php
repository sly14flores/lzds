<?php

require_once 'authentication.php';
$page = "payroll";

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

  <body id="main-body" class="nav-md" ng-app="payroll" ng-controller="payrollCtrl" account-profile>
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
              <div class="title_right">
              </div>
            </div>
			<div class="clearfix"></div>
			<div style="padding: 0 10px;">
			<div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group pull-right top_search">
				<div class="x_panel">
                  <div class="x_title">
                    <h2>Reports</h2>
                    <ul class="nav navbar-right panel_toolbox">
					  <li><a href="javascript:;">&nbsp;</a></li>
					  <li><a href="javascript:;">&nbsp;</a></li>
					  <li><a href="javascript:;">&nbsp;</a></li>
					  <li><a href="javascript:;">&nbsp;</a></li>
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <form name="formHolder.reports" id="formHolder.reports">
					  <div class="form-group">
						<label>Report :</label>
						<select class="form-control" ng-model="payroll.reports.report">
							<option value="tardiness_awol">Tardiness/AWOL</option>
						</select>						
					  </div>					
					  <div class="form-group">
						<label>School Year :</label>
						<select class="form-control" ng-model="payroll.reports.payroll_sy" ng-options="sy as sy.school_year for sy in school_years_ track by sy.id">
							<option value="0">SY</option>
						</select>						
					  </div>
                      <label>Month :</label>
                      <select class="form-control" ng-model="payroll.reports.month" ng-options="m.description for m in months track by m.month"></select>
                      <label>Period :</label>
                      <select class="form-control" ng-model="payroll.reports.period">
						<option value="first">First</option>
						<option value="second">Second</option>
					  </select>
                      <span class="btn btn-primary pull-right" ng-click="form.printReports(this)">Go!</span>
                    </form>
                  </div>
                </div>
                </div>			
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group pull-right top_search">
				<div class="x_panel">
                  <div class="x_title">
                    <h2>Payroll Sheet</h2>
                    <ul class="nav navbar-right panel_toolbox">
					  <li><a href="javascript:;">&nbsp;</a></li>
					  <li><a href="javascript:;">&nbsp;</a></li>
					  <li><a href="javascript:;">&nbsp;</a></li>
					  <li><a href="javascript:;">&nbsp;</a></li>
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <form name="formHolder.all" id="formHolder.all">
					  <div class="form-group">
						<label>School Year :</label>
						<select class="form-control" ng-model="payroll.all.payroll_sy" ng-options="sy as sy.school_year for sy in school_years_ track by sy.id">
							<option value="0">SY</option>
						</select>						
					  </div>
                      <label>Month :</label>
                      <select class="form-control" ng-model="payroll.all.month" ng-options="m.description for m in months track by m.month"></select>
                      <label>Period :</label>
                      <select class="form-control" ng-model="payroll.all.period">
						<option value="first">First</option>
						<option value="second">Second</option>
					  </select>
                      <span class="btn btn-primary pull-right" ng-click="form.printAll(this)">Go!</span>
                    </form>
                  </div>
                </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group pull-right top_search">
				<div class="x_panel">
                  <div class="x_title">
                    <h2>Individual Payroll</h2>
                    <ul class="nav navbar-right panel_toolbox">
					  <li><a href="javascript:;">&nbsp;</a></li>
					  <li><a href="javascript:;">&nbsp;</a></li>
					  <li><a href="javascript:;">&nbsp;</a></li>
					  <li><a href="javascript:;">&nbsp;</a></li>
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <form name="formHolder.individual" id="formHolder.individual">
					  <div class="form-group">
						<label>School Year :</label>
						<select class="form-control" ng-model="payroll.individual.payroll_sy" ng-options="sy as sy.school_year for sy in school_years_ track by sy.id">
							<option value="0">SY</option>
						</select>
					  </div>
                      <label>Month :</label>
                      <select class="form-control" ng-model="payroll.individual.month" ng-options="m.description for m in months track by m.month"></select>
                      <label>Period :</label>
                      <select class="form-control" ng-model="payroll.individual.period">
						<option value="first">First</option>
						<option value="second">Second</option>
					  </select>
                      <label>Staff :</label>
                      <!-- <input type="text" class="form-control" ng-model="payroll.individual.fullname" uib-typeahead="fullname as ss.fullname for ss in suggest_staffs | filter:{fullname:$viewValue}" typeahead-on-select="form.staffSelect(this, $item, $model, $label, $event)"> -->
                      <select class="form-control" ng-model="payroll.individual.staff" ng-options="ss.full_name for ss in suggest_staffs track by ss.id" ng-change="form.staffSelected(this)"></select>
                      <br/>
                      <span class="btn btn-primary pull-right" ng-click="form.individual(this,false)">Go!</span>
                    </form>
                  </div>
                </div>
                </div>				
			</div>
			</div>
			
            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>{{views.panel_title}}</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-tasks"></i></a>
                        <ul class="dropdown-menu" role="menu">
                          <li><a href="javascript:;" ng-click="form.individual(this,true)">Re-process</a></li>
                        </ul>
                      </li>					  
                      <!--<li><a class="close-link"><i class="fa fa-close"></i></a></li>-->
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div id="x_content" class="x_content"></div>
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
	<script src="modules/module-access.js"></script>
	<script src="modules/account.js?ver=1.0.0.1"></script>
	<script src="modules/fullscreen.js"></script>
	<script src="modules/pnotify.js"></script>		
	<script src="modules/blockui.js"></script>	
	<script src="modules/school-year.js"></script>
	<script src="modules/window-open-post.js"></script>	
	<script src="modules/jspdf.js"></script>	
	<script src="modules/payroll.js"></script>
	
	<script src="controllers/payroll.js?ver=1.0.1"></script>

  </body>
</html>