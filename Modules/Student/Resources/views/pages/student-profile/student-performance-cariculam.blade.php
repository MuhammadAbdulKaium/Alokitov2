@extends('student::pages.student-profile.profile-layout')

@section('profile-content')
   <div class="panel ">
      <div class="panel-body">
         
         <div id="user-profile">
            <ul id="w2" class="nav-tabs margin-bottom nav">
               @if(@isset($performanceCategory))
                  @foreach ($performanceCategory as $item)
                     <li class="{{SessionHelper::setMenuHeadActive($categoryname, $item->category_name)}}"><a href="/student/profile/performance/{{strtolower($item->category_name)}}/{{$item->category_type_id}}/{{$item->id}}/{{$personalInfo->id}}">{{$item->category_name}}</a></li>
                  @endforeach
               @endif
            </ul>
         </div>
         <div class="row">
            <div class="col-md-2">
               <input name="student_id" type="hidden" value="{{$personalInfo->id}}">
               <div class="form-group">
                  <input type="radio" id="yearly" name="duration" value="yearly" checked="checked">
                  <label for="female">Yearly</label><br>
                  <input type="radio" id="monthly" name="duration" value="monthly">
                  <label for="male">Monthly</label><br>
               </div>
            </div>
            <div id="month_show" class="col-md-3" style="display: none;">
               <div class="form-group">
                  <input type="text" class="form-control datepicker" id="month_name" name="month_name" placeholder="Select Year">
               </div>
            </div>
            <div class="col-md-2">
               <div class="form-group">
                  <input type="radio" id="details" name="type" value="details" checked="checked">
                  <label for="details">Details</label><br>
                  <input type="radio" id="summary" name="type" value="summary">
                  <label for="summary">Summary</label><br>

               </div>
            </div>
            <div class="col-md-3">
               <div class="form-group">
                  <select id="activity" class="form-control select2" name="activity_id[]" multiple="multiple">
                     @if(@isset($activity))
                        @foreach ($activity as $ac)
                           <option value="{{$ac->id}}">{{$ac->activity_name}}</option>
                        @endforeach
                     @endif
                  </select>
               </div>
            </div>
            <a href="javascript:void(0)" id="show_graph"><i class="fa fa-search fa-2x" aria-hidden="true"></i></a>
         </div>
         <div id="chtAnimatedBarChart" class="bcBar"></div>
         <div class="row">
            <div id="guardian_add_btn_container">
               <p class="text-right">
{{--               <a id="update-guard-data" class="btn btn-success" href="/student/profile/factor/activity/new/add/{{$personalInfo->id}}/{{$categoryid}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-user-plus" aria-hidden="true"></i> Add Record</a>--}}
               <a id="update-guard-data" class="btn btn-success" href="/student/profile/factor/psychology/new/add/{{$personalInfo->id}}/{{$categoryid}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg" data-backdrop="static" data-keyboard="false"><i class="fa fa-user-plus" aria-hidden="true"></i> Add Record</a>
               </p>
            </div>
            <table class="table table-striped">
               <thead>
               <tr>
                  <th scope="col">#</th>
                  <th scope="col">Academic Year</th>
                  <th scope="col">Academic Label</th>
                  {{-- <th scope="col">Division</th> --}}
                  <th scope="col">Class</th>
                  <th scope="col">Form</th>
                  <th scope="col">Date</th>
                  <th scope="col">Entity</th>
                  <th scope="col">Value</th>
                  <th scope="col">Point</th>
                  <th scope="col">Remarks</th>
                  <th scope="col">Action</th>
               </tr>
               </thead>
               <tbody>
                  @php $enrollment = $personalInfo->enroll(); @endphp
                  @if(isset($assessment))
                        @php
                           $i = 1
                        @endphp
                        @foreach($assessment as $act)
                           <tr class="gradeX">
                              <td>{{$i++}}</td>                              
                              <td>{{$act->year()->year_name}}</td>
                              <td>{{$act->lavel()->level_name}}</td>
                              {{-- <td></td> --}}
                              <td>{{$act->batch()->batch_name}}</td>
                              <td>{{$act->section()->section_name}}</td>
                              <td>{{date('d/m/Y', strtotime($act->date))}}</td>
                              <td>
                                 @if ($act->cadet_performance_activity_id != null)
                                    {{$act->performance_activity()->activity_name}}
                                 @endif 
                              </td>
                              <td>
                                 @if ($act->cadet_performance_activity_point_id)
                                    {{$act->activity_point()->value}}
                                 @endif                                 
                              </td>
                              <td>
                                 @if($act->cadet_performance_activity_point_id != null)
                                    {{$act->activity_point()->point}}
                                 @else
                                 {{$act->total_point}}
                                 @endif                                 
                              </td>
                              <td>{{$act->remarks}}</td>
                              <td>
                                 <a id="update-guard-data" class="btn btn-success btn-xs" href="/student/profile/psychology/view/{{$suid}}/{{$act->id}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                 {{-- <a href="#" class="btn btn-primary">Edit</a>
                                 <a href="#" class="btn btn-primary">Delete</a> --}}
                                 {{-- <a href="{{ url('setting/performance/activity/edit', $act->id) }}" class="btn btn-primary btn-xs" data-placement="top" data-content="update"><i class="fa fa-edit"></i></a>--}}
                                 <a href="{{ url('student/profile/factor/activity/delete', $act->id) }}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure to Delete?')" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a> 
                              </td>
                           </tr>
                        @endforeach
                  @endif
               </tbody>
            </table>
         </div>
      </div>
   </div>
   <!--/responsive-->

@endsection

@section('scripts')

   <link href="{{ asset('css/bar.chart.min.css') }}" rel="stylesheet"/>
   <script src='https://d3js.org/d3.v4.min.js'></script>
   <script src="{{asset('js/jquery.bar.chart.min.js')}}"></script>
   <script type="text/javascript">
     $(document).ready(function (){
         $('.select2').select2();
         show_graph();

         $('.datepicker').datepicker({
            autoclose: true,
            format: "yyyy",
            viewMode: "years",
            minViewMode: "years"
         });

         $('input[name="duration"]').click(function(){
            var radio_select = $(this).val();
            if(radio_select == 'monthly')
            {
               $("#month_show").show(200);
            }
            else
            {
               $("#month_name").val("");
               $("#month_show").hide();
            }
         });

      });

      $("#show_graph").click(function (e){
         e.preventDefault();
         $("#chtAnimatedBarChart").html("");
         show_graph();

      });


      function show_graph()
      {
         // var host = window.location.origin;
         let student_id = $("input[name=student_id]").val();
         let category = {{$categoryid}};
         let fector_item = {{$typeid}};
         let duration = $("input[name='duration']:checked").val();
         let month_name = $("input[name=month_name]").val();
         let type = $("input[name='type']:checked").val();
         let activity_id = $("#activity").val();
         let _token   = '<?php echo csrf_token() ?>';

         $.ajax({
            url: '/student/profile/activity/wise',
            type:"POST",
            data:{
               student_id:student_id,
               category:category,
               fector_item:fector_item,
               duration:duration,
               month_name:month_name,
               type:type,
               activity_id:activity_id,
               _token: _token
            },
            success: function (response) {
               console.log(response);
               $("#chtAnimatedBarChart").animatedBarChart({ data: response });
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
               alert(errorThrown);
               console.log(errorThrown);
            }
         });
      }

   </script>
@endsection


