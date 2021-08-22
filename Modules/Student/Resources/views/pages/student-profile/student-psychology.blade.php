@extends('student::pages.student-profile.profile-layout')

@section('styles')
   <link href="{{ asset('js/dynameter/jquery-gauge.css') }}" rel="stylesheet"/>
   <style>
      .demo1 {
         position: relative;
         width: 20vw;
         height: 20vw;
         box-sizing: border-box;
         float:left;
         margin:20px
      }

      .demo2 {
         position: relative;
         width: 20vw;
         height: 20vw;
         box-sizing: border-box;
         margin: 36px 20px -75px 56px;
      }
      .radio-box {
         border: 1px solid #fff;
         background: #f2f3f2;
         margin-bottom: 10px;
         position: relative;
         -webkit-box-shadow: 10px 10px 5px -10px rgba(0,0,0,0.75);
         -moz-box-shadow: 10px 10px 5px -10px rgba(0,0,0,0.75);
         box-shadow: 10px 10px 5px -10px rgba(0,0,0,0.75);
      }
      svg.b-gauge__paths.b-gauge__block {
         overflow: inherit;
      }
   </style>
@endsection

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


         {{--         <div class="gauge1 demo1"></div>--}}
         <div class="row">
            <div class="col-md-12">
               <div id="guardian_add_btn_container">
                  <p class="text-right">
                     <a id="update-guard-data" class="btn btn-success" href="/student/profile/factor/psychology/new/add/{{$personalInfo->id}}/{{$categoryid}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg" data-backdrop="static" data-keyboard="false"><i class="fa fa-user-plus" aria-hidden="true"></i> Add Record</a>
                  </p>
               </div>
            </div>
            @if($assessment->count()>0)
            @foreach($assessment as $index=>$act)
               <div class="col-md-4">
                  <div class="radio-box">
                     <h5 class="text-center"><b>{{date('d/m/Y', strtotime($act->date))}} - {{$act->batch()->batch_name}}</b></h5>
                     <b><p class="text-center" id="state{{$index}}"></p></b>
                     <div class="gauge2 demo2" id="gauge{{$index}}"></div>

                  </div>
               </div>
            @endforeach
            @endif
            <div class="col-md-12">
               <p style="margin:10px;text-align: center;border:1px solid #c0c7c0;padding:5px;"><b>{{$range}}</b></p>
            </div>


            <table class="table table-striped">
               <thead>
               <tr>
                  <th scope="col">#</th>
                  <th scope="col">Academic Year</th>
                  <th scope="col">Academic Label</th>
                  {{-- <th scope="col">Division</th> --}}
                  <th scope="col">Class</th>
                  <th scope="col">Section</th>
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
{{--                           <a id="update-guard-data" class="btn btn-success" href="/student/profile/academic2/{{$suid}}/{{$categoryid}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-eye" aria-hidden="true"></i>Details</a>--}}

                           {{--                           <a href="#" class="btn btn-primary btn-xs"><i class="fa fa-eye" aria-hidden="true"></i></a>--}}
                           <a id="update-guard-data" class="btn btn-success btn-xs" href="/student/profile/psychology/view/{{$suid}}/{{$act->id}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-eye" aria-hidden="true"></i></a>

                           {{--                           <a href="#" class="btn btn-primary">Delete</a>--}}
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
{{--   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>--}}
   <script src="{{asset('js/dynameter/jquery-gauge.min.js')}}"></script>

   <script type="text/javascript">
      // first example
      // var gauge = new Gauge($('.gauge1'), {value: 40});\
      $(document).ready(function (){
         show_graph();
      });
      function show_graph()
      {
         let student_id = {{$personalInfo->id}};
         let type = {{$typeid}};
         let category_id = {{$categoryid}};
         let _token   = '<?php echo csrf_token() ?>';

         $.ajax({
            url: '/student/profile/diameter/graph',
            type:"POST",
            data:{
               student_id:student_id,
               type:type,
               category_id:category_id,
               _token: _token
            },
            success: function (response) {
               console.log(response);
               var length = response.length;
               var i;
               for (i = 0; i < length; i++) {
                  $("#state" + i).html(response[i].state);
                  $('#gauge'+i).gauge({
                     values: {
                        0 : '0',
                        20: '20',
                        40: '40',
                        60: '60',
                        80: '80',
                        100: '100'
                     },
                     colors: {
                        0 : '#666',
                        9 : '#378618',
                        60: '#ffa500',
                        80: '#f00'
                     },
                     angles: [
                        180,
                        360
                     ],
                     lineWidth: 25,
                     arrowWidth: 20,
                     arrowColor: '#ccc',
                     inset:true,

                     value: response[i].value
                  });
               }

               // $("#chtAnimatedBarChart").animatedBarChart({ data: response });
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
               alert(errorThrown);
               console.log(errorThrown);
            }
         });
      }

     // second example
     //  $('.gauge2').gauge({
     //     values: {
     //        0 : '0',
     //        20: '2',
     //        40: '4',
     //        60: '6',
     //        80: '8',
     //        100: '10'
     //     },
     //     colors: {
     //        0 : '#666',
     //        9 : '#378618',
     //        60: '#ffa500',
     //        80: '#f00'
     //     },
     //     angles: [
     //        180,
     //        360
     //     ],
     //     lineWidth: 25,
     //     arrowWidth: 20,
     //     arrowColor: '#ccc',
     //     inset:true,
     //
     //     value: 60
     //  });

   </script>
@endsection
