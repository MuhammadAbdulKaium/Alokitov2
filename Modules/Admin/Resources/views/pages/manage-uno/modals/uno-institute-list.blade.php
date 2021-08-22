{{--checking--}}
{{--@if($campusList->count()>0)--}}
<div class="box box-solid">
    <div class="et">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-user"></i> User (HighAdmin) Assignment History</h3>
        </div>
    </div>
    <div class="box-body">

        <p class="bg-aqua-active text-bold text-center">User (HighAdmin) Information</p>
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
        <p class="bg-aqua-active text-bold text-center">Institute Assignment History</p>
        <div class="panel-group" id="accordion">
            @php
                // uno institute list
                $myInstituteList = $userProfile->userInfo()->get(['user_id','institute_id', 'campus_id']);
                // user institute array list
                $userInstituteArrayList = array();
                // institute list looping
                foreach($myInstituteList as $userInstitute){
                    $userInstituteArrayList[$userInstitute->institute_id][$userInstitute->campus_id] = $userInstitute->user_id;
                }
            @endphp

            {{--insitute list--}}
            @php $instituteList = $userProfile->userInfo()->distinct()->get(['institute_id']); @endphp
            {{--checking--}}
            @if($instituteList->count()>0)
                @foreach($instituteList as $unoInstitute)
                    {{--checking uno institue array list--}}
                    @if(array_key_exists($unoInstitute->institute_id, $userInstituteArrayList)==false) @continue @endif
                    {{--user instiute campus list--}}
                    @php $campusArrayList = $userInstituteArrayList[$unoInstitute->institute_id]; @endphp

                    {{--campus list--}}
                    @php $institute = $unoInstitute->institute(); @endphp
                    @php $allCampus = $institute->campus(); @endphp
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$institute->id}}">
                                    <span class="glyphicon glyphicon-plus icon-margin"></span> {{$institute->institute_name}}
                                </a>
                            </h4>
                        </div>
                        <div id="collapse{{$institute->id}}" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    {{--checking--}}
                                    @if($allCampus->count()>0)
                                        @php $i=1; @endphp
                                        <table class="table table-responsive table-striped text-center" style="font-size:15px">
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
                                            @foreach ($allCampus as $campus)
                                                {{--checking uno institue array list--}}
                                                @if(array_key_exists($campus->id, $campusArrayList)==false) @continue @endif
                                                <tr>
                                                    <td>{{$i}}</td>
                                                    <td>{{$campus->name}}</td>
                                                    <td>{{$campus->campus_code}}</td>
                                                    @php $campusAddress = $campus->address(); @endphp
                                                    <td>{{$campusAddress?$campusAddress->address:"Not set"}}</td>
                                                    <td>
                                                        <button type="button" id="{{$campus->id}}" data-id="{{$institute->id}}" data-key="remove" class="btn btn-danger assingment text-bold">Remove</button>
                                                    </td>
                                                </tr>
                                                @php $i+=1; @endphp
                                            @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <div class="panel-heading ">
                                            <h4 class="panel-title alert bg-warning text-warning">
                                                There is no campus in this institute
                                            </h4>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-md-12">
                    <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="fa fa-times" aria-hidden="true"></i>No Records found</h4>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('.assingment').click(function () {
            var user_id = '{{$userProfile->id}}';
            var campus_id = $(this).attr('id');
            var institute_id = $(this).attr('data-id');
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