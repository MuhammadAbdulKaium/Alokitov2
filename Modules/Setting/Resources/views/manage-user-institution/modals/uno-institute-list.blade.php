{{--user porfile details--}}
@php
    // uno institute list
    $myInstituteList = $userProfile->userInfo()->distinct()->get(['campus_id']);
    // user institute array list
    $userCampusList = array();
    // institute list looping
    foreach($myInstituteList as $userInstitute){
        $userCampusList[$userInstitute->campus_id] = $userProfile->id;
    }
@endphp
<!-- DataTables -->
<link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
<p class="bg-aqua-active text-bold text-center">Assignment History</p>
<table id="example1" class="table table-responsive text-center table-bordered table-striped">
    <thead>
    <tr>
        <th>#</th>
        <th>Campus Name</th>
        <th>Campus Alias</th>
        <th>Address</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @php $campusList = $instituteProfile->campus(); @endphp
    {{--checking campus list--}}
    @if($campusList->count()>0)
        @foreach($campusList as $index=>$campus)
            <tr>
                <td>{{($index+1)}}</td>
                <td>{{$campus->name}}</td>
                <td>{{$campus->campus_code}}</td>

                @php $campusAddress = $campus->address(); @endphp
                <td>{{$campusAddress?$campusAddress->address:"Not set"}}</td>
                <td>
                    {{--checking campus assignment--}}
                    @if(array_key_exists($campus->id, $userCampusList)==true)
                        <button type="button" id="{{$campus->id}}" data-key="remove" class="btn btn-danger assingment text-bold">Remove</button>
                    @else
                        <button type="button" id="{{$campus->id}}" data-key="assign" class="btn btn-primary assingment text-bold">Assign</button>
                    @endif
                </td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="5">No records found.</td>
        </tr>
    @endif
    </tbody>
</table>
<!-- DataTables -->
<script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>

<script>
    $(document).ready(function () {

        $("#example1").DataTable();
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false
        });

        $('.assingment').click(function () {
            var user_id = '{{$userProfile->id}}';
            var campus_id = $(this).attr('id');
            var institute_id = '{{$instituteProfile->id}}';
            var assignment_type = $(this).attr('data-key');
            var token = '{{csrf_token()}}';
            // ajax request
            $.ajax({
                url: "/setting/institute/campus/assign",
                type: 'POST',
                cache: false,
                data:{'user_id': user_id, 'campus_id':campus_id, 'institute_id':institute_id, 'assignment_type':assignment_type, '_token':token},
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
                        var button = $('#'+campus_id);
                        // checking
                        if(assignment_type=='assign'){
                            button.removeClass('btn-primary');
                            button.addClass('btn-danger');
                            button.removeAttr('data-key');
                            button.attr('data-key', 'remove');
                            button.html('Remove');
                        }else{
                            button.removeClass('btn-danger');
                            button.addClass('btn-primary');
                            button.removeAttr('data-key');
                            button.attr('data-key', 'assign');
                            button.html('Assign');
                        }
                    }else{
                        alert(data.msg);
                    }
                },

                error:function(data){
                    // statements
                    alert(JSON.stringify(data));
                }
            });

        });



        {{--$('.assignment').click(function () {--}}
            {{--var user_id = '{{$userProfile->id}}';--}}
            {{--var institute_id = '{{$instituteProfile->id}}';--}}
            {{--var assignment_type = $(this).attr('data-key');--}}
            {{--var token = '{{csrf_token()}}';--}}
            {{--// ajax request--}}
            {{--$.ajax({--}}
                {{--url: "/setting/uno/institute/assign",--}}
                {{--type: 'POST',--}}
                {{--cache: false,--}}
                {{--data:{'user_id': user_id, 'institute_id':institute_id, 'assignment_type':assignment_type, '_token':token},--}}
                {{--datatype: 'application/json',--}}

                {{--beforeSend: function() {--}}
                    {{--// show waiting dialog--}}
                    {{--waitingDialog.show('Loading...');--}}
                {{--},--}}

                {{--success:function(data){--}}
                    {{--// hide waiting dialog--}}
                    {{--waitingDialog.hide();--}}
                    {{--// checking--}}
                    {{--if(data.status=='success'){--}}
                        {{--var button = $('#inst_id_'+institute_id);--}}
                        {{--// checking--}}
                        {{--if(assignment_type=='assign'){--}}
                            {{--button.removeClass('btn-primary');--}}
                            {{--button.addClass('btn-danger');--}}
                            {{--button.removeAttr('data-key');--}}
                            {{--button.attr('data-key', 'remove');--}}
                            {{--button.html('Remove');--}}
                        {{--}else{--}}
                            {{--button.removeClass('btn-danger');--}}
                            {{--button.addClass('btn-primary');--}}
                            {{--button.removeAttr('data-key');--}}
                            {{--button.attr('data-key', 'assign');--}}
                            {{--button.html('Assign');--}}
                        {{--}--}}
                    {{--}else{--}}
                        {{--alert(data.msg);--}}
                    {{--}--}}
                {{--},--}}

                {{--error:function(data){--}}
                    {{--// statements--}}
                    {{--alert(JSON.stringify(data));--}}
                {{--}--}}
            {{--});--}}

        {{--});--}}
    });
</script>