<div class="col-md-12">
    <div class="box box-solid">
        <div class="et">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-search"></i> View Student List</h3>
                <div class="box-tools">
                    <form id="w0" action="{{url("/communication/telephone-diary/student-contact/downlaod")}}" method="post">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="hidden" name="academic_year" @if(!empty($allSearchInputs['academic_year'])) value="{{$allSearchInputs['academic_year']}} @endif">
                        <input type="hidden" name="academic_level"  @if(!empty($allSearchInputs['academic_level']))  value="{{$allSearchInputs['academic_level']}}"  @endif>
                        <input type="hidden" name="batch"  @if(!empty($allSearchInputs['batch']))  value="{{$allSearchInputs['batch']}}" @endif>
                        <input type="hidden" name="section"  @if(!empty($allSearchInputs['section']))  value="{{$allSearchInputs['section']}}" @endif>
                        <input type="hidden" name="gr_no"  @if(!empty($allSearchInputs['gr_no']))  value="{{$allSearchInputs['gr_no']}}" @endif>
                        <input type="hidden" name="email"  @if(!empty($allSearchInputs['email']))  value="{{$allSearchInputs['email']}}" @endif>
                        <input type="hidden" name="username"  @if(!empty($allSearchInputs['username']))  value="{{$allSearchInputs['username']}}" @endif>
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

        <div class="box-body table-responsive">
            @if($allEnrollments->count()>0)
                <table id="example1" class="table table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>GR No.</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>User Name</th>
                        <th>Phone</th>
                        <th>Academic Year</th>
                        <th>Course Name</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th class="action-column">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    {{--find current paginate number--}}
                    @php $currentPage = $allEnrollments->currentPage(); @endphp
                    {{--reset item counter--}}
                    @php $i = ((($currentPage*20)-20)+1); @endphp
                    {{--student enrollment looping--}}
                    @foreach($allEnrollments as $enroll)
                        <tr>
                            <td>{{$i}}</td>
                            <td>{{$enroll->gr_no}}</td>
                            <td><a href="/student/profile/personal/{{$enroll->std_id}}"> {{$enroll->first_name." ".$enroll->middle_name." ".$enroll->last_name}}</a></td>
                            <td><a href="/student/profile/personal/{{$enroll->std_id}}">{{$enroll->email}}</a></td>
                            <td>{{$enroll->username}}</td>
                            <td>{{$enroll->student()->phone}}</td>
                            <td>{{$enroll->year()->year_name}}</td>
                            <td>{{$enroll->level()->level_name}}</td>
                            <td>{{$enroll->batch()->batch_name}} @if(isset($enroll->batch()->get_division()->name)) - {{$enroll->batch()->get_division()->name}}@endif</td>
                            <td>{{$enroll->section()->section_name}}</td>
                            <td>
                                <a href="/student/student-waiver/add-waiver/{{$enroll->std_id}}" title="waiver" data-target="#globalModal" data-toggle="modal">
                                    <span class="fa fa-caret-square-o-down fa-lg"></span>
                                </a>
                                <a href="#" title="Delete" data-confirm="Are you sure you want to delete this item?" data-method="get">
                                    <span class="glyphicon glyphicon-trash"></span>
                                </a>
                            </td>
                        </tr>
                        @php $i += 1; @endphp
                    @endforeach
                    </tbody>
                </table>
                {{--paginate--}}
                <div class="text-center">
                    {{ $allEnrollments->appends(Request::only([
                    'search'=>'search',
                    'filter'=>'filter',
                    'academic_level'=>'academic_level',
                    'batch'=>'batch',
                    'section'=>'section',
                    'gr_no'=>'gr_no',
                    'email'=>'email',
                    '_token'=>'_token',
                    ]))->render() }}
                </div>
            @else
                <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h6><i class="fa fa-warning"></i> No result found. </h6>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    $(function () {
        $("#example2").DataTable();
        $('#example1').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": false,
            "autoWidth": false
        });

        // paginating
        $('.pagination a').on('click', function (e) {
            e.preventDefault();
            var url = $(this).attr('href').replace('store', 'find');
            loadRolePermissionList(url);
            // window.history.pushState("", "", url);
            // $(this).removeAttr('href');
        });
        // loadRole-PermissionList
        function loadRolePermissionList(url) {
            $.ajax({
                url: url,
                type: 'POST',
                cache: false,
                beforeSend: function() {
                    // show waiting dialog
                    waitingDialog.show('Loading...');
                },
                success:function(data){
                    // hide waiting dialog
                    waitingDialog.hide();
                    // checking
                    if(data.status=='success'){
                        var std_list_container_row = $('#std_list_container_row');
                        std_list_container_row.html('');
                        std_list_container_row.append(data.html);
                    }else{
                        alert(data.msg)
                    }
                },
                error:function(data){
                    alert(JSON.stringify(data));
                }
            });
        }

    });
</script>