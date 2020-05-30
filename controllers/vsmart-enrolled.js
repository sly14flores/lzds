let app = angular.module('app',['account-module','toggle-fullscreen','vsmart-enrolled']);

app.controller('appCtrl',function($rootScope,vsmartEnrolled) {
	
	vsmartEnrolled.data();
	
	$rootScope.vsmart = vsmartEnrolled;
	
});