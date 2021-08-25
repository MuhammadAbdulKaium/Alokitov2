<style>
   @php $headerBgColor=institute_property("Header") @endphp
   .main-menu-headerbg {
      @if(!empty($headerBgColor->attribute_value))
         background:{{ $headerBgColor->attribute_value }} !important;
      @else
background:#0b460b !important;
   }
   @endif
   .head-text>a {
      color: #fff;
      font-size: 14px;
      font-weight: bold;
   }
</style>

@php $user = Auth::user(); @endphp

<nav class="navbar navbar-default main-menu main-menu-headerbg" role="navigation" style="min-height: 50px;">
   <div class="container-fluid">
      <div class="navbar-header">
         <!-- <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>-->
{{--         @role(['super-admin','admin'])--}}
{{--         <h4 class="head-text"><a href="{{URL::to('home')}}">Dashboard</a></h4>--}}
{{--         @endrole--}}
      </div>
      <div class="">
      @if (!Request::is('/'))
         {{--header menu permission according to the role--}}
         @role(['super-admin','admin'])
         <ul class="nav navbar-nav">

            <li><a href="#" class="main-menu-style"><span class="fa fa-th-large icon-margin"></span> Menu </a>
               <ul class="dropdown-menus main-dropdown">
                  <li><a href="#"><span class="fa fa-book icon-margin"></span> Academics <span class="caret"></span></a>

                     <ul class="dropdown-menus">
                        <li><a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> SOP Setup <span class="caret"></span></a>

                           <ul class="dropdown-menus">
                              {{--                        <li><a href="#"><i class="fa fa-line-chart icon-margin"></i> Course Management<span class="caret"></span></a>--}}
                              {{--                           <ul class="dropdown-menus">--}}
                              <li><a href="{{url('academics/academic-year')}}"><i class="fa fa-calendar"></i> Academic Year</a></li>
                              <li><a href="{{url('academics/admission-year')}}"><i class="fa fa-calendar"></i> Admission Year</a></li>
                              <li><a href="{{url('academics/academic-level')}}"><i class="fa fa-graduation-cap"></i> Academic Level</a></li>
                              <li><a href="{{url('academics/semester')}}"><i class="fa fa-info-circle"></i> Term</a></li>
                              <li><a href="{{url('academics/division')}}"><i class="fa fa-info-circle"></i> Group</a></li>
                              <li><a href="{{url('academics/subject')}}"><i class="fa fa-book"></i> Subject</a></li>
                              <li><a href="{{url('academics/subject/group')}}"><i class="fa fa-book"></i> Subject Group</a></li>
                              <li><a href="{{url('academics/batch')}}"><i class="fa fa-sitemap"></i> Batch</a></li>
                              <li><a href="{{url('academics/section')}}"><i class="fa fa-sitemap"></i> Section</a></li>
                              <li><a href="{{url('academics/physical/rooms')}}"><i class="fa fa-sitemap"></i> Physical Rooms</a></li>
                              <li><a href=""><i class="fa fa-sitemap"></i> Exam <span class="caret"></span></a>
                                 <ul class="dropdown-menus">
                                    <li><a href="{{url('/academics/exam-category/exam')}}"><i class="fa fa-link"></i> Exam Set UP</a></li>
                                    <li><a href="{{url('/academics/exam/marks')}}"><i class="fa fa-question-circle"></i> Exam Marks</a></li>
                                    <li><a href="{{url('/academics/exam/schedules')}}"><i class="fa fa-sort-alpha-asc"></i> Exam Schedule</a></li>
                                    <li><a href="{{url('/academics/exam/attendance')}}"><i class="fa fa-sort-alpha-asc"></i> Exam Attendance</a></li>
                                    <li><a href="{{url('/academics/exam/seatPlan')}}"><i class="fa fa-sort-alpha-asc"></i> Exam Seat Plan</a></li>
                                    <li><a href="{{url('/academics/exam/marks/entry')}}"><i class="fa fa-sort-alpha-asc"></i> Exam Marks Entry</a></li>
                                 </ul>
                              </li>
                              <li><a href="{{url('academics/manage')}}"><i class="fa fa-book "></i>Manage Academics</a></li>
                              <li><a href="{{url('academics/manage/assessments/grade-setup')}}"><i class="fa fa-book "></i>Manage Assessments</a></li>
                              {{--                           </ul>--}}
                              {{--                        </li>--}}
                              <li><a href="{{url('/academics/timetable/manage')}}"><i class="fa fa-calendar"></i> Timetable</a></li>
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
                        <li><a href="#"><i class="fa fa-cog" aria-hidden="true"></i>  Operations <span class="caret"></span></a></li>
                        <li><a href="#"><i class="fa fa-line-chart" aria-hidden="true"></i> Reports <span class="caret"></span></a></li>
                     </ul>

                  </li>
                  <li>
                     <a href="{{url('/onlineacademics/onlineacademic/classtopic')}}">
                        <span class="fa fa-desktop icon-margin"></span> Online Academic
                     </a>
                  </li>
                  <li><a href="#"><span class="fa fa-users icon-margin"></span> Event <span class="caret"></span></a>

                  <ul class="dropdown-menus">
                     <li><a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> SOP Setup <span class="caret"></span></a>

                        <ul class="dropdown-menus">
                           <li><a href="{{url('/event/')}}"><i class="fa fa-user"></i> Set Up Event</a></li>                           
                           <li><a href="{{url('/event/marks')}}"><i class="fa fa-user"></i> Event Marks</a></li>                           
                        </ul>

                     </li>
                     <li><a href="#"><i class="fa fa-cog" aria-hidden="true"></i>  Operations <span class="caret"></span></a>
                        <ul class="dropdown-menus">
                        </ul>
                     </li>
                     <li><a href="#"><i class="fa fa-line-chart" aria-hidden="true"></i> Reports <span class="caret"></span></a></li>
                  </ul>

                  </li>

                  <li><a href="#"><span class="fa fa-users icon-margin"></span> Human Resource <span class="caret"></span></a>

                     <ul class="dropdown-menus">
                        <li><a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> SOP Setup <span class="caret"></span></a>

                           <ul class="dropdown-menus">
                              <li><a href="#"><i class="fa fa-user"></i> Teacher Management<span class="caret"></span></a>
                                 <ul class="dropdown-menus">
                                    <li><a href="{{url('/employee/manage/teacher')}}"><i class="fa fa-users"></i> Manage Teacher</a></li>
                                 </ul>
                              </li>
                              <li><a href="#"><i class="fa fa-user"></i> Employee Management<span class="caret"></span></a>
                                 <ul class="dropdown-menus">
                                    <li><a href="/employee/departments"><i class="fa fa-sitemap"></i> Department</a></li>
                                    <li><a href="/employee/designations"><i class="fa fa-signal"></i> Designation</a></li>
                                    <li><a href="/employee/create"><i class="fa fa-user-plus"></i> Add Employee</a></li>
                                    <li><a href="/employee/manage"><i class="fa fa-reorder"></i> Manage Employee</a></li>
                                    <li><a href="/employee/import"><i class="fa fa-upload"></i> Import Employee</a></li>
                                    <li><a href="/employee/import/images"><i class="fa fa-upload"></i> Import Photos</a></li>
                                    {{--<li><a href="#"><i class="fa fa-plus-square"></i> Shift Allocation</a></li>--}}
                                    {{--<li><a href="#"><i class="fa fa-gear"></i> Employee Settings</a></li>--}}
                                 </ul>
                              </li>
                              <li><a href="#"><i class="fa fa-gears"></i> Employee Configuration<span class="caret"></span></a>
                                 <ul class="dropdown-menus">
                                    {{--<li><a href="#"><i class="fa fa-clock-o"></i> Shift</a></li>--}}
                                    {{--<li><a href="#"><i class="fa fa-life-bouy"></i> Loan Type</a></li>--}}
                                    <li><a href="{{url('/employee/manage/week-off')}}"><i class="fa fa-calendar"></i> Week Off</a></li>
                                    <li><a href="{{url('/employee/manage/national-holiday')}}"><i class="fa fa-calendar-o"></i> National Holiday</a></li>
                                 </ul>
                              </li>
                              <li><a href="{{url('/employee/employee/status')}}"><i class="fa fa-gears"></i> Employee Status</a></li>
                              <li><a href="{{url('/employee/shift-configuration')}}"><i class="fa fa-gears"></i> Shift Configuration</a></li>
                              <li><a href="{{url('/employee/holiday-calender')}}"><i class="fa fa-gears"></i> Holiday Calender</a></li>
                              <li><a href="{{url('/employee/evaluations')}}"><i class="fa fa-gears"></i> Evaluation Set Up </a></li>
                              <li><a href="/employee/manage/leave/type"><i class="fa fa-file-text-o"></i> Leave Management<span class="caret"></span></a>
                                 <ul class="dropdown-menus">
                                    <li><a href="/employee/manage/leave/type"><i class="fa fa-life-bouy"></i> Leave Type</a></li>
                                    <li><a href="/employee/manage/leave/structure"><i class="fa fa-clock-o"></i> Leave Structure</a></li>
                                    <li><a href="/employee/manage/leave/entitlement"><i class="fa fa-plus-square"></i> Leave Entitlement</a></li>
                                    {{--<li><a href="#"><i class="fa fa-users"></i> Leave Reporting</a></li>--}}
                                    <li><a href="{{url('/employee/manage/leave/application')}}"><i class="fa fa-list-alt"></i> Manage Leave Applications</a></li>
                                    <li><a href="{{url('/employee/leave/application')}}"><i class="fa fa-list-alt"></i> Leave Applications</a></li>
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
                                    <li><a href="/payroll/salary-component"><i class="glyphicon glyphicon-tasks"></i> Salary Component </a></li>
                                    <li><a href="/payroll/pf-rules"><i class="fa fa-gear"></i> Provident fund Rules </a></li>
                                    <li><a href="/payroll/ot-rates"><i class="fa fa-gear"></i> Over time rate </a></li>
                                    <li><a href="/payroll/salary-structure"><i class="fa fa-clock-o"></i> Salary Structure </a></li>
                                    <li><a href="/payroll/emp-salary-assign"><i class="fa fa-check-square-o"></i> Employee Salary Assign </a></li>
                                    <li><a href="/payroll/emp-salary-dedallo"><i class="fa fa-check"></i> Monthly Deduction Allowance </a></li>
                                    <li><a href="/payroll/emp-lones"><i class="fa fa-money"></i> Employee Loan</a></li>
                                    <li><a href="/payroll/emp-salary-monthly"><i class="fa fa-credit-card"></i> Employee Monthly Salary </a></li>
                                    <li><a href="/payroll/emp-salary"><i class="fa fa-bullhorn"></i> Employee Salary</a></li>
                                    <li><a href="/employee/employee-over-time-entry"><i class="glyphicon glyphicon-check"></i> Over Time Entrt</a></li>
                                 </ul>
                              </li>

                              <li><a href="#"><i class="fa fa-bullseye"></i> Attendance<span class="caret"></span></a>
                                 <ul class="dropdown-menus">
                                    <li><a href="/employee/employee-attendance"><i class="glyphicon glyphicon-check"></i> Take Attendance</a></li>
                                    <li><a href="/employee/employee-attendance-setting"><i class="glyphicon glyphicon-check"></i> Attendance Setting</a></li>
                                 </ul>
                              </li>
                           </ul>

                        </li>
                        <li><a href="#"><i class="fa fa-cog" aria-hidden="true"></i>  Operations <span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="{{url('/employee/evaluation/view')}}"><i class="fa fa-gears"></i> Evaluation </a></li>
                              <li><a href="{{url('/employee/evaluation/search/view')}}"><i class="fa fa-gears"></i> Search Evaluation </a></li>
                              <li><a href="{{url('/employee/evaluation/history/view')}}"><i class="fa fa-gears"></i> Evaluation History </a></li>
                           </ul>
                        </li>
                        <li><a href="#"><i class="fa fa-line-chart" aria-hidden="true"></i> Reports <span class="caret"></span></a></li>
                     </ul>

                  </li>
                  <li><a href="#"><span class="fa fa-user icon-margin"></span> Students <span class="caret"></span></a>

                     <ul class="dropdown-menus">
                        <li><a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> SOP Setup <span class="caret"></span></a>

                           <ul class="dropdown-menus">
                              {{--                        <li><a href="#"><i class="fa fa-users"></i> Student<span class="caret"></span></a>--}}
                              {{--                           <ul class="dropdown-menus">--}}
                              {{--<li><a href="#"><i class="fa fa-sort-alpha-asc"></i> Admission Category</a></li>--}}
                              
                              {{--                              <li><a href="/student/manage/alumni"><i class="fa fa-reorder"></i> Alumni Cadet</a></li>--}}
                              <li><a href="/student/cadet-activity-directory"><i class="fa fa-list"></i> Student Activity Directory</a></li>
                              {{--                              <li><a href="/student/update/profile"><i class="fa fa-reorder"></i> Update Cadet Profile</a></li>--}}
                              {{--                              <li><a href="/fees/attendance_fine"><i class="fa fa-reorder"></i> Attendance Fine Generate</a></li>--}}
                              {{--                              <li><a href="/student/upload/images"><i class="fa fa-upload"></i> Upload Images</a></li>--}}
                              {{--                              @if($user->hasRole('super-admin'))--}}
                              {{--                                 <li><a href="/student/get/device/attendance"><i class="fa fa-upload"></i>Upload Device Attendance</a></li>--}}
                              {{--                              @endif--}}

                              {{--                              --}}{{--<li><a href="#"><i class="fa fa-info-circle"></i> Student Status</a></li>--}}
                              {{--                              <li><a href="/student/student-waiver/show-waiver/list"><i class="fa fa-exchange"></i> Cadet Waiver List</a></li>--}}
                              {{--                              <li><a href="/student/manage/waiver"><i class="fa fa-exchange"></i> Manage Waiver</a></li>--}}
                              {{--                              <li><a href="/student/testimonial/result/manage"><i class="fa fa-reorder"></i> Testimonal Result</a></li>--}}
                              {{--                           </ul>--}}
                              {{--                        </li>--}}
                              {{--                        <li><a href="/admission"><i class="fa fa-user-plus"></i> Admission (School)<span class="caret"></span></a>--}}
                              {{--                           <ul class="dropdown-menus">--}}
                              {{--                              --}}{{--<li><a href="/admission/application"><i class="fa fa-external-link"></i> Online Application</a></li>--}}
                              {{--                              <li><a href="/admission/enquiry"><i class="fa fa-users"></i> Manage Enquiry</a></li>--}}
                              {{--                              <li><a href="/admission/fees"><i class="fa fa-users"></i> Manage Fees</a></li>--}}
                              {{--                              --}}{{--<li><a href="/admission/admission-form"><i class="fa fa-users"></i> Admission Form</a></li>--}}
                              {{--                              <li><a href="/admission/assessment"><i class="fa fa-users"></i> Manage Assessment</a></li>--}}
                              {{--                              <li><a href="/admission/applicant/download"><i class="fa fa-users"></i> Download Applicant</a></li>--}}
                              {{--                              <li><a href="/admission/applicant-sitplan/download"><i class="fa fa-users"></i> Applicant Seat Plan</a></li>--}}
                              {{--                  --}}{{--<li><a href="/admission/assessment"><i class="fa fa-users"></i> Applicant Sit Plan</a></li>--}}
                              {{--                           </ul>--}}
                              {{--                        </li>--}}
                              {{--                        <li><a href="/admission"><i class="fa fa-user-plus"></i> Admission (College)<span class="caret"></span></a>--}}
                              {{--                           <ul class="dropdown-menus">--}}
                              {{--                              <li><a href="/admission/hsc/enquiry"><i class="fa fa-users"></i> Manage Applications</a></li>--}}
                              {{--                              <li><a href="/admission/hsc/promotion"><i class="fa fa-users"></i> Manage Promotion</a></li>--}}
                              {{--                           </ul>--}}
                              {{--                        </li>--}}
                              <li><a href="/student/club/setup/enrollment"><i class="fa fa-users"></i> Club Setup</a></li>
                              {{--                        <li><a><i class="fa fa-user-plus"></i> Parents<span class="caret"></span></a>--}}
                              {{--                           <ul class="dropdown-menus">--}}
                              {{--                              --}}
                              {{--                           </ul>--}}
                              {{--                        </li>--}}
                              <li><a href="/student/task/schedule"><i class="fa fa-users"></i> Task Schedule</a></li>
                              <li><a href="/student/view/task/schedule"><i class="fa fa-users"></i> View Task Schedule</a></li>
                           </ul>

                        </li>
                        <li><a href="#"><i class="fa fa-cog" aria-hidden="true"></i>  Operations <span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="/student/profile/create"><i class="fa fa-user-plus"></i> Student Enrollment</a></li>
                              <li><a href="/student/manage"><i class="fa fa-reorder"></i> Manage Student</a></li>
                              <li><a href="/student/cadet-performance-bulk"><i class="fa fa-reorder"></i> Factor Entries</a></li>
                              <li><a href="/student/manage/status"><i class="fa fa-reorder"></i> Deactive Student</a></li>
                              <li><a href="/student/import"><i class="fa fa-upload"></i> Import Student</a></li>
                              <li><a href="/student/import/images"><i class="fa fa-upload"></i> Import Photos</a></li>
                              <li><a href="/student/promote"><i class="fa fa-exchange"></i> Promote Student</a></li>
                              <li><a href="/student/parent/manage"><i class="fa fa-users"></i> Manage Parents</a></li>
                              <li><a href="{{url('academics/manage/attendance/manage')}}"><i class="fa fa-book "></i>Manage Attendance</a></li>
                           </ul>
                        </li>
                        <li><a href="#"><i class="fa fa-line-chart" aria-hidden="true"></i> Reports <span class="caret"></span></a></li>
                     </ul>

                  </li>
                  <li><a href="/fees/dashboard/panel"><span class="fa fa-money icon-margin"></span> Fees <span class="caret"></span></a>

                     <ul class="dropdown-menus">
                        <li><a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> SOP Setup <span class="caret"></span></a>

                           <ul class="dropdown-menus">
                              <li><a href="/fees/dashboard/panel"><i class="fa fa-money"></i> Fees<span class="caret"></span></a>
                                 <ul class="dropdown-menus">
                                    <li><a href="/cadetfees/create/fees"><i class="fa fa-bank"></i> Fees Assign</a></li>
                                    <li><a href="/cadetfees/generate/fees"><i class="fa fa-bank"></i> Fees Generate</a></li>
{{--                                    <li><a href="/fees/invoice"><i class="fa fa-sort-alpha-asc"></i> Fees Invoice</a></li>--}}
{{--                                    <li><a href="/fees/paymenttransaction"><i class="fa fa-sort-alpha-asc"></i> Payment Transaction</a></li>--}}
{{--                                    <li><a href="/fees/addfees"><i class="fa fa-exchange"></i> Add Fees</a></li>--}}
{{--                                    <li><a href="/fees/feestemplate"><i class="fa fa-exchange"></i> Fees Template</a></li>--}}
{{--                                    <li><a href="/fees/feetype"><i class="fa fa-exchange"></i> Fees Type</a></li>--}}
{{--                                    <li><a href="/fees/items"><i class="fa fa-exchange"></i> Fees Items</a></li>--}}
{{--                                    <li><a href="/fees/attendance_fine_generate"><i class="fa fa-exchange"></i> Attendance Fine Generate</a></li>--}}
{{--                                    <li><a href="/fees/student/invoice/search"><i class="fa fa-exchange"></i> Process Invoice</a></li>--}}
{{--                                    <li><a href="/fees/invoice/pdf/demo/10"><i class="fa fa-exchange"></i>Bangla Fees Report</a></li>--}}
{{--                                    <li><a href="/fees/student/fine-reduction"><i class="fa fa-exchange"></i> Fine Reduction</a></li>--}}
                                 </ul>
                              </li>
                              <li><a href="{{url('accounting/accdashboard')}}"><i class="fa fa-money"></i> Accounting<span class="caret"></span></a>
                                 <ul class="dropdown-menus">
                                    <li><a href="{{url('/cadetfees/manual/fees')}}"><i class="fa fa-bank"></i> Manual Fees</a></li>
{{--                                    <li><a href="{{url('/finance/accounts/dashboard')}}"><i class="fa fa-bank"></i> Dashboard</a></li>--}}
{{--                                    <li><a href="{{url('finance/accounts/index')}}"><i class="fa fa-bank"></i> Chart of Accounts</a></li>--}}
{{--                                    <li><a href="{{url('finance/accounts/groups/add')}}"><i class="fa fa-bank"></i> Group</a></li>--}}
{{--                                    <li><a href="{{url('finance/accounts/ledger/add')}}"><i class="fa fa-bank"></i>  Ledger</a></li>--}}
{{--                                    <li><a href="{{url('finance/accounts/entries/index')}}"><i class="fa fa-bank"></i> Entires</a></li>--}}
                                 </ul>
                              </li>
                              <li><a href="{{url('accounting/accdashboard')}}"><i class="fa fa-money"></i> Accounting Reports<span class="caret"></span></a>
                                 <ul class="dropdown-menus">
                                    <li><a href="{{url('finance/reports/balancesheet')}}"><i class="fa fa-bank"></i> Balance Sheet</a></li>
                                    <li><a href="{{url('finance/reports/profitloss')}}"><i class="fa fa-bank"></i> Profit Loss</a></li>
                                    <li><a href="{{url('finance/reports/trialbalance')}}"><i class="fa fa-bank"></i>Trail Balance</a></li>
                                    <li><a href="{{url('finance/accounts/reports/ledgerstatement')}}"><i class="fa fa-bank"></i> Ledger Statement</a></li>
                                    <li><a href="{{url('finance/accounts/reports/ledgerentries')}}"><i class="fa fa-bank"></i> Ledger Entries</a></li>
                                 </ul>
                              </li>

                              <li><a href="{{url('accounting/accdashboard')}}"><i class="fa fa-money"></i> Fee (New)<span class="caret"></span></a>
                                 <ul class="dropdown-menus">
                                    <li><a href="{{url('fee/create/generalfee')}}"><i class="fa fa-bank"></i> Fee Create</a></li>
                                    <li><a href="{{url('fee/feeassign/feeassign')}}"><i class="fa fa-bank"></i>Fee Assign</a></li>
                                    <li><a href="{{url('fee/collection/single')}}"><i class="fa fa-bank"></i> Fee Collection</a></li>
                                    <li><a href="{{url('fee/fine-collection/late-fine')}}"><i class="fa fa-bank"></i> Fine Collection</a></li>
                                    <li><a href="{{url('fee/report/collection-amount/feedetails')}}"><i class="fa fa-bank"></i> Report Collection Amount</a></li>
                                    <li><a href="{{url('fee/report/due-amount/student-wise')}}"><i class="fa fa-bank"></i> Report Due Amount</a></li>
                                    <li><a href="{{url('fee/report/money-receipt/feereceipt')}}"><i class="fa fa-bank"></i> Report Money Receipt</a></li>
                                    <li><a href="{{url('fee/setting/feehead')}}"><i class="fa fa-bank"></i> Fee Setting</a></li>

                                 </ul>
                              </li>
                           </ul>

                        </li>
                        <li><a href="#"><i class="fa fa-cog" aria-hidden="true"></i>  Operations <span class="caret"></span></a></li>
                        <li><a href="#"><i class="fa fa-line-chart" aria-hidden="true"></i> Reports <span class="caret"></span></a></li>
                     </ul>

                  </li>
                  <li><a href="#"><span class="fa fa-comment icon-margin"></span> Communication <span class="caret"></span></a>

                     <ul class="dropdown-menus">
                        <li><a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> SOP Setup <span class="caret"></span></a>

                           <ul class="dropdown-menus">
                              <li><a href="javascript:void(0);"><i class="fa fa-comment"></i> SMS<span class="caret"></span></a>
                                 <ul class="dropdown-menus">
                                    <li><a href="/communication/sms/sms_credit"><i class="fa fa-comments-o"></i>SMS Credit</a></li>
                                    <li><a href="/communication/sms/sms_log"><i class="fa fa-comments-o"></i> SMS Log</a></li>
                                    <li><a href="/communication/sms/group"><i class="fa fa-comments-o"></i> Group SMS</a></li>
                                    @if($user->hasRole('super-admin'))
                                       <li><a href="/communication/sms/pending_sms"><i class="fa fa-comments-o"></i> Pending SMS</a></li>
                                    @endif
                                    <li><a href="/communication/sms/template"><i class="fa fa-comments-o"></i> Template SMS</a></li>
                                    <li><a href="/communication/sms/template/list"><i class="fa fa-comments-o"></i> Template SMS List</a></li>
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
                                    <li><a href="/communication/telephone-diary/student-contact"><i class="fa fa-phone-square"></i> Student Contact</a></li>
                                    <li><a href="/communication/telephone-diary/employee-contact"><i class="fa fa-phone-square"></i> Employee Contact</a></li>
                                 </ul>
                              </li>

                              <li><a href="#"><i class="fa fa-question-circle"></i> Helpdesk<span class="caret"></span></a>
                                 <ul class="dropdown-menus">
                                    <li><a href="#"><i class="fa fa-question"></i> Inquiry Subjects</a></li>
                                    <li><a href="#"><i class="fa fa-ticket"></i> Inquiry Tickets</a></li>
                                 </ul>
                              </li>

                           </ul>

                        </li>
                        <li><a href="#"><i class="fa fa-cog" aria-hidden="true"></i>  Operations <span class="caret"></span></a></li>
                        <li><a href="#"><i class="fa fa-line-chart" aria-hidden="true"></i> Reports <span class="caret"></span></a></li>
                     </ul>

                  </li>

                  <li><a href="#"><span class="fa fa-line-chart icon-margin"></span> Reports and Printing <span class="caret"></span></a>

                     <ul class="dropdown-menus">
                        <li><a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> SOP Setup <span class="caret"></span></a>

                           <ul class="dropdown-menus">
                              <li><a href="/reports/academics"><i class="fa fa-line-chart"></i>Academics<span class="caret"></span></a>
                                 <ul class="dropdown-menus">
                                    <li><a href="/reports/academics"><i class="fa fa-bar-chart"></i> Student Reports</a></li>
                                    <li><a href="/reports/academics"><i class="fa fa-bar-chart"></i> Teacher Reports</a></li>
                                    <li><a href="/reports/academics"><i class="fa fa-bar-chart"></i> Parents Reports</a></li>
                                    <li><a href="/reports/id-card"><i class="fa fa-bar-chart"></i> ID Card</a></li>
                                    <li><a href="/reports/sitplan"><i class="fa fa-bar-chart"></i> Seat Plan</a></li>
                                    <li><a href="/reports/admit-card"><i class="fa fa-bar-chart"></i> Admit Card</a></li>
                                    <li><a href="/reports/documents#transfer_certificate"><i class="fa fa-bar-chart"></i> Transfer Certificate</a></li>
                                    <li><a href="/reports/documents"><i class="fa fa-bar-chart"></i> Testimonial</a></li>
                                 </ul>
                              </li>
                              <li><a href="/reports/attendance"><i class="fa fa-building-o"></i> Attendance<span class="caret"></span></a>
                                 <ul class="dropdown-menus">
                                    <li><a href="{{url('/reports/attendance')}}"><i class="fa fa-users"></i> Student Attendance</a></li>
                                    <li><a href="{{url('/report/attendance')}}"><i class="fa fa-users"></i> Class Section Attendance</a></li>
                                    <li><a href="{{url('/report/attendance')}}"><i class="fa fa-users"></i> Student Absent Days Report</a></li>
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
                        <li><a href="#"><i class="fa fa-cog" aria-hidden="true"></i>  Operations <span class="caret"></span></a></li>
                        <li><a href="#"><i class="fa fa-line-chart" aria-hidden="true"></i> Reports <span class="caret"></span></a></li>
                     </ul>

                  </li>
                  <li><a href="#"><span class="fa fa-wrench icon-margin"></span> Administration <span class="caret"></span></a>

                     <ul class="dropdown-menus">
                        <li><a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> SOP Setup <span class="caret"></span></a>

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
                        <li><a href="#"><i class="fa fa-cog" aria-hidden="true"></i>  Operations <span class="caret"></span></a></li>
                        <li><a href="#"><i class="fa fa-line-chart" aria-hidden="true"></i> Reports <span class="caret"></span></a></li>
                     </ul>

                  </li>
                  <li><a href="#"><span class="fa fa-wrench icon-margin"></span> House <span class="caret"></span></a>

                     <ul class="dropdown-menus">
                        <li><a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> SOP Setup <span class="caret"></span></a>

                           <ul class="dropdown-menus">
                              <li><a href="{{url('/house/manage-house')}}"><i class="fa fa-sitemap"></i> Manage House</a></li>
                              <li><a href="{{url('/house/view')}}"><i class="fa fa-sitemap"></i> View Houses</a></li>
                              <li><a href="{{url('/house/cadets-evaluation')}}"><i class="fa fa-sitemap"></i> Evaluation</a></li>
                           </ul>

                        </li>
                        <li><a href="#"><i class="fa fa-cog" aria-hidden="true"></i>  Operations <span class="caret"></span></a></li>
                        <li><a href="#"><i class="fa fa-line-chart" aria-hidden="true"></i> Reports <span class="caret"></span></a></li>
                     </ul>

                  </li>
                  <li><a href="#"><span class="fa fa-wrench icon-margin"></span> Inventory <span class="caret"></span></a>

                     <ul class="dropdown-menus">
                        <li><a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> SOP Setup <span class="caret"></span></a>

                           <ul class="dropdown-menus">
                              <li><a href="{{url('/inventory/batch-grid')}}"><i class="fa fa-sitemap"></i> Batch</a></li>
                              <li><a href="#"><i class="fa fa-building"></i> Stock<span class="caret"></span></a>
                                 <ul class="dropdown-menus">
                                    <li><a href="{{url('/inventory/stock-group-grid')}}"> Stock Group</a></li>
                                    <li><a href="{{url('/inventory/stock-category')}}"> Stock Category</a></li>
                                    <li><a href="{{url('/inventory/unit-of-measurement')}}"> Unit Of Measurement</a></li>
                                    <li><a href="{{url('/inventory/stock-list')}}"> Stock List</a></li>
                                    <li><a href="{{url('/inventory/stock-item-serial')}}"> Stock Item Serial</a></li>
                                 </ul>
                              </li>
                              <li><a href="#"><i class="fa fa-building"></i> Voucher<span class="caret"></span></a>
                                 <ul class="dropdown-menus">
                                    <li><a href="{{url('/inventory/voucher-config-list')}}"> Voucher Config</a></li>
                                 </ul>
                              </li>
                              <li><a href="#"><i class="fa fa-building"></i> Store<span class="caret"></span></a>
                                 <ul class="dropdown-menus">
                                    <li><a href="{{url('/inventory/store')}}"> Store</a></li>
                                 </ul>
                              </li>
                              <li><a href="#"><i class="fa fa-building"></i> Purchase<span class="caret"></span></a>
                                 <ul class="dropdown-menus">
                                    <li><a href="{{url('/inventory/vendor')}}"> Vendor</a></li>
                                 </ul>
                              </li>
                              <li><a href="#"><i class="fa fa-building"></i> Sales<span class="caret"></span></a>
                                 <ul class="dropdown-menus">
                                    <li><a href="{{url('/inventory/customer')}}"> Customer</a></li>
                                 </ul>
                              </li>
                              <li><a href="{{url('/inventory/price-catalogue')}}"><i class="fa fa-sitemap"></i> Price Catalogue</a></li>
                           </ul>

                        </li>
                        <li><a href="#"><i class="fa fa-cog" aria-hidden="true"></i>  Operations <span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="#"><i class="fa fa-building"></i> Requisition<span class="caret"></span></a>
                                 <ul class="dropdown-menus">
                                    <li><a href="{{url('/inventory/new-requisition')}}"> New Requisition</a></li>
                                 </ul>
                              </li>
                              <li><a href="{{url('/inventory/issue-inventory')}}"><i class="fa fa-building"></i> Issue from Inventory</a></li>
                              <li><a href="{{url('/inventory/store-transfer-requisition')}}"><i class="fa fa-building"></i> Store Transfer Requisition</a></li>
                              <li><a href="{{url('/inventory/store-transfer')}}"><i class="fa fa-building"></i> Store Transfer</a></li>
                              <li><a href="#"><i class="fa fa-building"></i> Purchase<span class="caret"></span></a>
                                 <ul class="dropdown-menus">
                                    <li><a href="{{url('/inventory/purchase-requisition')}}"> Purchase Requisition</a></li>
                                    <li><a href="{{url('/inventory/comparative-statement')}}">Comparative Statement</a></li>
                                    <li><a href="{{url('/inventory/purchase-order')}}"> Purchase order</a></li>
                                    <li><a href="{{url('/inventory/purchase-receive')}}"> Purchase Receive</a></li>
                                    <li><a href="{{url('/inventory/purchase-return')}}"> Purchase Return</a></li>
                                 </ul>
                              </li>

                              <li><a href="#"><i class="fa fa-building"></i> Sales<span class="caret"></span></a>
                                 <ul class="dropdown-menus">
                                    <li><a href="{{url('/inventory/sales-order')}}"> Sales Order</a></li>
                                    <li><a href="{{url('/inventory/sales-challan')}}"> Sales Chalan</a></li>
                                 </ul>
                              </li>

                              <li><a href="{{url('/inventory/stock-in')}}"><i class="fa fa-building"></i> Stock In</a></li>

                              <li><a href="{{url('/inventory/stock-out')}}"><i class="fa fa-building"></i> Stock Out</a></li>

                             
                           </ul>
                        </li>
                        <li><a href="#"><i class="fa fa-line-chart" aria-hidden="true"></i> Reports <span class="caret"></span></a></li>
                     </ul>

                  </li>
                  <li><a href="#"><span class="fa fa-wrench icon-margin"></span> Health Care <span class="caret"></span></a>

                     <ul class="dropdown-menus">
                        <li><a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> SOP Setup <span class="caret"></span></a>

                           <ul class="dropdown-menus">
                              <li><a href="{{url('/healthcare/prescription')}}"><i class="fa fa-sitemap"></i> Prescription</a></li>
                              <li><a href="{{url('/healthcare/drug/reports')}}"><i class="fa fa-sitemap"></i> Drug Reports</a></li>
                              <li><a href="{{url('/healthcare/investigation')}}"><i class="fa fa-sitemap"></i> List Of Investigation</a></li>
                              <li><a href="{{url('/healthcare/investigation/reports')}}"><i class="fa fa-sitemap"></i> Investigation Reports</a></li>
                           </ul>

                        </li>
                        <li><a href="#"><i class="fa fa-cog" aria-hidden="true"></i>  Operations <span class="caret"></span></a></li>
                        <li><a href="#"><i class="fa fa-line-chart" aria-hidden="true"></i> Reports <span class="caret"></span></a></li>
                     </ul>

                  </li>
                  <li><a href="#"><span class="fa fa-wrench icon-margin"></span> Mess <span class="caret"></span></a>

                     <ul class="dropdown-menus">
                        <li><a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> SOP Setup <span class="caret"></span></a>

                           <ul class="dropdown-menus">
                              <li><a href="{{url('/mess/table')}}"><i class="fa fa-sitemap"></i> Table</a></li>
                              <li><a href="{{url('/mess/food-menu')}}"><i class="fa fa-sitemap"></i> Food Menu</a></li>
                              <li><a href="{{url('/mess/food-menu-schedule')}}"><i class="fa fa-sitemap"></i> Food Menu Schedule</a></li>
                           </ul>

                        </li>
                        <li><a href="#"><i class="fa fa-cog" aria-hidden="true"></i>  Operations <span class="caret"></span></a></li>
                        <li><a href="#"><i class="fa fa-line-chart" aria-hidden="true"></i> Reports <span class="caret"></span></a></li>
                     </ul>

                  </li>
                  <li><a href="#"><span class="fa fa-file-text icon-margin"></span> Document <span class="caret"></span></a>

                     <ul class="dropdown-menus">
                        <li><a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> SOP Setup <span class="caret"></span></a>

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
                        <li><a href="#"><i class="fa fa-cog" aria-hidden="true"></i>  Operations <span class="caret"></span></a></li>
                        <li><a href="#"><i class="fa fa-line-chart" aria-hidden="true"></i> Reports <span class="caret"></span></a></li>
                     </ul>

                  </li>
                  <li><a href=""><span class="fa fa-university icon-margin"> </span>Library<span class="caret"></span></a>

                     <ul class="dropdown-menus">
                        <li><a href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> SOP Setup <span class="caret"></span></a>

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
                        <li><a href="#"><i class="fa fa-cog" aria-hidden="true"></i>  Operations <span class="caret"></span></a></li>
                        <li><a href="#"><i class="fa fa-line-chart" aria-hidden="true"></i> Reports <span class="caret"></span></a></li>
                     </ul>

                  </li>

                  @if($user->hasRole('super-admin'))
                     <li><a href="#"><span class="fa fa-cog icon-margin"></span> Setting <span class="caret"></span></a>
                        <ul class="dropdown-menus" >
                           <li><a href="/default/index"><i class="fa fa-cogs"></i> Configuration<span class="caret"></span></a>
                              <ul class="dropdown-menus">
                                 <li><a href="{{url('setting/country')}}"><i class="fa fa-globe"></i> Country</a></li>
                                 <li><a href="{{url('setting/state')}}"><i class="fa fa-map-marker"></i> State/Province</a></li>
                                 <li><a href="{{url('setting/city')}}"><i class="fa fa-building-o"></i> City/Town</a></li>
{{--                                 <li><a href="/setting/language/index"><i class="fa fa-language"></i> Languages</a></li>--}}
                                 <li><a href="{{url('setting')}}"><i class="fa fa-bank"></i> Institute</a></li>
{{--                                 <li><a href="/setting/institute/property"><i class="fa fa-bank"></i> Institute Property</a></li>--}}
{{--                                 <li><a href="/setting/attendance"><i class="fa fa-bank"></i> Attendance Setting</a></li>--}}
                                 <li><a href="/setting/fees/setting/list"><i class="fa fa-bank"></i>Fine Setting</a></li>
{{--                                 <li><a href="/setting/login/screen"><i class="fa fa-bank"></i> Setting Login Screen </a></li>--}}
                              </ul>
                           </li>
                           <li><a href="#"><i class="fa fa-envelope"></i> SMS<span class="caret"></span></a>
                              <ul class="dropdown-menus">
                                 <li><a href="/setting/sms/setting/getway"><i class="fa fa-cogs"></i> SMS Settings</a></li>
                                 <li><a href="/setting/institute/sms-price"><i class="fa fa-cogs"></i> SMS Price</a></li>
                                 <li><a href="/setting/sms/getway/list"><i class="fa fa-cogs"></i> SMS Gateway List</a></li>
                              </ul>
                           </li>
                           <li><a href="#"> Manage User Rights <span class="caret"></span></a>
                              <ul class="dropdown-menus">
                                 <li><a href="/setting/institute/campus/assign"><i class="fa fa-male"></i> Assignment</a></li>
                                 <li><a href="/setting/rights/role"><i class="fa fa-user-times"></i> Role</a></li>
                              </ul>
                           </li>
                           <li><a href="#"><i class="fa fa-user-secret"></i> Manage Users<span class="caret"></span></a>
                              <ul class="dropdown-menus">
                                 <li><a href="/setting/manage/users"><i class="fa fa-male"></i> Manage Admin Users</a></li>
                                 <li><a href="/setting/change/password"><i class="fa fa-male"></i> Change Password</a></li>
                                 <li><a href="#"><i class="fa fa-user-times"></i> Manage Employee Users</a></li>
                              </ul>
                           </li>
                           <li><a href="#"><i class="fa fa-cog"></i> Factor Config <span class="caret"></span></a>
                              <ul class="dropdown-menus">
                                 <li><a href="/setting/performance/category">Factor Add</a></li>
                              </ul>
                           </li>                           
{{--                           <li><a href="#"><i class="fa fa-cog"></i> Additiona<span class="caret"></span></a>--}}
{{--                              <ul class="dropdown-menus">--}}
{{--                                 --}}{{--<li><a href="#"><i class="fa fa-cogs"></i> System Settings</a></li>--}}
{{--                                 --}}{{--<li><a href="#"><i class="fa fa-bell-o"></i> Notification Settings</a></li>--}}
{{--                                 --}}{{--<li><a href="#"><i class="fa fa-database"></i> Backup</a></li>--}}
{{--                                 <li><a href="/setting/audit/history"><i class="fa fa-history"></i> Audit History</a></li>--}}
{{--                              </ul>--}}
{{--                           </li>--}}
                        </ul>
                     </li>
                  @elseif($user->hasRole('admin'))
                     <li><a href="#"><span class="fa fa-cog icon-margin"></span> Setting <span class="caret"></span></a>
                        <ul class="dropdown-menus" >
                           <li><a href="/default/index"><i class="fa fa-cogs"></i> Users Manage<span class="caret"></span></a>
                              <ul class="dropdown-menus">
                                 <li><a href="{{url('setting/change/password')}}"><i class="fa fa-globe"></i>Change Password</a></li>
                              </ul>
                           </li>
                        </ul>
                     </li>

                  @endif
               </ul>
            </li>
         </ul>
         @endrole
         @role(['accountant'])
         <ul class="nav navbar-nav">

            <li><a href="#" class="main-menu-style"><span class="fa fa-th-large icon-margin"></span> Menu </a>
               <ul class="dropdown-menus main-dropdown">
                  <li><a href="/fees/dashboard/panel"><span class="fa fa-money icon-margin"></span> Fees </span><span class="caret"></span></a>
                     <ul class="dropdown-menus">
                        <li><a href="/fees/dashboard/panel"><i class="fa fa-money"></i> Fees<span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="/fees/feeslist"><i class="fa fa-bank"></i> Fees List</a></li>
                              <li><a href="/fees/feesmanage"><i class="fa fa-bank"></i> Fees Manage</a></li>
                              <li><a href="/fees/invoice"><i class="fa fa-sort-alpha-asc"></i> Fees Invoice</a></li>
                              <li><a href="/fees/paymenttransaction"><i class="fa fa-sort-alpha-asc"></i> Payment Transaction</a></li>
                              <li><a href="/fees/addfees"><i class="fa fa-exchange"></i> Add Fees</a></li>
                              <li><a href="/fees/feestemplate"><i class="fa fa-exchange"></i> Fees Template</a></li>
                              <li><a href="/fees/feetype"><i class="fa fa-exchange"></i> Fees Type</a></li>
                              <li><a href="/fees/items"><i class="fa fa-exchange"></i> Fees Items</a></li>
                              <li><a href="/fees/attendance_fine_generate"><i class="fa fa-exchange"></i> Attendance Fine Generate</a></li>
                              <li><a href="/fees/student/invoice/search"><i class="fa fa-exchange"></i> Process Invoice</a></li>
                              <li><a href="/fees/invoice/pdf/demo/10"><i class="fa fa-exchange"></i>Bangla Fees Report</a></li>
                              <li><a href="/fees/student/fine-reduction"><i class="fa fa-exchange"></i> Fine Reduction</a></li>
                           </ul>
                        </li>
                        <li><a href="{{url('accounting/accdashboard')}}"><i class="fa fa-money"></i> Accounting<span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="{{url('/finance/accounts/create')}}"><i class="fa fa-bank"></i> Create Account</a></li>
                              <li><a href="{{url('/finance/accounts/dashboard')}}"><i class="fa fa-bank"></i> Dashboard</a></li>
                              <li><a href="{{url('finance/accounts/index')}}"><i class="fa fa-bank"></i> Chart of Accounts</a></li>
                              <li><a href="{{url('finance/accounts/groups/add')}}"><i class="fa fa-bank"></i> Group</a></li>
                              <li><a href="{{url('finance/accounts/ledger/add')}}"><i class="fa fa-bank"></i>  Ledger</a></li>
                              <li><a href="{{url('finance/accounts/entries/index')}}"><i class="fa fa-bank"></i> Entires</a></li>
                           </ul>
                        </li>
                        <li><a href="{{url('accounting/accdashboard')}}"><i class="fa fa-money"></i> Accounting Reports<span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="{{url('finance/reports/balancesheet')}}"><i class="fa fa-bank"></i> Balance Sheet</a></li>
                              <li><a href="{{url('finance/reports/profitloss')}}"><i class="fa fa-bank"></i> Profit Loss</a></li>
                              <li><a href="{{url('finance/reports/trialbalance')}}"><i class="fa fa-bank"></i>Trail Balance</a></li>
                              <li><a href="{{url('finance/accounts/reports/ledgerstatement')}}"><i class="fa fa-bank"></i> Ledger Statement</a></li>
                              <li><a href="{{url('finance/accounts/reports/ledgerentries')}}"><i class="fa fa-bank"></i> Ledger Entries</a></li>
                           </ul>
                        </li>


                        <li><a href="{{url('accounting/accdashboard')}}"><i class="fa fa-money"></i> Fee (New)<span class="caret"></span></a>
                           <ul class="dropdown-menus">
                              <li><a href="{{url('fee/create/generalfee')}}"><i class="fa fa-bank"></i> Fee Create</a></li>
                              <li><a href="{{url('fee/feeassign/feeassign')}}"><i class="fa fa-bank"></i>Fee Assign</a></li>
                              <li><a href="{{url('fee/collection/single')}}"><i class="fa fa-bank"></i> Fee Collection</a></li>
                              <li><a href="{{url('fee/fine-collection/late-fine')}}"><i class="fa fa-bank"></i> Fine Collection</a></li>
                              <li><a href="{{url('fee/report/collection-amount/feedetails')}}"><i class="fa fa-bank"></i> Report Collection Amount</a></li>
                              <li><a href="{{url('fee/report/due-amount/student-wise')}}"><i class="fa fa-bank"></i> Report Due Amount</a></li>
                              <li><a href="{{url('fee/report/money-receipt/feereceipt')}}"><i class="fa fa-bank"></i> Report Money Receipt</a></li>
                              <li><a href="{{url('fee/setting/feehead')}}"><i class="fa fa-bank"></i> Fee Setting</a></li>

                           </ul>
                        </li>

                     </ul>
                  </li>
               </ul>

             @endrole
         @endrole
         @role(['teacher'])
         <ul class="nav navbar-nav">
            <li><a href="#" class="main-menu-style"><span class="fa fa-th-large icon-margin"></span> Menu </a>
               <ul class="dropdown-menus main-dropdown">
                  <li>
                     <a href="{{URL::to('onlineacademics/onlineacademic/classtopic')}}">
                        <span class="fa fa-desktop icon-margin"></span> Online Academic
                     </a>
                  </li>
               </ul>
            </li>
         </ul>
         @endrole
         @role(['student'])
         <ul class="nav navbar-nav">
            <li><a href="#" class="main-menu-style"><span class="fa fa-th-large icon-margin"></span> Menu </a>
               <ul class="dropdown-menus main-dropdown">
                  <li>
                     <a href="{{URL::to('onlineacademics/onlineacademic/classtopic')}}">
                        <span class="fa fa-desktop icon-margin"></span> Online Academic
                     </a>
                  </li>
               </ul>
            </li>
         </ul>   
      @endif

      </div>
</nav><!--/.nav-collapse -->
<div class=" clearfix"></div>