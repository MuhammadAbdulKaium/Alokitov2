<!-- DataTables -->
<link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>

<div class="box box-solid">
    <div class="et">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-search"></i> View Employee List ({{$allEmployee->count()}})</h3>
            <div class="box-tools">
                <form id="w0" action="{{url("/employee/manage/download/excel")}}" method="post">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <input type="hidden" name="department" @if(!empty($allSearchInputs['department'])) value="{{$allSearchInputs['department']}} @endif">
                    <input type="hidden" name="designation"  @if(!empty($allSearchInputs['designation']))  value="{{$allSearchInputs['designation']}}"  @endif>
                    <input type="hidden" name="category"  @if(!empty($allSearchInputs['category']))  value="{{$allSearchInputs['category']}}" @endif>
                    <input type="hidden" name="email"  @if(!empty($allSearchInputs['email']))  value="{{$allSearchInputs['email']}}" @endif>
                    <input type="hidden" name="gr_no"  @if(!empty($allSearchInputs['gr_no']))  value="{{$allSearchInputs['gr_no']}}" @endif>
                    <input type="hidden" name="emp_id"  @if(!empty($allSearchInputs['id']))  value="{{$allSearchInputs['id']}}" @endif>
                    {{--<input type="hidden" name="section" value="{{$allSearchInputs['section']}}">--}}
                    {{--<input type="hidden" name="stu_detail_search" value="{{$allSearchInputs['academic_level']}}">--}}
                    <input type="hidden" name="student_name" value="{{csrf_token()}}">
                    <button type="submit" class="btn btn-primary">
                        <i class="icon-user icon-white"></i> Excel
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="box-body">
        @if(!empty($allEmployee) AND $allEmployee->count()>0)
            <table id="example2" class="table table-striped table-bordered table-responsive text-center">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Nick Name</th>
                    <th>Email/Login Id</th>
                    <th>Department</th>
                    <th>Designation</th>

                    <th width="100px">
                        {{--<input type="checkbox" id="emp_sort_order">  --}}
                        Position
                    </th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody id="table">
                @foreach($allEmployee as $index=>$employee)
                    <tr>
                        <td>{{($index+1)}}</td>
                        <td><a href="{{url('/employee/profile/personal/'.$employee->id)}}">{{$employee->first_name." ".$employee->middle_name." ".$employee->last_name}}</a></td>
                        <td>{{$employee->alias}}</td>
                        <td><a href="{{url('/employee/profile/personal/'.$employee->id)}}">{{$employee->email}}</a></td>
                        <td>
                            @if(!empty($employee->department()))
                            {{$employee->department()->name}}
                                @endif

                        </td>
                        <td>
                            @if(!empty($employee->designation()))
                                {{$employee->designation()->name}}
                            @endif

                        </td>
                        <td>
                            {{--Checking Status--}}
                            @if($employee->status==1)
                            <div class="form-group">
                                <input id="{{$employee->id}}" class="form-control text-center sort_order" name="sort_order" placeholder="Web Position" type="text" value="{{$employee->sort_order==999?0:$employee->sort_order}}" readonly required>
                                <div class="help-block"></div>
                            </div>
                            @else
                                <span class="label label-warning">N/A</span>
                            @endif
                        </td>
                        <td>
                            {{--Checking Status--}}
                            @if($employee->status==1)
                                <span class="label label-success">ACTIVE</span>
                            @else
                                <span class="label label-warning">Retired</span>
                            @endif
                        </td>
                        <td>

                            @if($employee->status!=1)
                                <a href="{{URL::to('employee/employee-status/change',$employee->id)}}" title="" data-confirm="Are you sure you want to delete this item?" data-method="get"><button class="btn btn-primary">Active</button> </a>

                            @else
                                <a href="{{URL::to('employee/employee-status/change',$employee->id)}}" title="" data-confirm="Are you sure you want to delete this item?" data-method="get"><button class="btn btn-primary">Deactive</button> </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="fa fa-warning"></i> No result found. </h5>
            </div>
        @endif
    </div>
</div>
<!-- DataTables -->
<script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>

<script>
    jQuery(document).ready(function () {

        $("#example1").DataTable();
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": true
        });

        // emp_web_sort
        //emp_web_sort();

        // emp_sort_order click action
        $("#emp_sort_order").click(function(){
            // checking
            if($(this).is(':checked')){
                // attendance looping
                $("#table input").each(function() {
                    // remove class
                    $(this).removeAttr('readonly');
                });
                // emp_web_sort
                //emp_web_sort();
            }else{
                // attendance looping
                $("#table input").each(function() {
                    // remove class
                    $(this).attr('readonly', 'readonly');
                });
                // emp_web_sort
                //emp_web_sort();
            }
        });

        $("#table input").keyup(function(){

            var emp_id = $(this).attr('id');
            var web_position = $(this).val();
            var _token = '{{csrf_token()}}';
            // change background color
            $(this).css("background-color", "pink");

            // checking
            if(web_position && emp_id && $.isNumeric(web_position)){
                // ajax request
                $.ajax({
                    url: '/employee/update/web-position',
                    type: 'POST',
                    cache: false,
                    data: {'emp_id': emp_id,'sort_order': web_position,'_token': _token }, //see the $_token
                    datatype: 'application/json',

                    beforeSend: function() {
                        // show waiting dialog
                        // waitingDialog.show('Loading...');
                    },

                    success:function(data){
                        // hide waiting dialog
                        //waitingDialog.hide();
                        // background
                    },

                    error:function(){
                        // hide waiting dialog
                        //waitingDialog.hide();
                        // sweet alert
                        swal("Error", 'Unable to load data form server', "error");
                    }
                });
            }else{
                swal("Warning", 'Invalid input', "warning");
                $(this).val('');
            }

        });

        {{--function emp_web_sort() {--}}
        {{--$("#table input").keyup(function(){--}}

        {{--var emp_id = $(this).attr('id');--}}
        {{--var web_position = $(this).val();--}}
        {{--var _token = '{{csrf_token()}}';--}}

        {{--// checking--}}
        {{--if(web_position && emp_id){--}}
        {{--// ajax request--}}
        {{--$.ajax({--}}
        {{--url: '/employee/update/web-position',--}}
        {{--type: 'POST',--}}
        {{--cache: false,--}}
        {{--data: {'emp_id': emp_id,'sort_order': web_position,'_token': _token }, //see the $_token--}}
        {{--datatype: 'application/json',--}}

        {{--beforeSend: function() {--}}
        {{--// show waiting dialog--}}
        {{--waitingDialog.show('Loading...');--}}
        {{--},--}}

        {{--success:function(data){--}}
        {{--// hide waiting dialog--}}
        {{--waitingDialog.hide();--}}
        {{--// background--}}
        {{--},--}}
        {{--error:function(){--}}
        {{--// hide waiting dialog--}}
        {{--waitingDialog.hide();--}}
        {{--// sweet alert--}}
        {{--swal("Error", 'Unable to load data form server', "error");--}}
        {{--}--}}
        {{--});--}}
        {{--}--}}

        {{--});--}}
        {{--}--}}




    });
</script>