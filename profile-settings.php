<?php

require_once 'authentication.php';
$page = "profile-settings";

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
	<style type="text/css">
	
		.grade-doughnut td {
		
			font-size: 12px!important;
		
		}
	
	</style>
  </head>

  <body id="main-body" class="nav-md" ng-app="profileSettings" ng-controller="profileSettingsCtrl" account-profile>
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
			
			<div class="row">
				<div class="col-lg-6 col-md-12 col-sm-12">
					<form class="form" name="formHolder.info" novalidate autocomplete=off>
						<div class="form-body">
							<h4><i class="icon-user"></i> Account Info <button class="btn btn-secondary float-md-right float-xs-right" ng-click="app.info.edit(this)"><i ng-class="{'fa fa-edit': settings.btns.info.edit, 'fa fa-ban': !settings.btns.info.edit}"></i></button></h4><hr>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label>Username</label>
										<input type="text" class="form-control" ng-class="{'border-danger': formHolder.info.username.$invalid || settings.info.not_unique || settings.info.alert.show}" name="username" ng-model="settings.info.username" ng-disabled="settings.btns.info.edit" required>
										<span class="help-block danger" ng-show="formHolder.info.username.$invalid">Username is required</span>
										<span class="help-block danger" ng-show="settings.info.not_unique">Username already exists</span>
										<span class="help-block danger" ng-show="settings.info.alert.show">{{settings.info.alert.message}}</span>
									</div>
								</div>
							</div>
						</div>
					</form>
					<div class="row">
						<div class="col-lg-12">
							<button class="btn btn-info float-md-right float-xs-right" ng-disabled="settings.btns.info.edit" ng-click="app.info.update(this)">Update</button>
						</div>
					</div>		
				</div>
				<div class="col-lg-6 col-md-12 col-sm-12">
					<form class="form" name="formHolder.security" novalidate>
						<div class="form-body">
							<h4><i class="icon-lock3"></i> Account Security <button class="btn btn-secondary float-md-right float-xs-right" ng-click="app.security.edit(this)"><i ng-class="{'fa fa-edit': settings.btns.security.edit, 'fa fa-ban': !settings.btns.security.edit}"></i></button></h4><hr>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label>Old Password</label>
										<input type="password" class="form-control" ng-class="{'border-danger': settings.security.alert.opw.required || settings.security.alert.opw.show}" name="opw" ng-model="settings.security.opw" ng-disabled="settings.btns.security.edit" required>
										<span class="help-block danger" ng-show="settings.security.alert.opw.required">Old password is required</span>
										<span class="help-block danger" ng-show="settings.security.alert.opw.show">{{settings.security.alert.opw.message}}</span>													
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label>New Password</label>
										<input type="password" class="form-control" ng-class="{'border-danger': (formHolder.security.pw.$invalid && formHolder.security.pw.$touched) || settings.security.alert.pw.show}" name="pw" ng-model="settings.security.pw" ng-disabled="settings.btns.security.edit" required>
										<span class="help-block danger" ng-show="(formHolder.security.pw.$invalid && formHolder.security.pw.$touched)">New password is required</span>													
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label>Re-Type New Password</label>
										<input type="password" class="form-control" ng-class="{'border-danger': (formHolder.security.rpw.$invalid && formHolder.security.rpw.$touched) || settings.security.alert.pw.show}" name="rpw" ng-model="settings.security.rpw" ng-disabled="settings.btns.security.edit" required>
										<span class="help-block danger" ng-show="(formHolder.security.rpw.$invalid && formHolder.security.rpw.$touched)">Re-type new password</span>													
										<p class="help-block danger" ng-show="settings.security.alert.pw.show">{{settings.security.alert.pw.message}}</p>													
									</div>
								</div>
							</div>
						</div>
					</form>
					<div class="row">
						<div class="col-lg-12">
							<button class="btn btn-info float-md-right float-xs-right" ng-disabled="settings.btns.security.edit" ng-click="app.security.update(this)">Update</button>
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
    <!-- Chart.js -->
    <script src="vendors/Chart.js/dist/Chart.min.js"></script>	
    
    <!-- Custom Theme Scripts -->
    <script src="build/js/custom.js"></script>

    <script src="vendors/bootbox/bootbox.min.js"></script>
    <script src="vendors/blockUI/jquery.blockUI.js"></script>	
	
	<script src="angular/angular.min.js"></script>
	<script src="angular/ui-bootstrap-tpls-2.5.0.min.js"></script>
	
	<script src="modules/bootstrap-modal.js"></script>
	<script src="modules/module-access.js"></script>
	<script src="modules/account.js?ver=1.0.0.1"></script>
	<script src="modules/fullscreen.js"></script>
	<script src="modules/blockui.js"></script>	
	<script src="modules/pnotify.js"></script>
	<script src="modules/school-year.js"></script>
	<script src="modules/x-panel.js"></script>	
	<script src="modules/profile-settings.js"></script>
	
	<script src="controllers/profile-settings.js"></script>

  </body>
</html>