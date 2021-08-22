<div class="theme-style"></div>
<br/>
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
<div class="panel-group" id="accordion">
    {{--checking--}}
    @if(count($userInstituteArrayList)>0 AND $instituteList->count()>0)
        @foreach($instituteList as $unoInstitute)
            {{--checking uno institue array list--}}
            @if(array_key_exists($unoInstitute->institute_id, $userInstituteArrayList)==false) @continue @endif
            {{--user instiute campus list--}}
            @php $campusArrayList = $userInstituteArrayList[$unoInstitute->institute_id]; @endphp

            {{--institute profile--}}
            @php $institute = $unoInstitute->institute(); @endphp
            {{--student list--}}
            @php $allStudent = $institute->student(); @endphp
            {{--staff list--}}
            @php $allStaff = $institute->staff(); @endphp
            {{--campus list--}}
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
                                        <th> Student(s)</th>
                                        <th> Staff(s)</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($allCampus as $campus)
                                        {{--campus address--}}
                                        @php $instCampusAddress = $campus->instAddress(); @endphp
                                        {{--checking uno institue array list--}}
                                        @if(array_key_exists($campus->id, $campusArrayList)==false || ($instCampusAddress->city_id != $cityId)) @continue @endif
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td class="text-left"><a href="/admin/uno/institute/login/campus/{{$campus->id}}" target="_blank">{{$campus->name}}</a></td>
                                            <td>{{$campus->student()->count()}}</td>
                                            <td>{{$campus->staff()->count()}}</td>
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