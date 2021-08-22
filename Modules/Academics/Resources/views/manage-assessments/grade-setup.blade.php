
@extends('academics::manage-assessments.index')

<!-- page content -->
@section('page-content')
        <div class="col-md-5 col-md-offset-1">
          <h4><strong>Grading Categories</strong> </h4>
          <p class="text-right">
            <a href="{{url('academics/manage/assessments/category/create')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-plus-square" aria-hidden="true"></i> Add Category</a>
          </p>
          <table class="table table-bordered">
              <thead>
                  <tr>
                    <th class="text-center" style="width:50px"> # </th>
                    <th>Category Name</th>
                    <th class="text-center">Type</th>
                    <th class="text-center">Actions</th>
                  </tr>
              </thead>
              <tbody>
                  @if($allGradeCategory->count() >0)
                    @php $i=1; @endphp
                    @foreach($allGradeCategory as $category)
                    <tr>
                      <td class="text-center">{{$i++}}</td>
                      <td>{{$category->name}}</td>
                      <td class="text-center">{{$category->is_sba==0?'Assessment':'SBA'}}</td>
                      <td class="text-center">
                        <a href="/academics/manage/assessments/category/edit/{{$category->id}}" title="Edit" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><span class="fa fa-pencil-square-o"></span></a>
                        <a href="/academics/manage/assessments/category/delete/{{$category->id}}" title="Delete" data-confirm="Are you sure you want to delete this item?" data-method="get"><span class="glyphicon glyphicon-trash"></span></a>
                      </td>
                    </tr>
                    @endforeach
                  @else
                    <tr>
                      <td colspan="3" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="fa fa-warning"></i></i> Grading Categroy is empty
                      </td>
                    </tr>
                  @endif
              </tbody>
          </table>
        </div>

        <!-- grading scale -->
        <div class="col-md-4 col-md-offset-1">
          <h4><strong>Grading Scales</strong></h4>
          <ul class="nav nav-tabs nav-justified">
            <li class="active"><a data-toggle="tab" href="#grade">Grades</a></li>
            {{--<li><a data-toggle="tab" href="#percentage">Percentage</a></li>--}}
            {{--<li><a data-toggle="tab" href="#manual">Manual Entry</a></li>--}}
          </ul>
          <div class="tab-content">
            <div id="grade" class="tab-pane fade in active">
              <p class="text-right" style="margin-top:15px;">
                <a href="{{url('academics/manage/assessments/grade/create')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-plus-square" aria-hidden="true"></i> Add New Grades</a>
              </p>
              <table class="table table-bordered">
                  <thead>
                      <tr>
                        <th class="text-center" style="width:100px"> # </th>
                        <th> Name</th>
                        <th class="text-center">Actions</th>
                      </tr>
                    </thead>
                  <tbody id="gradeBody">
                      @php $x=1; @endphp
                      @foreach($allGrade as $grade)
                      <tr id="grade_row_{{$grade->id}}">
                        <td class="text-center">{{$x++}}</td>
                        <td>{{$grade->name}}</td>
                        <td class="text-center">
                          <a href="{{url('academics/manage/assessments/grade/edit/'.$grade->id)}}" title="Edit" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><span class=" fa fa-pencil-square-o"></span></a>
                          <a id="{{$grade->id}}" style="cursor: pointer;" onclick="deleteGradeScale(this.id)" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>
                        </td>
                      </tr>
                      @endforeach
                  </tbody>
              </table>
            </div>
            <div id="percentage" class="tab-pane fade">
                <h3>Menu 1</h3>
                <p>Some content in menu 1.</p>
            </div>
            <div id="manual" class="tab-pane fade">
                <h3>Menu 2</h3>
                <p>Some content in menu 2.</p>
            </div>
          </div>
        </div>
@endsection

@section('page-script')
        <script type="text/javascript">
            $(document).ready(function(){
              $('.alert-auto-hide').fadeTo(7500, 500, function(){
                $(this).slideUp('slow', function(){
                  $(this).remove();
                });
              });
            });

            function deleteGradeScale(id){
              var x = confirm("Are you sure you want to delete?");
              if (x){
                    var td = '';
                    $.ajax({
                      type: 'get',
                      url: '/academics/manage/assessments/grade/delete/'+id,
                      datatype: 'application/json',

                      beforeSend: function() {
                        // statement
                      },

                      success: function (data) {
                        // checking response
                          if(data.status=='success'){
                            $('#grade_row_'+id).remove();
                          }else {
                            alert(data.msg);
                          }
                      }
                    });
              }else{
                  return false;
              }
            }


        </script>
@endsection
