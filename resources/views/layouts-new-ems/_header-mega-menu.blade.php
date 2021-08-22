
<nav class="navbar navbar-default main-menu" role="navigation" style="min-height: 50px; background: #48b04f">
   <div class="container-fluid">
      <div class="navbar-header">
         <!-- <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>-->
         <h4 class="head-text"><span class="fa fa-dashboard icon-margin"></span> Dashboard</h4>
      </div>
      <div class="">

         <!-- Left nav -->
         <ul class="nav navbar-nav">
            <li><a href="#" class="main-menu-style"><span class="fa fa-th-large icon-margin"></span> Menu </a>
               <ul class="dropdown-menus main-dropdown">
                  <li><a href="#"><span class="fa fa-book icon-margin"></span> Academic </span><span class="caret"></span></a>


                     <ul class="dropdown-menus">
                        <li><a href="#"><i class="fa fa-line-chart icon-margin"></i> Course Management<span class="caret"></span></a>
                           <ul class="dropdown-menus">
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
                        </li>
                        <li><a href="{{url('academics/roomcategory')}}"><i class="fa fa-calendar-o"></i> Timetable <span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="{{url('academics/roomcategory')}}"><i class="fa fa-sort-alpha-asc"></i> Room Category</a></li>
                              <li><a href="{{url('academics/roommaster')}}"><i class="fa fa-object-ungroup"></i> Room Master</a></li>
                              <li><a href="{{url('/academics/timetable/manage')}}"><i class="fa fa-calendar"></i> Timetable</a></li>
                           </ul>
                        </li>
                        <li><a href="#"><i class="fa fa-list"></i> Online Test<span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="#"><i class="fa fa-link"></i> Question Category</a></li>
                              <li><a href="#"><i class="fa fa-question-circle"></i> Questions</a></li>
                              <li><a href="#"><i class="fa fa-sort-alpha-asc"></i> Grading System</a></li>
                              <li><a href="#"><i class="fa fa-list-alt"></i> Online Test</a></li>
                              <li><a href="#"><i class="fa fa-file-text"></i> View Result</a></li>
                              <li><a href="#"><i class="fa fa-upload"></i> Import Questions</a></li>
                           </ul>
                        </li>
                        <li><a href="#"><i class="fa fa-check-square-o"></i> Student Attendance<span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="#"><i class="fa fa-check-square"></i> Manage Student Attendance</a></li>
                              <li><a href="#"><i class="fa fa-check-square"></i> Lecture Attendance</a></li>
                           </ul>
                        </li>
                        <li><a href="#"><i class="fa fa-calendar-o"></i> Academics<span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="#"><i class="fa fa-flag"></i> Event Management</a></li>
                              <li><a href="#"><i class="fa fa-object-group"></i> Assignment</a></li>
                           </ul>
                        </li>

                        <li><a href="#"><i class="fa fa-map-marker"></i> Placement<span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="#"><i class="fa fa-user"></i> Recruiter</a></li>
                              <li><a href="#"><i class="fa fa-search"></i> Jobs</a></li>
                           </ul>
                        </li>
                     </ul>

                  </li>
                  <li><a href="#"><span class="fa fa-users icon-margin"></span> Human Resource </span><span class="caret"></span></a>
                     <ul class="dropdown-menus">
                        <li><a href="#"><i class="fa fa-user"></i> Employee Management<span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="/employee/departments"><i class="fa fa-sitemap"></i> Department</a></li>
                              <li><a href="/employee/designations"><i class="fa fa-signal"></i> Designation</a></li>
                              <li><a href="/employee/create"><i class="fa fa-user-plus"></i> Add Employee</a></li>
                              <li><a href="/employee/manage"><i class="fa fa-reorder"></i> Manage Employee</a></li>
                              <li><a href="/employee/import"><i class="fa fa-upload"></i> Import Employee</a></li>
                              <li><a href="#"><i class="fa fa-plus-square"></i> Shift Allocation</a></li>
                              <li><a href="#"><i class="fa fa-gear"></i> Employee Settings</a></li>
                           </ul>
                        </li>
                        <li><a href="#"><i class="fa fa-gears"></i> Employee Configuration<span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="#"><i class="fa fa-clock-o"></i> Shift</a></li>
                              <li><a href="#"><i class="fa fa-life-bouy"></i> Loan Type</a></li>
                              <li><a href="#"><i class="fa fa-calendar"></i> Week Off</a></li>
                              <li><a href="#"><i class="fa fa-calendar-o"></i> National Holiday</a></li>
                           </ul>
                        </li>
                        <li><a href="/employee/manage/leave/type"><i class="fa fa-file-text-o"></i> Leave Management<span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="/employee/manage/leave/type"><i class="fa fa-life-bouy"></i> Leave Type</a></li>
                              <li><a href="/employee/manage/leave/structure"><i class="fa fa-clock-o"></i> Leave Structure</a></li>
                              <li><a href="/employee/manage/leave/entitlement"><i class="fa fa-plus-square"></i> Leave Entitlement</a></li>
                              <li><a href="#"><i class="fa fa-users"></i> Leave Reporting</a></li>
                              <li><a href="#"><i class="fa fa-list-alt"></i> Leave Applications</a></li>
                           </ul>
                        </li>
                        <li><a href="#"><i class="fa fa-check-square-o"></i> Attendance<span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="#"><i class="glyphicon glyphicon-check"></i> Take Attendance</a></li>
                              <li><a href="#"><i class="fa fa-reorder"></i> Manage Attendance</a></li>
                           </ul>
                        </li>
                        <li><a href="#"><i class="fa fa-bullseye"></i> Payroll<span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="#"><i class="glyphicon glyphicon-tasks"></i> Salary Component</a></li>
                              <li><a href="#"><i class="fa fa-clock-o"></i> Salary Structure</a></li>
                              <li><a href="#"><i class="fa fa-check-square-o"></i> Employee Salary Component</a></li>
                              <li><a href="#"><i class="fa fa-money"></i> Employee Loan</a></li>
                              <li><a href="#"><i class="fa fa-credit-card"></i> Salary Slip</a></li>
                              <li><a href="#"><i class="fa fa-print"></i> Print Salary Slip</a></li>
                              <li><a href="#"><i class="fa fa-bullhorn"></i> Publish Salary Slip</a></li>
                           </ul>
                        </li>
                     </ul>


                  </li>
                  <li><a href="#"><span class="fa fa-user icon-margin"></span> Students </span><span class="caret"></span></a>

                     <ul class="dropdown-menus">
                        <li><a href="#"><i class="fa fa-users"></i> Student<span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="#"><i class="fa fa-sort-alpha-asc"></i> Admission Category</a></li>
                              <li><a href="/student/profile/create"><i class="fa fa-user-plus"></i> Add Student</a></li>
                              <li><a href="/student/manage"><i class="fa fa-reorder"></i> Manage Student</a></li>
                              <li><a href="/student/import"><i class="fa fa-upload"></i> Import Student</a></li>
                              <li><a href="#"><i class="fa fa-info-circle"></i> Student Status</a></li>
                              <li><a href="/student/promote"><i class="fa fa-exchange"></i> Promote Student</a></li>
                              <li><a href="/student/student-waiver/show-waiver/list"><i class="fa fa-exchange"></i> Student Waiver List</a></li>
                           </ul>
                        </li>
                        <li><a href="/admission"><i class="fa fa-user-plus"></i> Admission<span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="/admission/application"><i class="fa fa-external-link"></i> Online Application</a></li>
                              <li><a href="/admission/enquiry"><i class="fa fa-users"></i> Manage Enquiry</a></li>
                              <li><a href="/admission/fees"><i class="fa fa-users"></i> Manage Fees</a></li>
                              <li><a href="/admission/assessment"><i class="fa fa-users"></i> Manage Assessment</a></li>
                           </ul>
                        </li>
                        <li><a><i class="fa fa-user-plus"></i> Parents<span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="/student/parent/manage"><i class="fa fa-users"></i> Manage Parents</a></li>
                           </ul>
                        </li>
                     </ul>

                  </li>
                  <li><a href="#"><span class="fa fa-money icon-margin"></span> Fees </span><span class="caret"></span></a>

                     <ul class="dropdown-menus">
                        <li><a href="/fees/"><i class="fa fa-money"></i> Fees<span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="/fees/feeslist"><i class="fa fa-bank"></i>Fees List</a></li>
                              <li><a href="/fees/invoice"><i class="fa fa-sort-alpha-asc"></i> Fees Invoice</a></li>
                              <li><a href="/fees/paymenttransaction"><i class="fa fa-sort-alpha-asc"></i>Payment Transaction</a></li>
                              <li><a href="/fees/addfees"><i class="fa fa-exchange"></i> Add Fees</a></li>
                              <li><a href="/fees/invoice/pdf/demo/10"><i class="fa fa-exchange"></i>Bangla Fees Report</a></li>
                           </ul>
                        </li>
                        <li><a href="{{url('accounting/accdashboard')}}"><i class="fa fa-money"></i> Accounting<span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="{{url('accounting/accfyear')}}"><i class="fa fa-bank"></i> Financial Year</a></li>
                              <li><a href="{{url('accounting/acchead')}}"><i class="fa fa-bank"></i> Ledger and Group</a></li>
                              <li><a href="{{url('accounting/accsubhead')}}"><i class="fa fa-bank"></i> Sub Ledger</a></li>
                              <li><a href="{{url('accounting/accbank')}}"><i class="fa fa-bank"></i> Banks</a></li>
                              <li><a href="{{url('accounting/accvouchertype')}}"><i class="fa fa-bank"></i> Voucher Type</a></li>
                              <li><a href="{{url('accounting/accvoucherentry')}}"><i class="fa fa-bank"></i> List of Voucher Entry</a></li>
                              <li><a href="{{url('accounting/accvoucherentry/add')}}"><i class="fa fa-bank"></i> Voucher Entry</a></li>
                              <li><a href="{{url('accounting/accfeescollection')}}"><i class="fa fa-bank"></i> Fees Collection</a></li>
                           </ul>
                        </li>
                        <li><a href="{{url('accounting/accdashboard')}}"><i class="fa fa-money"></i> Accounting Reports<span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="{{url('accounting/accreport/accdailybook')}}"><i class="fa fa-bank"></i> Day Book</a></li>
                              <li><a href="{{url('accounting/accreport/accledgerbook')}}"><i class="fa fa-bank"></i> Ledger Book</a></li>
                              <li><a href="{{url('accounting/accreport/accreceivepayment')}}"><i class="fa fa-bank"></i> Receive And Payment</a></li>
                              <li><a href="{{url('accounting/accreport/acctrialbalance')}}"><i class="fa fa-bank"></i> Trial Balance</a></li>
                              <li><a href="{{url('accounting/accreport/accbalancesheet')}}"><i class="fa fa-bank"></i> Balance Sheet</a></li>
                              <li><a href="{{url('accounting/accreport/accprofitloss')}}"><i class="fa fa-bank"></i> Profit And loss</a></li>

                           </ul>
                        </li>
                     </ul>

                  </li>
                  <li><a href="#"><span class="fa fa-comment icon-margin"></span> Communication </span><span class="caret"></span></a>

                     <ul class="dropdown-menus">
                        <li><a href="javascript:void(0);"><i class="fa fa-comment"></i> SMS<span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="/communication/sms/sms_credit"><i class="fa fa-comments-o"></i>SMS Credit</a></li>
                              <li><a href="/communication/sms/sms_log"><i class="fa fa-comments-o"></i> SMS Log</a></li>
                              <li><a href="/communication/sms/group"><i class="fa fa-comments-o"></i> Group SMS</a></li>
                              <li><a href="/communication/sms/pending_sms"><i class="fa fa-comments-o"></i> Pending SMS</a></li>
                           </ul>
                        </li>
                        <li><a href="javascript:void(0);"><i class="fa fa-comment"></i> Notice<span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="/communication/notice/"><i class="fa fa-comments-o"></i> Notice </a></li>
                           </ul>
                        </li>
                        <li><a href="javascript:void(0);"><i class="fa fa-envelope-o"></i> Email<span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="#"><i class="fa fa-comments-o"></i> Employee Email</a></li>
                              <li><a href="#"><i class="fa fa-comments-o"></i> Student Email</a></li>
                           </ul>
                        </li>
                        <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard Management<span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="#"><i class="fa fa-list-alt"></i> Message of Day</a></li>
                              <li><a href="#"><i class="fa fa-columns"></i> Notice</a></li>
                           </ul>
                        </li>
                        <li><a href="#"><i class="fa fa-phone"></i> Telephone Diary<span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="#"><i class="fa fa-phone-square"></i> Student Contact</a></li>
                              <li><a href="#"><i class="fa fa-phone-square"></i> Employee Contact</a></li>
                           </ul>
                        </li>

                        <li><a href="javascript:void(0);"><i class="fa fa-question-circle"></i> Helpdesk<span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="#"><i class="fa fa-question"></i> Inquiry Subjects</a></li>
                              <li><a href="#"><i class="fa fa-ticket"></i> Inquiry Tickets</a></li>
                           </ul>
                        </li>

                     </ul>
                  </li>

                  </li>
                  <li><a href="#"><span class="fa fa-line-chart icon-margin"></span> Reports and Printing </span><span class="caret"></span></a>

                     <ul class="dropdown-menus">
                        <li><a href="/reports/academics"><i class="fa fa-line-chart"></i>Academics<span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="/reports/academics"><i class="fa fa-bar-chart"></i> Student Reports</a></li>
                              <li><a href="/reports/academics"><i class="fa fa-bar-chart"></i> Teacher Reports</a></li>
                              <li><a href="/reports/academics"><i class="fa fa-bar-chart"></i> Parents Reports</a></li>
                           </ul>
                        </li>
                        <li><a href="/reports/attendance"><i class="fa fa-building-o"></i> Attendance<span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="/report/attendance"><i class="fa fa-users"></i> Student Attendance</a></li>
                              <li><a href="/report/attendance"><i class="fa fa-users"></i> Class Section Attendance</a></li>
                              <li><a href="/report/attendance"><i class="fa fa-users"></i> Student Absent Days Report</a></li>
                           </ul>
                        </li>
                        <li><a href="/reports/result"><i class="fa fa-check-square-o"></i> Result<span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="/reports/result"><i class="fa fa-file"></i> Report Card (Details)</a></li>
                              <li><a href="/reports/result"><i class="fa fa-file"></i> Report Card (Summary)</a></li>
                           </ul>
                        </li>
                        <li><a href="/reports/admission"><i class = "fa fa-calendar-o" aria-hidden="true"></i> Admission<span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="/reports/admission"><i class = "fa fa-bank" aria-hidden="true"></i> Admission Reports</a></li>
                           </ul>
                        </li>
                        <li><a href="/reports/fees"><i class="fa fa-check-square-o"></i> Fees And Invoice<span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="/fees/report/date-wise-fees"><i class="fa fa-money"></i>Fees Reports (Daily)</a></li>
                              <li><a href="/reports/fees"><i class="fa fa-money"></i> Fees Report (Details)</a></li>
                              <li><a href="/reports/invoice"><i class="fa fa-money"></i> Invoice Report</a></li>
                              <li><a href="/fees/report/index"><i class="fa fa-money"></i> Fees Reports (Level Wise)</a></li>
                           </ul>
                        </li>
                     </ul>
                  </li>
                  <li><a href="#"><span class="fa fa-wrench icon-margin"></span> Administrations </span><span class="caret"></span></a>
                     <ul class="dropdown-menus">
                        <li><a href="#"><i class="fa fa-building icon-margin"></i> Hostel<span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="#"> Hostel Type</a></li>
                              <li><a href="#"> Hostel Details</a></li>
                              <li><a href="#"> Hostel Blocks</a></li>
                              <li><a href="#"> Room Details</a></li>
                              <li><a href="#"> Fees Structure</a></li>
                              <li><a href="#"> Student Registration</a></li>
                              <li><a href="#"> Registered Students</a></li>
                           </ul>
                        </li>
                        <li><a href="#"><i class="fa fa-bus icon-margin"></i> Transport<span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="#"> Vehicle Details</a></li>
                              <li><a href="#"> Driver Details</a></li>
                              <li><a href="#"> Manage Route</a></li>
                              <li><a href="#"> Student Bus Allocation</a></li>
                              <li><a href="#"> Fees Collect</a></li>
                           </ul>
                        </li>
                        <li><a href="#"><i class="fa fa-book icon-margin"></i> Accounts<span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="#"> Exp. Category</a></li>
                              <li><a href="#"> Payable</a></li>
                              <li><a href="#"> Expenses</a></li>
                           </ul>
                        </li>
                     </ul>
                  </li>
                  <li><a href="#"><span class="fa fa-file-text icon-margin"></span> Document </span><span class="caret"></span></a>
                     <ul class="dropdown-menus">
                        <li><a href="#"><i class="fa fa-certificate icon-margin"></i> Certificate/Letters<span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="#"> Manage Certificate/Letter</a></li>
                              <li><a href="#"> Student Certificate/Letter</a></li>
                              <li><a href="#"> Employee Certificate/Letter</a></li>
                           </ul>
                        </li>
                        <li><a href="#"><i class="fa fa-file-archive-o icon-margin"></i> Manage Documents<span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="#"> Document Category</a></li>
                              <li><a href="#"> Student Docs</a></li>
                              <li><a href="#"> Employee Docs</a></li>
                           </ul>
                        </li>
                        <li><a href="#"><i class="fa fa-share-alt icon-margin"></i> File Sharing<span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="#"> File Category</a></li>
                              <li><a href="#"> File Uploads</a></li>
                           </ul>
                        </li>
                     </ul>
                  </li>
                  <li><a href=""><span class="fa fa-university icon-margin"> </span> Librarys<span class="caret"></span></a>
                     <ul class="dropdown-menus">
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
                  </li>
                  <li><a href="#"><span class="fa fa-cog icon-margin"></span> Setting </span><span class="caret"></span></a>
                     <ul class="dropdown-menus" >
                        <li><a href="/default/index"><i class="fa fa-cogs"></i> Configuration<span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="{{url('setting/country')}}"><i class="fa fa-globe"></i> Country</a></li>
                              <li><a href="{{url('setting/state')}}"><i class="fa fa-map-marker"></i> State/Province</a></li>
                              <li><a href="{{url('setting/city')}}"><i class="fa fa-building-o"></i> City/Town</a></li>
                              <li><a href="/setting/language/index"><i class="fa fa-language"></i> Languages</a></li>
                              <li><a href="{{url('setting')}}"><i class="fa fa-bank"></i> Institute</a></li>
                              <li><a href="/setting/institute/property"><i class="fa fa-bank"></i> Institute Property</a></li>
                           </ul>
                        </li>
                        <li><a href="#"><i class="fa fa-envelope"></i> Sms<span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="/setting/sms/setting/getway"><i class="fa fa-cogs"></i> Sms Settings</a></li>
                              <li><a href="/setting/sms/getway/list"><i class="fa fa-cogs"></i> Sms Gateway List</a></li>
                           </ul>
                        </li>
                        <li><a href="#"> Manage Users <span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="/setting/institute/campus/assign"><i class="fa fa-male"></i> Assignment</a></li>
                              <li><a href="/setting/rights/role"><i class="fa fa-user-times"></i> Role</a></li>
                           </ul>
                        </li>
                        <li><a href="#"><i class="fa fa-user-secret"></i> Manage User Rights<span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="#">Assignment</a></li>
                              <li><a href="#">Role</a></li>
                           </ul>
                        </li>
                        <li><a href="#"><i class="fa fa-cog"></i> Additiona<span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="#"><i class="fa fa-cogs"></i> System Settings</a></li>
                              <li><a href="#"><i class="fa fa-bell-o"></i> Notification Settings</a></li>
                              <li><a href="#"><i class="fa fa-database"></i> Backup</a></li>
                              <li><a href="/setting/audit/history"><i class="fa fa-history"></i> Audit History</a></li>
                           </ul>
                        </li>
                     </ul>
                  </li>
               </ul>
            </li>
         </ul>
      </div>
</nav><!--/.nav-collapse -->
<div class=" clearfix"></div>