{{--checking--}}
@if($campusList->count()>0)
    <div class="box box-solid">
        <div class="et">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-user"></i> User (Admin) Assignment</h3>
            </div>
        </div>
        <div class="box-body">

            <p class="bg-aqua-active text-bold text-center">User Information</p>
            <table class="table table-bordered table-striped text-center">
                <thead>
                <tr>

                    <th>Role</th>
                    <th>Name</th>
                    <th>Email</th>
                </tr>
                </thead>

                <tbody>
                <tr>
                    <td> {{$userProfile->roles()->count()>0?$userProfile->roles()->first()->display_name:'No Role'}} </td>
                    <td>{{$userProfile->name}}</td>
                    <td>{{$userProfile->email}}</td>
                </tr>
                </tbody>
            </table>
            <br/>
            <p class="bg-aqua-active text-bold text-center">Assignment History</p>
            <table class="table table-responsive text-center table-bordered table-striped">
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
                @php $userInstitutes = $userProfile->userInfo()->get(['campus_id','user_id']);
				$userCampusList = array();
				foreach($userInstitutes as $userInstitute){
					$userCampusList[$userInstitute->campus_id] = $userInstitute->user_id;
				}
                @endphp

                @php $i=1; @endphp
                @foreach($campusList as $campus)
                    <tr>
                        <td>{{$i++}}</td>
                        <td>{{$campus->name}}</td>
                        <td>{{$campus->campus_code}}</td>
                        @php $campusAddress = $campus->address(); @endphp
                        <td>{{$campusAddress?$campusAddress->address:"Not set"}}</td>
                        <td>
                            @php $campus_assigned = array_key_exists($campus->id, $userCampusList); @endphp
                            @if($campusId==$campus->id)
                                <p class="btn btn-default text-bold">Permanent Campus</p>
                            @else
                                <button type="button" id="{{$campus->id}}" data-key="{{$campus_assigned?'remove':'assign'}}" class="btn btn-{{$campus_assigned?'danger':'primary'}} assingment text-bold">
                                    {{$campus_assigned?'Remove':'Assign'}}
                                </button>

                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="alert-warning alert-auto-hide alert fade in text-center text-bold" style="opacity: 474.119;">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="fa fa-warning"></i> No records found. </h5>
    </div>
@endif

<script>
    $(document).ready(function () {
        $('.assingment').click(function () {
            var user_id = '{{$userId}}';
            var campus_id = $(this).attr('id');
            var institute_id = '{{$instituteId}}';
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
    });
</script>