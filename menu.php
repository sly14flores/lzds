            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <ul class="nav side-menu">
				<!-- menu -->
				  <li ng-show="accountProfile.profile.pages_access.dashboard.value"><a><i class="fa fa-home"></i> Home <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li class="<?php ($page=="index")?"active":""; ?>" class="active" ng-show="accountProfile.profile.pages_access.dashboard.value"><a href="index.php">Dashboard</a></li>	
					</ul>
				  </li>			
                  <li ng-show="accountProfile.profile.pages_access.students.value || accountProfile.profile.pages_access.staffs.value"><a><i class="fa fa-group"></i> Profiles <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li class="<?php ($page=="students")?"active":""; ?>" ng-show="accountProfile.profile.pages_access.students.value"><a href="students.php">Students</a></li>					
                      <li class="<?php ($page=="staffs")?"active":""; ?>" ng-show="accountProfile.profile.pages_access.staffs.value"><a href="staffs.php">Staffs</a></li>
                    </ul>
                  </li>
                  <li ng-show="accountProfile.profile.pages_access.school_year.value"><a><i class="fa fa-book"></i> Enrollments <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li class="<?php ($page=="school-year")?"active":""; ?>" ng-show="accountProfile.profile.pages_access.school_year.value"><a href="school-year.php">School Year</a></li>					
                      <li class="<?php ($page=="ids")?"active":""; ?>" ng-show="accountProfile.profile.pages_access.ids.value"><a href="ids.php">Manage IDs</a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-book"></i> VSmart <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li class="<?php ($page=="vsmart-import")?"active":""; ?>"><a href="vsmart-import.php">Import</a></li>
                      <li class="<?php ($page=="vsmart-enrolled")?"active":""; ?>"><a href="vsmart-enrolled.php">Enrolled</a></li>
                    </ul>
                  </li>					  
                  <li ng-show="accountProfile.profile.pages_access.cashier.value || accountProfile.profile.pages_access.dtr_staffs.value || accountProfile.profile.pages_access.dtr_students.value || accountProfile.profile.pages_access.payroll.value"><a><i class="fa fa-suitcase"></i> Accounting <span class="fa fa-chevron-down"></span></a>				  
                    <ul class="nav child_menu">
                      <li class="<?php ($page=="cashier")?"active":""; ?>" ng-show="accountProfile.profile.pages_access.cashier.value"><a href="cashier.php">Cashier</a></li>
                      <li class="<?php ($page=="dtr")?"active":""; ?>" ng-show="accountProfile.profile.pages_access.dtr_staffs.value"><a href="dtr.php">DTR (Staffs)</a></li>
                      <li class="<?php ($page=="dtr-students")?"active":""; ?>" ng-show="accountProfile.profile.pages_access.dtr_students.value"><a href="dtr-students.php">DTR (Students)</a></li>
                      <li class="<?php ($page=="payroll")?"active":""; ?>" ng-show="accountProfile.profile.pages_access.payroll.value"><a href="payroll.php">Payroll</a></li>
                    </ul>				
                  </li>
                  <li ng-show="accountProfile.profile.pages_access.balances_reports.value || accountProfile.profile.pages_access.summary_reports.value"><a><i class="fa fa-bar-chart-o"></i> Reports <span class="fa fa-chevron-down"></span></a>				  
                    <ul class="nav child_menu">
                      <li class="<?php ($page=="balances_report")?"active":""; ?>" ng-show="accountProfile.profile.pages_access.balances_reports.value"><a href="balances_report.php">Balances</a></li>				  
                      <li class="<?php ($page=="summary_report")?"active":""; ?>" ng-show="accountProfile.profile.pages_access.summary_reports.value"><a href="summary_report.php">Summary</a></li>			  
                    </ul>				  
                  </li>				  
                  <li ng-show="accountProfile.profile.pages_access.levels.value || accountProfile.profile.pages_access.school_fees.value || accountProfile.profile.pages_access.schedules.value || accountProfile.profile.pages_access.holidays.value || accountProfile.profile.pages_access.groups.value"><a><i class="fa fa-cogs"></i> Maintenance <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li class="<?php ($page=="levels")?"active":""; ?>" ng-show="accountProfile.profile.pages_access.levels.value"><a href="levels.php">Grade Levels</a></li>				
                      <li class="<?php ($page=="school-fees")?"active":""; ?>" ng-show="accountProfile.profile.pages_access.school_fees.value"><a href="school-fees.php">School Fees</a></li>
                      <li class="<?php ($page=="schedules")?"active":""; ?>" ng-show="accountProfile.profile.pages_access.schedules.value"><a href="schedules.php">Schedules</a></li>					  
                      <li class="<?php ($page=="school-years")?"active":""; ?>" ng-show="accountProfile.profile.pages_access.school_years.value"><a href="school-years.php">School Years</a></li>					  
                      <li class="<?php ($page=="holidays")?"active":""; ?>" ng-show="accountProfile.profile.pages_access.holidays.value"><a href="holidays.php">Holidays</a></li>
                      <li class="<?php ($page=="groups")?"active":""; ?>" ng-show="accountProfile.profile.pages_access.groups.value"><a href="groups.php">Groups</a></li>
                    </ul>
                  </li>
                <!-- menu -->
				</ul>
              </div>
            </div>
            <!-- /sidebar menu -->