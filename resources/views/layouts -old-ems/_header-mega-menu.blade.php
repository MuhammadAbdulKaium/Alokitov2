<style>
   .menu-header-height {
      height: 36px;
   }
</style>

<div class="es-megamenu esmenu pull-left">
   <div id="navbar-collapse" role="navigation" class="navbar-collapse">
      <ul class="nav navbar-nav">
         <li class="dropdown esmenu-full">
            {{--<a href="javascript:void(0)" data-target="#megamenu-items" data-toggle="collapse" class="navbar-toggle">--}}
            {{--<span class="fa fa-th-large fa-lg menu-icon"></span>--}}
            {{--new menu header--}}
            {{--</a>--}}
            @if(getInstituteProfile()->logo)
               <a id="alokito_menu" data-target="#megamenu-items" data-toggle="collapse" class="navbar-toggle" style="margin-top: 3px; cursor: pointer">

                  <span class="fa fa-th-large fa-lg menu-icon"></span>
               </a>
               <a href="/"  style="position: absolute;margin-left: 80px;margin-top: -6px;">
                  <img class="menu-header-height" src="{{URL::to('assets/users/images/'.getInstituteProfile()->logo)}}">
               </a>
            @else
               <span class="fa fa-th-large fa-lg menu-icon"></span>
               @endif
               </a>
               <ul class="dropdown-menu" id="megamenu-items">
                  <li>
                     <div class="esmenu-content">
                        <div class="tabbable row">
                           <div class="col-md-3">
                              <ul class="nav nav-pills nav-stacked">
                                 <li>
                                    <a href="#academics" class="alokito-module"><i class="fa fa-calendar-o"></i> Academics</a>
                                 </li>
                                 <li>
                                    <a href="#hrms" class="alokito-module"><i class="fa fa-user"></i> Human Resource</a>
                                 </li>
                                 <li>
                                    <a href="#student" class="alokito-module"><i class="fa fa-users"></i> Student</a>
                                 </li>
                                 <li>
                                    <a href="#fees" class="alokito-module"><i class="fa fa-money"></i> Fees</a>
                                 </li>
                                 <li>
                                    <a href="#communication" class="alokito-module"><i class="fa fa-comments"></i> Communication</a>
                                 </li>
                                 <li>
                                    <a href="#reports" class="alokito-module"><i class="fa fa-line-chart"></i> Reports and Printing</a>
                                 </li>
                                 <li>
                                    <a href="#administration" class="alokito-module"><i class="fa fa-wrench"></i> Administration</a>
                                 </li>
                                 <li>
                                    <a href="#document" class="alokito-module"><i class="fa fa-file-text-o"></i> Document</a>
                                 </li>
                                 <li>
                                    <a href="#library" class="alokito-module"><i class="fa fa-university"></i> Library</a>
                                 </li>
                                 <li>
                                    <a href="#settings" class="alokito-module"><i class="fa fa-cogs"></i> Settings</a>
                                 </li>
                              </ul>
                           </div>
                           <!-- end col -->
                           <div class="col-md-9 menu-sub-items">
                              <div class="tab-content">
                                 <div id="academics" class="tab-pane">
                                    <div class="visible-sm visible-xs menu-box-header">
                                       <button aria-label="Close" class="close" type="button">
                                          <span aria-hidden="true">×</span>
                                       </button>
                                       <h4><i class="fa fa-calendar-o"></i> Academics</h4>
                                    </div>
                                    <div class="row">
                                       <div class="col-md-3 col-sm-4 col-xs-12">
                                          <div class="menu-box">
                                             <ul>
                                                <li><a href="#"><i class="fa fa-graduation-cap"></i> Course Management</a></li>
                                                <li><a href="{{url('academics/academic-year')}}"><i class="fa fa-calendar"></i> Academic Year</a></li>
                                                <li><a href="{{url('academics/admission-year')}}"><i class="fa fa-calendar"></i> Admission Year</a></li>
                                                <li><a href="{{url('academics/academic-level')}}"><i class="fa fa-graduation-cap"></i> Academic Level</a></li>
                                                <li><a href="{{url('academics/semester')}}"><i class="fa fa-info-circle"></i> Semester</a></li>
                                                <li><a href="{{url('academics/subject')}}"><i class="fa fa-book"></i> Subject</a></li>
                                                <li><a href="{{url('academics/batch')}}"><i class="fa fa-sitemap"></i> Batch</a></li>
                                                <li><a href="{{url('academics/section')}}"><i class="fa fa-sitemap"></i> Section</a></li>
                                                <li><a href="{{url('academics/manage')}}"><i class="fa fa-book "></i>Manage Academics</a></li>
                                                <li><a href="{{url('academics/manage/assessments/grade-setup')}}"><i class="fa fa-book "></i>Manage Assessments</a></li>
                                                <li><a href="{{url('academics/manage/attendance/manage')}}"><i class="fa fa-book "></i>Manage Attendance</a></li>
                                             </ul>
                                          </div>
                                       </div>
                                       <div class="col-md-3 col-sm-4 col-xs-12">
                                          <div class="menu-box">
                                             <ul>
                                                <li><a href="{{url('academics/roomcategory')}}"><i class="fa fa-calendar-o"></i> Timetable</a></li>
                                                <li><a href="{{url('academics/roomcategory')}}"><i class="fa fa-sort-alpha-asc"></i> Room Category</a></li>
                                                <li><a href="{{url('academics/roommaster')}}"><i class="fa fa-object-ungroup"></i> Room Master</a></li>
                                                <li><a href="{{url('/academics/timetable/manage')}}"><i class="fa fa-calendar"></i> Timetable</a></li>
                                             </ul>
                                          </div>
                                       </div>
                                       <div class="col-md-3 col-sm-4 col-xs-12">
                                          <div class="menu-box">
                                             <ul>
                                                <li><a href="#"><i class="fa fa-list"></i> Online Test</a></li>
                                                <li><a href="#"><i class="fa fa-link"></i> Question Category</a></li>
                                                <li><a href="#"><i class="fa fa-question-circle"></i> Questions</a></li>
                                                <li><a href="#"><i class="fa fa-sort-alpha-asc"></i> Grading System</a></li>
                                                <li><a href="#"><i class="fa fa-list-alt"></i> Online Test</a></li>
                                                <li><a href="#"><i class="fa fa-file-text"></i> View Result</a></li>
                                                <li><a href="#"><i class="fa fa-upload"></i> Import Questions</a></li>
                                             </ul>
                                          </div>
                                       </div>
                                       <div class="col-md-3 col-sm-4 col-xs-12">
                                          <div class="menu-box">
                                             <ul>
                                                <li><a href="#"><i class="fa fa-check-square-o"></i> Student Attendance</a></li>
                                                <li><a href="#"><i class="fa fa-check-square"></i> Manage Student Attendance</a></li>
                                                <li><a href="#"><i class="fa fa-check-square"></i> Lecture Attendance</a></li>
                                             </ul>
                                          </div>
                                       </div>
                                       <div class="col-md-3 col-sm-4 col-xs-12">
                                          <div class="menu-box">
                                             <ul>
                                                <li><a href="#"><i class="fa fa-calendar-o"></i> Academics</a></li>
                                                <li><a href="#"><i class="fa fa-flag"></i> Event Management</a></li>
                                                <li><a href="#"><i class="fa fa-object-group"></i> Assignment</a></li>
                                             </ul>
                                          </div>
                                       </div>
                                       <div class="col-md-3 col-sm-4 col-xs-12">
                                          <div class="menu-box">
                                             <ul>
                                                <li><a href="#"><i class="fa fa-map-marker"></i> Placement</a></li>
                                                <li><a href="#"><i class="fa fa-user"></i> Recruiter</a></li>
                                                <li><a href="#"><i class="fa fa-search"></i> Jobs</a></li>
                                             </ul>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <!--./tab-pane-->
                                 <div id="hrms" class="tab-pane">
                                    <div class="visible-sm visible-xs menu-box-header">
                                       <button aria-label="Close" class="close" type="button">
                                          <span aria-hidden="true">×</span>
                                       </button>
                                       <h4><i class="fa fa-user"></i> Human Resource</h4>
                                    </div>
                                    <div class="row">
                                       <div class="col-md-3 col-sm-4 col-xs-12">
                                          <div class="menu-box">
                                             <ul>
                                                <li><a href="#"><i class="fa fa-user"></i> Employee Management</a></li>
                                                <li><a href="/employee/departments"><i class="fa fa-sitemap"></i> Department</a></li>
                                                <li><a href="/employee/designations"><i class="fa fa-signal"></i> Designation</a></li>
                                                <li><a href="/employee/create"><i class="fa fa-user-plus"></i> Add Employee</a></li>
                                                <li><a href="/employee/manage"><i class="fa fa-reorder"></i> Manage Employee</a></li>
                                                <li><a href="/employee/import"><i class="fa fa-upload"></i> Import Employee</a></li>
                                                <li><a href="#"><i class="fa fa-plus-square"></i> Shift Allocation</a></li>
                                                <li><a href="#"><i class="fa fa-gear"></i> Employee Settings</a></li>
                                             </ul>
                                          </div>
                                       </div>
                                       <div class="col-md-3 col-sm-4 col-xs-12">
                                          <div class="menu-box">
                                             <ul>
                                                <li><a href="#"><i class="fa fa-gears"></i> Employee Configuration</a></li>
                                                <li><a href="#"><i class="fa fa-clock-o"></i> Shift</a></li>
                                                <li><a href="#"><i class="fa fa-life-bouy"></i> Loan Type</a></li>
                                                <li><a href="#"><i class="fa fa-calendar"></i> Week Off</a></li>
                                                <li><a href="#"><i class="fa fa-calendar-o"></i> National Holiday</a></li>
                                             </ul>
                                          </div>
                                       </div>
                                       <div class="col-md-3 col-sm-4 col-xs-12">
                                          <div class="menu-box">
                                             <ul>

                                                <li><a href="#"><i class="fa fa-users"></i> Leave Reporting</a></li>
                                                @role(['teacher', 'hrms'])
                                                <li><a href="/employee/leave/application"><i class="fa fa-list-alt"></i> Leave Apply</a></li>
                                                @endrole
                                                @role(['admin'])
                                                <li><a href="/employee/manage/leave/type"><i class="fa fa-file-text-o"></i> Leave Management</a></li>
                                                <li><a href="/employee/manage/leave/type"><i class="fa fa-life-bouy"></i> Leave Type</a></li>
                                                <li><a href="/employee/manage/leave/structure"><i class="fa fa-clock-o"></i> Leave Structure</a></li>
                                                <li><a href="/employee/manage/leave/entitlement"><i class="fa fa-plus-square"></i> Leave Entitlement</a></li>
                                                <li><a href="/employee/manage/leave/application"><i class="fa fa-list-alt"></i> Leave Applications</a></li>
                                                @endrole
                                             </ul>
                                          </div>
                                       </div>
                                       <div class="col-md-3 col-sm-4 col-xs-12">
                                          <div class="menu-box">
                                             <ul>
                                                <li><a href="#"><i class="fa fa-check-square-o"></i> Attendance</a></li>
                                                <li><a href="#"><i class="glyphicon glyphicon-check"></i> Take Attendance</a></li>
                                                <li><a href="#"><i class="fa fa-reorder"></i> Manage Attendance</a></li>
                                             </ul>
                                          </div>
                                       </div>
                                       <div class="col-md-3 col-sm-4 col-xs-12">
                                          <div class="menu-box">
                                             <ul>
                                                <li><a href="#"><i class="fa fa-bullseye"></i> Payroll</a></li>
                                                <li><a href="#"><i class="glyphicon glyphicon-tasks"></i> Salary Component</a></li>
                                                <li><a href="#"><i class="fa fa-clock-o"></i> Salary Structure</a></li>
                                                <li><a href="#"><i class="fa fa-check-square-o"></i> Employee Salary Component</a></li>
                                                <li><a href="#"><i class="fa fa-money"></i> Employee Loan</a></li>
                                                <li><a href="#"><i class="fa fa-credit-card"></i> Salary Slip</a></li>
                                                <li><a href="#"><i class="fa fa-print"></i> Print Salary Slip</a></li>
                                                <li><a href="#"><i class="fa fa-bullhorn"></i> Publish Salary Slip</a></li>
                                             </ul>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <!--./tab-pane-->
                                 <div id="student" class="tab-pane">
                                    <div class="visible-sm visible-xs menu-box-header">
                                       <button aria-label="Close" class="close" type="button">
                                          <span aria-hidden="true">×</span>
                                       </button>
                                       <h4><i class="fa fa-users"></i> Student</h4>
                                    </div>
                                    <div class="row">
                                       <div class="col-md-3 col-sm-4 col-xs-12">
                                          <div class="menu-box">
                                             <ul>
                                                <li><a href="#"><i class="fa fa-users"></i> Student</a></li>
                                                <li><a href="#"><i class="fa fa-sort-alpha-asc"></i> Admission Category</a></li>
                                                <li><a href="/student/profile/create"><i class="fa fa-user-plus"></i> Add Student</a></li>
                                                <li><a href="/student/manage"><i class="fa fa-reorder"></i> Manage Student</a></li>
                                                <li><a href="/student/import"><i class="fa fa-upload"></i> Import Student</a></li>
                                                <li><a href="#"><i class="fa fa-info-circle"></i> Student Status</a></li>
                                                <li><a href="/student/promote"><i class="fa fa-exchange"></i> Promote Student</a></li>
                                                <li><a href="/student/student-waiver/show-waiver/list"><i class="fa fa-exchange"></i> Student Waiver List</a></li>
                                             </ul>
                                          </div>
                                       </div>
                                       <div class="col-md-3 col-sm-4 col-xs-12">
                                          <div class="menu-box">
                                             <ul>
                                                <li><a href="/admission"><i class="fa fa-user-plus"></i> Admission</a></li>
                                                <li><a href="/admission/application"><i class="fa fa-external-link"></i> Online Application</a></li>
                                                <li><a href="/admission/enquiry"><i class="fa fa-users"></i> Manage Enquiry</a></li>
                                                <li><a href="/admission/fees"><i class="fa fa-users"></i> Manage Fees</a></li>
                                                <li><a href="/admission/assessment"><i class="fa fa-users"></i> Manage Assessment</a></li>
                                                {{--<li><a href="#"><i class="fa fa-file-text-o"></i> Admission Letter</a></li>--}}
                                             </ul>
                                          </div>
                                       </div>
                                       <div class="col-md-3 col-sm-4 col-xs-12">
                                          <div class="menu-box">
                                             <ul>
                                                <li><a><i class="fa fa-user-plus"></i> Parents</a></li>
                                                <li><a href="/student/parent/manage"><i class="fa fa-users"></i> Manage Parents</a></li>
                                             </ul>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <!--./tab-pane-->
                                 <div id="fees" class="tab-pane">
                                    <div class="visible-sm visible-xs menu-box-header">
                                       <button aria-label="Close" class="close" type="button">
                                          <span aria-hidden="true">×</span>
                                       </button>
                                       <h4><i class="fa fa-money"></i> Fees</h4>
                                    </div>
                                    <div class="row">
                                       <div class="col-md-3 col-sm-4 col-xs-12">
                                          <div class="menu-box">
                                             <ul>
                                                <li><a href="/fees/"><i class="fa fa-money"></i> Fees</a></li>
                                                <li><a href="/fees/feeslist"><i class="fa fa-bank"></i>Fees List</a></li>
                                                <li><a href="/fees/invoice"><i class="fa fa-sort-alpha-asc"></i> Fees Invoice</a></li>
                                                <li><a href="/fees/paymenttransaction"><i class="fa fa-sort-alpha-asc"></i>Payment Transaction</a></li>
                                                <li><a href="/fees/addfees"><i class="fa fa-exchange"></i> Add Fees</a></li>
                                                <li><a href="/fees/invoice/pdf/demo/10"><i class="fa fa-exchange"></i>Bangla Fees Report</a></li>
                                             </ul>
                                          </div>
                                       </div>
                                       <div class="col-md-3 col-sm-4 col-xs-12">
                                          <div class="menu-box">
                                             <ul>
                                                <li><a href="{{url('accounting/accdashboard')}}"><i class="fa fa-money"></i> Accounting</a></li>
                                                <li><a href="{{url('accounting/accfyear')}}"><i class="fa fa-bank"></i> Financial Year</a></li>
                                                <li><a href="{{url('accounting/acchead')}}"><i class="fa fa-bank"></i> Ledger and Group</a></li>
                                                <li><a href="{{url('accounting/accsubhead')}}"><i class="fa fa-bank"></i> Sub Ledger</a></li>
                                                <li><a href="{{url('accounting/accbank')}}"><i class="fa fa-bank"></i> Banks</a></li>
                                                <li><a href="{{url('accounting/accvouchertype')}}"><i class="fa fa-bank"></i> Voucher Type</a></li>
                                                <li><a href="{{url('accounting/accvoucherentry')}}"><i class="fa fa-bank"></i> List of Voucher Entry</a></li>
                                                <li><a href="{{url('accounting/accvoucherentry/add')}}"><i class="fa fa-bank"></i> Voucher Entry</a></li>
                                                <li><a href="{{url('accounting/accfeescollection')}}"><i class="fa fa-bank"></i> Fees Collection</a></li>
                                             </ul>
                                          </div>
                                       </div>
                                       <div class="col-md-3 col-sm-4 col-xs-12">
                                          <div class="menu-box">
                                             <ul>
                                                <li><a href="{{url('accounting/accdashboard')}}"><i class="fa fa-money"></i> Accounting Reports</a></li>
                                                <li><a href="{{url('accounting/accreport/accdailybook')}}"><i class="fa fa-bank"></i> Day Book</a></li>
                                                <li><a href="{{url('accounting/accreport/accledgerbook')}}"><i class="fa fa-bank"></i> Ledger Book</a></li>
                                                <li><a href="{{url('accounting/accreport/accreceivepayment')}}"><i class="fa fa-bank"></i> Receive And Payment</a></li>
                                                <li><a href="{{url('accounting/accreport/acctrialbalance')}}"><i class="fa fa-bank"></i> Trial Balance</a></li>
                                                <li><a href="{{url('accounting/accreport/accbalancesheet')}}"><i class="fa fa-bank"></i> Balance Sheet</a></li>
                                                <li><a href="{{url('accounting/accreport/accprofitloss')}}"><i class="fa fa-bank"></i> Profit And loss</a></li>

                                             </ul>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <!--./tab-pane-->
                                 <div id="communication" class="tab-pane">
                                    <div class="visible-sm visible-xs menu-box-header">
                                       <button aria-label="Close" class="close" type="button">
                                          <span aria-hidden="true">×</span>
                                       </button>
                                       <h4><i class="fa fa-comments"></i> Communication</h4>
                                    </div>
                                    <div class="row">
                                       <div class="col-md-3 col-sm-4 col-xs-12">
                                          <div class="menu-box">
                                             <ul>
                                                <li><a href="javascript:void(0);"><i class="fa fa-comment"></i> SMS</a></li>
                                                <li><a href="/communication/sms/sms_credit"><i class="fa fa-comments-o"></i>SMS Credit</a></li>
                                                <li><a href="/communication/sms/sms_log"><i class="fa fa-comments-o"></i> SMS Log</a></li>
                                                <li><a href="/communication/sms/group"><i class="fa fa-comments-o"></i> Group SMS</a></li>
                                                <li><a href="/communication/sms/pending_sms"><i class="fa fa-comments-o"></i> Pending SMS</a></li>
                                             </ul>
                                          </div>
                                          <div class="menu-box">
                                             <ul>
                                                <li><a href="javascript:void(0);"><i class="fa fa-comment"></i> Notice and Events</a></li>
                                                <li><a href="/communication/notice/"><i class="fa fa-comments-o"></i> Notice </a></li>
                                                <li><a href="/communication/event"><i class="fa fa-file"></i>Event</a></li>
                                             </ul>
                                          </div>
                                       </div>
                                       <div class="col-md-3 col-sm-4 col-xs-12">
                                          <div class="menu-box">
                                             <ul>
                                                <li><a href="javascript:void(0);"><i class="fa fa-envelope-o"></i> Email</a></li>
                                                <li><a href="#"><i class="fa fa-comments-o"></i> Employee Email</a></li>
                                                <li><a href="#"><i class="fa fa-comments-o"></i> Student Email</a></li>
                                             </ul>
                                          </div>
                                       </div>
                                       <div class="col-md-3 col-sm-4 col-xs-12">
                                          <div class="menu-box">
                                             <ul>
                                                <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard Management</a></li>
                                                <li><a href="#"><i class="fa fa-list-alt"></i> Message of Day</a></li>
                                                <li><a href="#"><i class="fa fa-columns"></i> Notice</a></li>
                                             </ul>
                                          </div>
                                       </div>
                                       <div class="col-md-3 col-sm-4 col-xs-12">
                                          <div class="menu-box">
                                             <ul>
                                                <li><a href="#"><i class="fa fa-phone"></i> Telephone Diary</a></li>
                                                <li><a href="#"><i class="fa fa-phone-square"></i> Student Contact</a></li>
                                                <li><a href="#"><i class="fa fa-phone-square"></i> Employee Contact</a></li>
                                             </ul>
                                          </div>
                                       </div>
                                       <div class="col-md-3 col-sm-4 col-xs-12">
                                          <div class="menu-box">
                                             <ul>
                                                <li><a href="javascript:void(0);"><i class="fa fa-question-circle"></i> Helpdesk</a></li>
                                                <li><a href="#"><i class="fa fa-question"></i> Inquiry Subjects</a></li>
                                                <li><a href="#"><i class="fa fa-ticket"></i> Inquiry Tickets</a></li>
                                             </ul>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <!--./tab-pane-->
                                 <div id="reports" class="tab-pane">
                                    <div class="visible-sm visible-xs menu-box-header">
                                       <button aria-label="Close" class="close" type="button">
                                          <span aria-hidden="true">×</span>
                                       </button>
                                       <h4><i class="fa fa-line-chart"></i> Reports Center</h4>
                                    </div>
                                    <div class="row">
                                       <div class="col-md-3 col-sm-4 col-xs-12">
                                          <div class="menu-box">
                                             <ul>
                                                <li><a href="/reports/academics"><i class="fa fa-line-chart"></i>Academics</a></li>
                                                <li><a href="/reports/academics"><i class="fa fa-bar-chart"></i> Student Reports</a></li>
                                                <li><a href="/reports/academics"><i class="fa fa-bar-chart"></i> Teacher Reports</a></li>
                                                <li><a href="/reports/academics"><i class="fa fa-bar-chart"></i> Parents Reports</a></li>
                                             </ul>
                                          </div>
                                       </div>
                                       <div class="col-md-3 col-sm-4 col-xs-12">
                                          <div class="menu-box">
                                             <ul>
                                                <li><a href="/reports/academics"><i class="fa fa-line-chart"></i>ID Card</a></li>
                                                <li><a href="/reports/id-card"><i class="fa fa-bar-chart"></i> Student ID Cards</a></li>
                                             </ul>
                                          </div>
                                       </div>
                                       <div class="col-md-3 col-sm-4 col-xs-12">
                                          <div class="menu-box">
                                             <ul>
                                                <li><a href="/reports/attendance"><i class="fa fa-building-o"></i> Attendance</a></li>
                                                <li><a href="/reports/attendance"><i class="fa fa-users"></i> Student Attendance</a></li>
                                                <li><a href="/reports/attendance"><i class="fa fa-users"></i> Class Section Attendance</a></li>
                                                <li><a href="/reports/attendance"><i class="fa fa-users"></i> Student Absent Days Report</a></li>
                                             </ul>
                                          </div>
                                       </div>
                                       <div class="col-md-3 col-sm-4 col-xs-12">
                                          <div class="menu-box">
                                             <ul>
                                                <li><a href="/reports/result"><i class="fa fa-check-square-o"></i> Result</a></li>
                                                <li><a href="/reports/result"><i class="fa fa-file"></i> Report Card (Details)</a></li>
                                                <li><a href="/reports/result"><i class="fa fa-file"></i> Report Card (Summary)</a></li>
                                             </ul>
                                          </div>
                                       </div>
                                       <div class="col-md-3 col-sm-4 col-xs-12">
                                          <div class="menu-box">
                                             <ul>
                                                <li><a href="/reports/admission"><i class = "fa fa-calendar-o" aria-hidden="true"></i> Admission</a></li>
                                                <li><a href="/reports/admission"><i class = "fa fa-bank" aria-hidden="true"></i> Admission Reports</a></li>
                                             </ul>
                                          </div>
                                       </div>
                                       <div class="col-md-3 col-sm-4 col-xs-12">
                                          <div class="menu-box">
                                             <ul>
                                                <li><a href="/reports/fees"><i class="fa fa-check-square-o"></i> Fees And Invoice</a></li>
                                                <li><a href="/fees/report/date-wise-fees"><i class="fa fa-money"></i>Fees Reports (Daily)</a></li>
                                                <li><a href="/reports/fees"><i class="fa fa-money"></i> Fees Report (Details)</a></li>
                                                <li><a href="/reports/fees/monthly/report"><i class="fa fa-money"></i> Fees Monthly Report</a></li>
                                                <li><a href="/reports/invoice"><i class="fa fa-money"></i> Invoice Report</a></li>
                                                <li><a href="/fees/report/index"><i class="fa fa-money"></i> Fees Reports (Level Wise)</a></li>
                                             </ul>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <!--./tab-pane-->
                                 <div id="administration" class="tab-pane">
                                    <div class="visible-sm visible-xs menu-box-header">
                                       <button aria-label="Close" class="close" type="button">
                                          <span aria-hidden="true">×</span>
                                       </button>
                                       <h4><i class="fa fa-wrench"></i> Administration</h4>
                                    </div>
                                    <div class="row">
                                       <div class="col-md-3 col-sm-4 col-xs-12">
                                          <div class="menu-box">
                                             <ul>
                                                <li><a href="#"><i class="fa fa-building"></i> Hostel</a></li>
                                                <li><a href="#"><i class="fa fa-sort-alpha-asc"></i> Hostel Type</a></li>
                                                <li><a href="#"><i class="fa fa-building-o"></i> Hostel Details</a></li>
                                                <li><a href="#"><i class="fa fa-sitemap"></i> Hostel Blocks</a></li>
                                                <li><a href="#"><i class="fa fa-building"></i> Room Details</a></li>
                                                <li><a href="#"><i class="fa fa-money"></i> Fees Structure</a></li>
                                                <li><a href="#"><i class="fa fa-user-plus"></i> Student Registration</a></li>
                                                <li><a href="#"><i class="fa fa-users"></i> Registered Students</a></li>
                                             </ul>
                                          </div>
                                       </div>
                                       <div class="col-md-3 col-sm-4 col-xs-12">
                                          <div class="menu-box">
                                             <ul>
                                                <li><a href="#"><i class="fa fa-bus"></i> Transport</a></li>
                                                <li><a href="#"><i class="fa fa-ambulance"></i> Vehicle Details</a></li>
                                                <li><a href="#"><i class="fa fa-user-plus"></i> Driver Details</a></li>
                                                <li><a href="#"><i class="fa fa-calendar"></i> Manage Route</a></li>
                                                <li><a href="#"><i class="fa fa-list-alt"></i> Student Bus Allocation</a></li>
                                                <li><a href="#"><i class="fa fa-money"></i> Fees Collect</a></li>
                                             </ul>
                                          </div>
                                       </div>
                                       <div class="col-md-3 col-sm-4 col-xs-12">
                                          <div class="menu-box">
                                             <ul>
                                                <li><a href="#"><i class="fa fa-book"></i> Accounts</a></li>
                                                <li><a href="#"><i class="fa fa-file-text"></i> Exp. Category</a></li>
                                                <li><a href="#"><i class="fa fa-file-text"></i> Payable</a></li>
                                                <li><a href="#"><i class="fa fa-file"></i> Expenses</a></li>
                                             </ul>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <!--./tab-pane-->
                                 <div id="document" class="tab-pane">
                                    <div class="visible-sm visible-xs menu-box-header">
                                       <button aria-label="Close" class="close" type="button">
                                          <span aria-hidden="true">×</span>
                                       </button>
                                       <h4><i class="fa fa-file-text-o"></i> Document</h4>
                                    </div>
                                    <div class="row">
                                       <div class="col-md-3 col-sm-4 col-xs-12">
                                          <div class="menu-box">
                                             <ul>
                                                <li><a href="javascript:void(0);"><i class="fa fa-certificate"></i> Certificate/Letters</a></li>
                                                <li><a href="#"><i class="fa fa-file-text"></i> Manage Certificate/Letter</a></li>
                                                <li><a href="#"><i class="fa fa-server"></i> Student Certificate/Letter</a></li>
                                                <li><a href="#"><i class="fa fa-server"></i> Employee Certificate/Letter</a></li>
                                             </ul>
                                          </div>
                                       </div>
                                       <div class="col-md-3 col-sm-4 col-xs-12">
                                          <div class="menu-box">
                                             <ul>
                                                <li><a href="javascript:void(0);"><i class="fa fa-files-o"></i> Manage Documents</a></li>
                                                <li><a href="#"><i class="fa fa-reorder"></i> Document Category></a></li>
                                                <li><a href="#"><i class="fa fa-file-o"></i> Student Docs</a></li>
                                                <li><a href="#"><i class="fa fa-file-o"></i> Employee Docs</a></li>
                                             </ul>
                                          </div>
                                       </div>
                                       <div class="col-md-3 col-sm-4 col-xs-12">
                                          <div class="menu-box">
                                             <ul>
                                                <li><a href="javascript:void(0);"><i class="fa fa-share-alt"></i> File Sharing</a></li>
                                                <li><a href="#"><i class="fa fa-list"></i> File Category</a></li>
                                                <li><a href="#"><i class="fa fa-file-text"></i> File Uploads</a></li>
                                             </ul>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <!--./tab-pane-->
                                 <div id="library" class="tab-pane">
                                    <div class="visible-sm visible-xs menu-box-header">
                                       <button aria-label="Close" class="close" type="button">
                                          <span aria-hidden="true">×</span>
                                       </button>
                                       <h4><i class="fa fa-university"></i> Library</h4>
                                    </div>
                                    <div class="row">
                                       <div class="col-md-3 col-sm-4 col-xs-12">
                                          <div class="menu-box">
                                             <ul>
                                                <li><a href="/library/default/index"><i class="fa fa-university"></i> Library</a></li>
                                                <li><a href="/library/library-book-category/index"><i class="fa fa-sort-alpha-asc"></i> Book Category</a></li>
                                                <li><a href="/library/library-book-shelf/index"><i class="glyphicon glyphicon-object-align-bottom"></i>Book Shelf</a></li>
                                                <li><a href="/library/library-cupboard-shelf/index"><i class="glyphicon glyphicon-equalizer"></i> Cup Board Shelf</a></li>
                                                <li><a href="/library/library-book-vendor/index"><i class="fa fa-cart-plus"></i> Book Vendor</a></li>
                                                <li><a href="/library/library-book-status/index"><i class="glyphicon glyphicon-tag"></i> Book Status</a></li>
                                                <li><a href="/library/library-book/index"><i class="glyphicon glyphicon-book"></i> Books</a></li>
                                                <li><a href="/library/library-borrow-transaction/index"><i class="fa fa-book"></i> Issue Book</a></li>
                                                <li><a href="/library/library-borrow-transaction/borrower"><i class="fa fa-reply-all"></i> Return/Renew Book</a></li>
                                                <li><a href="/library/library-fine-master/index"><i class="fa fa-eject"></i> Fine</a></li>
                                             </ul>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <!--./tab-pane-->
                                 <div id="settings" class="tab-pane">
                                    <div class="visible-sm visible-xs menu-box-header">
                                       <button aria-label="Close" class="close" type="button">
                                          <span aria-hidden="true">×</span>
                                       </button>
                                       <h4><i class="fa fa-cogs"></i> Settings</h4>
                                    </div>
                                    <div class="row">
                                       <div class="col-md-3 col-sm-4 col-xs-12">
                                          <div class="menu-box">
                                             <ul>
                                                <li><a href="/default/index"><i class="fa fa-cogs"></i> Configuration</a></li>
                                                <li><a href="{{url('setting/country')}}"><i class="fa fa-globe"></i> Country</a></li>
                                                <li><a href="{{url('setting/state')}}"><i class="fa fa-map-marker"></i> State/Province</a></li>
                                                <li><a href="{{url('setting/city')}}"><i class="fa fa-building-o"></i> City/Town</a></li>
                                                <li><a href="/setting/language/index"><i class="fa fa-language"></i> Languages</a></li>
                                                <li><a href="/nationality/index"><i class="fa fa-flag-checkered"></i> Nationality</a></li>
                                                <li><a href="{{url('setting')}}"><i class="fa fa-bank"></i> Institute</a></li>
                                                <li><a href="/setting/institute/property"><i class="fa fa-bank"></i> Institute Property</a></li>
                                             </ul>
                                          </div>

                                          <div class="menu-box">
                                             <ul>
                                                <li><a href="#"><i class="fa fa-envelope"></i> Sms</a></li>
                                                <li><a href="/setting/sms/setting/getway"><i class="fa fa-cogs"></i> Sms Settings</a></li>
                                                <li><a href="/setting/sms/getway/list"><i class="fa fa-cogs"></i> Sms Gateway List</a></li>
                                             </ul>
                                          </div>
                                       </div>
                                       <div class="col-md-3 col-sm-4 col-xs-12">
                                          <div class="menu-box">
                                             <ul>
                                                <li><a href="#"><i class="fa fa-user"></i> Manage Users</a></li>
                                                <li><a href="#"><i class="fa fa-key"></i> Student Reset Password</a></li>
                                                <li><a href="#"><i class="fa fa-key"></i> Employee Reset Password</a></li>
                                                <li><a href="#"><i class="fa fa-key"></i> Manage Parent</a></li>
                                                <li><a href="/forgot-password/users"><i class="fa fa-key"></i> Users Reset Password</a></li>

                                             </ul>
                                          </div>
                                       </div>
                                       <div class="col-md-3 col-sm-4 col-xs-12">
                                          <div class="menu-box">
                                             <ul>
                                                <li><a href="#"><i class="fa fa-user-secret"></i> Manage User Rights</a></li>
                                                <li><a href="/setting/institute/campus/assign"><i class="fa fa-male"></i> Assignment</a></li>
                                                <li><a href="/setting/rights/role"><i class="fa fa-user-times"></i> Role</a></li>
                                             </ul>
                                          </div>
                                       </div>
                                       <div class="col-md-3 col-sm-4 col-xs-12">
                                          <div class="menu-box">
                                             <ul>
                                                <li><a href="#"><i class="fa fa-cog"></i> Additional</a></li>
                                                <li><a href="#"><i class="fa fa-cogs"></i> System Settings</a></li>
                                                <li><a href="#"><i class="fa fa-bell-o"></i> Notification Settings</a></li>
                                                <li><a href="#"><i class="fa fa-database"></i> Backup</a></li>
                                                <li><a href="/setting/audit/history"><i class="fa fa-history"></i> Audit History</a></li>
                                             </ul>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <!--./tab-pane-->
                              </div>
                              <!-- end col -->
                           </div>
                           <!-- /.tabbable -->
                        </div>
                        <!-- end esmenu-content -->
                     </div>
                  </li>
               </ul>
               <!-- dropdown-menu -->
         </li>
         <!-- end mega menu -->
      </ul>
      <!--./navbar-nav-->
   </div>
   <!--./navbar-collapse-->
</div>

