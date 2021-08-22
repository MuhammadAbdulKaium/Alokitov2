@extends('student::pages.student-profile.profile-layout')

@section('profile-content')
   <div class="panel ">
      <div class="panel-body">
         <div id="user-profile">
            <ul id="w2" class="nav-tabs margin-bottom nav">
               @if(@isset($performanceCategory))
                  @foreach ($performanceCategory as $item)
                  <li class="@if($performance == '{{$item->category_name}}')active @endif"><a href="/student/profile/performance/{{strtolower($item->category_name)}}/{{$item->category_type_id}}/{{$item->id}}/{{$personalInfo->id}}">{{$item->category_name}}</a></li>
                  @endforeach
               @endif
            </ul>
         </div>
         <a class="btn btn-success pull-right" href="#" >Overall Remarks</a>
         <div class="row" id="graph">

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
                  <input id="month_name" type="text" class="form-control datepicker" name="month_name" placeholder="Select Year">
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
                     @if(@isset($performanceCategory))
                        @foreach ($performanceCategory as $ac)
                           <option value="{{$ac->id}}">{{$ac->category_name}}</option>
                        @endforeach
                     @endif
                  </select>
               </div>
            </div>
            <a href="javascript:void(0)" id="show_graph"><i class="fa fa-search fa-2x" aria-hidden="true"></i></a>
         </div>
         <div id="chtAnimatedBarChart" class="bcBar"></div>
      </div>
   </div>
   <!--/responsive-->

@endsection

@section('scripts')

   <link href="{{ asset('css/bar.chart.min.css') }}" rel="stylesheet"/>
   <script src='https://d3js.org/d3.v4.min.js'></script>
   <script src="{{asset('js/jquery.bar.chart.min.js')}}"></script>
   <script type="text/javascript">
      $(document).ready(function(){
         $('.select2').select2();
         var categoryType = {{$type}}

         if(categoryType == 2 || categoryType == 5 || categoryType == 4)
        {
           $("#graph").hide();
        }
        else
        {
           show_graph();
        }

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
         let student_id = $("input[name=student_id]").val();
         let category_id = {{$type}};
         let duration = $("input[name='duration']:checked").val();
         let month_name = $("input[name=month_name]").val();
         let type = $("input[name='type']:checked").val();
         let activity_id = $("#activity").val();
         let _token   = '<?php echo csrf_token() ?>';

         $.ajax({
            url: '/student/profile/landing/graph',
            type:"POST",
            data:{
               student_id:student_id,
               duration:duration,
               month_name:month_name,
               type:type,
               activity_id:activity_id,
               category_id:category_id,
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

      // $(function() {
      //    $("#chtAnimatedBarChart").animatedBarChart({ data: chart_data });
      // });

      //    var chart_data = getData();
      // getData = function() {
      //    return [
      //       { "group_name": "Quirat", "name": "Jan", "value": 38367 },
      //       { "group_name": "Quirat", "name": "Feb", "value": 32684 },
      //       { "group_name": "Quirat", "name": "Mar", "value": 28236 },
      //       { "group_name": "Quirat", "name": "Apr", "value": 44205 },
      //       { "group_name": "Quirat", "name": "May", "value": 3357 },
      //       { "group_name": "Quirat", "name": "Jun", "value": 3511 },
      //       { "group_name": "Quirat", "name": "Jul", "value": 10372 },
      //       { "group_name": "Quirat", "name": "Aug", "value": 15565 },
      //       { "group_name": "Quirat", "name": "Sep", "value": 23752 },
      //       { "group_name": "Quirat", "name": "Oct", "value": 28927 },
      //       { "group_name": "Quirat", "name": "Nov", "value": 21795 },
      //       { "group_name": "Quirat", "name": "Dec", "value": 49217 },
      //       { "group_name": "Azan", "name": "Jan", "value": 28827 },
      //       { "group_name": "Azan", "name": "Feb", "value": 13671 },
      //       { "group_name": "Azan", "name": "Mar", "value": 27670 },
      //       { "group_name": "Azan", "name": "Apr", "value": 6274 },
      //       { "group_name": "Azan", "name": "May", "value": 12563 },
      //       { "group_name": "Azan", "name": "Jun", "value": 31263 },
      //       { "group_name": "Azan", "name": "Jul", "value": 24848 },
      //       { "group_name": "Azan", "name": "Aug", "value": 41199 },
      //       { "group_name": "Azan", "name": "Sep", "value": 18952 },
      //       { "group_name": "Azan", "name": "Oct", "value": 30701 },
      //       { "group_name": "Azan", "name": "Nov", "value": 16554 },
      //       { "group_name": "Azan", "name": "Dec", "value": 36399 },
      //       { "group_name": "Painting", "name": "Jan", "value": 38674 },
      //       { "group_name": "Painting", "name": "Feb", "value": 9595 },
      //       { "group_name": "Painting", "name": "Mar", "value": 7520 },
      //       { "group_name": "Painting", "name": "Apr", "value": 2568 },
      //       { "group_name": "Painting", "name": "May", "value": 6583 },
      //       { "group_name": "Painting", "name": "Jun", "value": 44485 },
      //       { "group_name": "Painting", "name": "Jul", "value": 3405 },
      //       { "group_name": "Painting", "name": "Aug", "value": 31709 },
      //       { "group_name": "Painting", "name": "Sep", "value": 45442 },
      //       { "group_name": "Painting", "name": "Oct", "value": 37580 },
      //       { "group_name": "Painting", "name": "Nov", "value": 23445 },
      //       { "group_name": "Painting", "name": "Dec", "value": 7554 },
      //       { "group_name": "Acting", "name": "Jan", "value": 40110 },
      //       { "group_name": "Acting", "name": "Feb", "value": 35605 },
      //       { "group_name": "Acting", "name": "Mar", "value": 15768 },
      //       { "group_name": "Acting", "name": "Apr", "value": 15075 },
      //       { "group_name": "Acting", "name": "May", "value": 12424 },
      //       { "group_name": "Acting", "name": "Jun", "value": 12227 },
      //       { "group_name": "Acting", "name": "Jul", "value": 40906 },
      //       { "group_name": "Acting", "name": "Aug", "value": 34032 },
      //       { "group_name": "Acting", "name": "Sep", "value": 18110 },
      //       { "group_name": "Acting", "name": "Oct", "value": 4755 },
      //       { "group_name": "Acting", "name": "Nov", "value": 42202 },
      //       { "group_name": "Acting", "name": "Dec", "value": 36183 }
      //    ];
      // }
   </script>
@endsection