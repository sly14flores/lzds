<?php

require_once 'authentication.php';
$page = "dtr-students";

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

  <body id="main-body" class="nav-md" ng-app="dtr" ng-controller="dtrCtrl" account-profile>
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
                    <h2>View DTR</h2>
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
                    <form name="formHolder.staff" id="formHolder.staff">
					  <div class="form-group">
						<label>By :</label>
						<select class="form-control" ng-model="studentDtr.by" ng-change="form.byChange(this)">
							<option value="Individual_Student">Individual Student</option>						
							<option value="Section">Section</option>						
							<option value="SF2">SF2</option>						
						</select>
					  </div>
					  <div class="form-group">
						<label>Year :</label>
						<input type="text" class="form-control" ng-model="studentDtr.year" required>
					  </div>
					  <div class="form-group">					  
                      <label>Month :</label>
                      <select class="form-control" ng-model="studentDtr.month" ng-options="m.description for m in months track by m.month"></select>
					  </div>
					  <div class="form-group">						  
						<label>School Year :</label>
						<select class="form-control" ng-model="studentDtr.sy" ng-options="sy.school_year for sy in school_years track by sy.id" ng-change="form.studentsSuggest(this)"></select>
					  </div>					  
					  <div ng-show="studentDtr.by == 'SF2' || studentDtr.by == 'Section'">
						  <div class="form-group">					  
							<label>Grade :</label>
							<select class="form-control" ng-model="studentDtr.grade" ng-options="level.description for level in levels track by level.id" ng-change="form.studentsSelect(this)" ng-required="studentDtr.by == 'SF2' || studentDtr.by == 'Section'"></select>
						  </div>
						  <div class="form-group">						  
							<label>Section :</label>
							<select class="form-control" ng-model="studentDtr.section" ng-options="section.description for section in sections track by section.id" ng-required="studentDtr.by == 'SF2' || studentDtr.by == 'Section'" ng-change="form.studentsSelect(this)"></select>
						  </div>
					  </div>
					  <div ng-show="studentDtr.by == 'Individual_Student'">
						<div class="form-group">						
							<label>Student :</label>
							<input type="text" class="form-control" ng-model="studentDtr.fullname" uib-typeahead="fullname as ss.fullname for ss in suggest_students | filter:{fullname:$viewValue}" typeahead-on-select="form.studentSelect(this, $item, $model, $label, $event)" ng-required="studentDtr.by == 'Individual_Student'">
						</div>
					  </div>
					  <!-- <div ng-show="studentDtr.by == 'Section'">
						<div class="form-group">						
							<label>Select Student :</label>
							<select class="form-control" ng-model="studentDtr.select_student" ng-options="ss.fullname for ss in select_students track by ss.id" ng-required="studentDtr.by == 'Section'" ng-change="form.sectionStudentSelect(this)"></select>
						</div>
					  </div> -->					  
                      <br/>
                      <span class="btn btn-primary pull-right" ng-click="form.dtr(this,false)">Go!</span>
                    </form>
                  </div>
                </div>
                </div>			  
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 form-group pull-right top_search">
				<div class="x_panel">
                  <div class="x_title">
                    <h2>DTR Importer</h2>
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
					<div class="progress">
						<div class="progress-bar progress-bar-info" data-transitiongoal="{{downloadProgress}}" aria-valuenow="{{downloadProgress}}" style="width: {{downloadProgress}}%;"></div>
                    </div>
					<p>{{downloadProgressStatus}}</p>
                    <form name="formHolder.downloadDtr" id="formHolder.downloadDtr">
                      <label>By :</label>
                      <select class="form-control" name="month" ng-model="downloadDtr.by" ng-change="form.dtrBySelected(this)">
						<option value="Month">Month</option>
						<option value="Date">Date</option>
					  </select>
					  <div ng-show="downloadDtr.by == 'Month'">
						  <label>Year :</label>
						  <input type="text" class="form-control" name="year" ng-model="downloadDtr.year" ng-required="downloadDtr.by == 'Month'">
						  <label>Month :</label>
						  <select class="form-control" name="month" ng-model="downloadDtr.month" ng-options="m.description for m in months track by m.month" ng-required="downloadDtr.by == 'Month'"></select>
					  </div>
					  <div ng-show="downloadDtr.by == 'Date'">
						  <label>From :</label>
						  <input type="date" class="form-control" name="dateFrom" ng-model="downloadDtr.dateFrom" ng-required="downloadDtr.by == 'Date'">
						  <label>To :</label>
						  <input type="date" class="form-control" name="dateTo" ng-model="downloadDtr.dateTo" ng-required="downloadDtr.by == 'Date'">
					  </div>
                      <br/>
                      <span class="btn btn-primary pull-right" ng-click="form.download(this);">Go!</span>
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
                          <li><a href="javascript:;" ng-click="form.dtr(this,true)">Re-analyze</a></li>
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

	<script src="modules/bootstrap-modal.js<?=$update?>"></script>
	<script src="modules/module-access.js<?=$update?>"></script>
	<script src="modules/account.js<?=$update?>"></script>
	<script src="modules/fullscreen.js<?=$update?>"></script>
	<script src="modules/blockui.js<?=$update?>"></script>		
	<script src="modules/pnotify.js<?=$update?>"></script>	
	<script src="modules/school-year.js<?=$update?>"></script>
	<script src="modules/jspdf.js<?=$update?>"></script>	
	<script src="modules/dtr-students.js<?=$update?>"></script>
	
	<script src="controllers/dtr-students.js<?=$update?>"></script>

  </body>
</html>