<style>
    .input-group {
        min-width: 200px;
    }
</style>
<div class="col-md-12">
    <div class="box box-solid">
        <h4 class="text-center bg-green-active text-bold">Manage Student (Profile) List</h4>


        <div class="box-tools" style="margin: 15px;">
            <form id="w0" action="{{url("/student/update/profile/download/excel")}}" method="post">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <input type="hidden" name="academic_year" @if(!empty($allSearchInputs['academic_year'])) value="{{$allSearchInputs['academic_year']}} @endif">
                <input type="hidden" name="academic_level"  @if(!empty($allSearchInputs['academic_level']))  value="{{$allSearchInputs['academic_level']}}"  @endif>
                <input type="hidden" name="batch"  @if(!empty($allSearchInputs['batch']))  value="{{$allSearchInputs['batch']}}" @endif>
                <input type="hidden" name="section"  @if(!empty($allSearchInputs['section']))  value="{{$allSearchInputs['section']}}" @endif>
                <input type="hidden" name="gr_no"  @if(!empty($allSearchInputs['gr_no']))  value="{{$allSearchInputs['gr_no']}}" @endif>
                <input type="hidden" name="email"  @if(!empty($allSearchInputs['email']))  value="{{$allSearchInputs['email']}}" @endif>
                {{--<input type="hidden" name="section" value="{{$allSearchInputs['section']}}">--}}
                {{--<input type="hidden" name="stu_detail_search" value="{{$allSearchInputs['academic_level']}}">--}}
                <button type="submit" class="btn btn-success" style="float: left">
                    <i class="icon-user icon-white"></i> Export Student Profile
                </button>
            </form>

            <a  href="/student/update/profile/import" class="btn btn-primary" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"  style="margin-left: 20px">Import Student Profile</a>
        </div>

       <div id="studentList">
        <div class="box-body table-responsive">
            @if($allEnrollments->count()>0)
                <form id="manage_student_profile_form">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <input type="hidden" name="request_type" value="manage_std_profile">
                    <input type="hidden" id="std_count" value="{{$allEnrollments->count()}}">

                    <table id="example1" class="table table-striped text-center">
                        <thead>
                        <tr class="bg-gray">
                            <th>#</th>
                            <th>Roll NO.</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Punch ID</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($allEnrollments as $index=>$enroll)
                            <tr>
                                <td>{{($index+1)}}</td>
                                <td>
                                    <div class="input-group">
                                        <input type="text" class="form-control text-center" name="std_list[{{$enroll->std_id}}][gr_no]" value="{{$enroll->gr_no}}">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="text" class="form-control text-center" name="std_list[{{$enroll->std_id}}][name]" value="{{$enroll->first_name." ".$enroll->middle_name." ".$enroll->last_name}}">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="text" class="form-control text-center" name="std_list[{{$enroll->std_id}}][username]" value="{{$enroll->username}}">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="text" class="form-control text-center" name="std_list[{{$enroll->std_id}}][email]" value="{{$enroll->email}}">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="text" class="form-control text-center" name="std_list[{{$enroll->std_id}}][punch_id]" value="{{$enroll->student()->punch_id}}">
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-success pull-right">Submit</button>
                    </div>
                </form>
            @else
                <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in text-center" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="fa fa-warning"></i> No result found. </h5>
                </div>
            @endif
        </div>
       </div>
    </div>
</div>

<script>
    $(function () {
        $("#example13").DataTable();

        var myDataTable = $('#example1').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "pageLength": 25
        });


        // request for parent list using batch section id
        $('form#manage_student_profile_form').on('submit', function (e) {
            e.preventDefault();

            // subject info
            // dataTable row calculation
            var rowData = $();
            for (var i = 0; i < myDataTable.rows()[0].length; i++) {
                rowData = rowData.add(myDataTable.row(i).node())
            }
            // json obj
            var json_obj = {};
            // input token
            json_obj['_token'] = '{{csrf_token()}}';
            json_obj['request_type'] = 'manage_std_profile';
            json_obj['std_count'] = $('#std_count').val();
            // input others data
            rowData.find('input').each(function(i, el) {
                json_obj[$(el).attr('name')] = $(el).val();
            });

            // ajax request
            $.ajax({
                url: "/student/update/profile",
                type: 'POST',
                cache: false,
                data: json_obj,
                datatype: 'application/json',

                beforeSend: function() {
                    // show waiting dialog
                    waitingDialog.show('Loading...');
                },

                success:function(data){
                    // hide waiting dialog
                    waitingDialog.hide();
                    // checking
                    if(data.status=='success'){
                        // sweet alert
                        swal("Success", data.msg, "success");
                    }else{
                        // sweet alert
                        swal("Warning", data.msg, "warning");
                    }
                },

                error:function(data){
                    // hide waiting dialog
                    waitingDialog.hide();
                    // sweet alert
                    swal("Error", 'Unable to load data form server', "error");
                }
            });
        });


    });








</script>