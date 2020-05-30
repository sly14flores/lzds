let app = angular.module('app',['account-module','toggle-fullscreen','vsmart-import']);

app.controller('appCtrl',function($rootScope,vsmartSimport) {
	
	vsmartSimport.data();
	
	$rootScope.vsmart = vsmartSimport;
	
});