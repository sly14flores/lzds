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
    <!-- Animate.css -->
    <link href="vendors/animate.css/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="build/css/custom.min.css" rel="stylesheet">
	<style type="text/css">
	
		.lzds-logo {
			text-align: center;
		}
		
		.lzds-logo img {
			width: 60%;
		}
	
	</style>
  </head>

  <body class="login" ng-app="login" ng-controller="loginCtrl">
    <div>

      <div class="login_wrapper">
        <div class="animate form login_form">
		  <div class="lzds-logo">
			<img src="img/lzds-logo.png?12345">
			<h3>School System</h3>			
		  </div>
          <section class="login_content">
            <form name="formHolder.login" ng-submit="login(this)" autocomplete="off" novalidate>
              <h1>Login</h1>
              <div>
                <input type="text" class="form-control" placeholder="Username" name="username" ng-model="account.username" required autofocus>
              </div>
              <div>
                <input type="password" class="form-control" placeholder="Password" name="password" ng-model="account.password" required>
              </div>
			  <div class="alert alert-danger" role="alert" ng-show="views.incorrect">{{views.msg}}</div>
              <div>
			    <button class="btn btn-default submit">Log in</button>
                <a class="reset_pass" href="javascript:;">Forgot your password?</a>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <!-- <p class="change_link">Don't have an account?
                  <a href="javascript:;" class="to_register"> Request for one </a>
                </p> -->

                <div class="clearfix"></div>
                <br />

                <div>
                  <p>&copy; <?php echo date("Y"); ?> All Rights Reserved. Lord of Zion Divine School</p>
                </div>
              </div>
            </form>
          </section>
        </div>
      </div>
    </div>
  <script src="angular/angular.min.js"></script>
  <script src="modules/login.js"></script>
  <script src="controllers/login.js"></script>
  
  </body>
</html>
