{{--checking--}}
@if($userProfile)
    {{--modal script--}}
    <style type="text/css">
        .ui-autocomplete {z-index:2147483647;}
        .ui-autocomplete span.hl_results {background-color: #ffff66;}
    </style>

    <div class="box box-solid">
        <div class="et">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-user"></i> User (HighAdmin) Assignment</h3>
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
                    <input id="user_id" name="user_id" type="hidden" value="{{$userProfile->id}}">
                    <td> {{$userProfile->roles()->count()>0?$userProfile->roles()->first()->display_name:'No Role'}} </td>
                    <td>{{$userProfile->name}}</td>
                    <td>{{$userProfile->email}}</td>
                </tr>
                </tbody>
            </table>
            <br/>
            {{--<p class="bg-aqua-active text-bold text-center">Institute Assignment</p>--}}
            <div class="row">
                <div class="col-md-9">
                    <div class="col-md-10 col-md-offset-1">
                        <div class="form-group">
                            <input class="form-control" id="inst_name" type="text" placeholder="Type Institute Name">
                            <input id="inst_id" name="inst_id" type="hidden" value="">
                            <div class="help-block"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <p id="view_institute" class="btn btn-primary pull-left text-center"><i class="fa fa-click"></i> View Institute</p>
                </div>
            </div>
            <div class="row">
                <div id="institute_container_row" class="col-md-12"></div>
            </div>
        </div>
    </div>
@else
    <div class="alert-warning alert-auto-hide alert fade in text-center text-bold" style="opacity: 474.119;">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="fa fa-warning"></i> User not found. </h5>
    </div>
@endif

<script>
    $(document).ready(function () {

        // document ready
        $('#inst_name').keypress(function(){
            // clear inst_id
            $('#inst_id').val('');
            // emplty std_report_card_row
            $('#institute_container_row').html('');
            // autoComplete
            $(this).autocomplete({
                source: loadFromAjax,
                minLength: 1,

                select: function (event, ui) {
                    // Prevent value from being put in the input:
                    this.value = ui.item.label;
                    // Set the next input's value to the "value" of the item.
                    $(this).next("input").val(ui.item.id);
                    event.preventDefault();
                }
            });

            // institute loader function
            function loadFromAjax(request, response) {
                var term = $("#inst_name").val();
                if(term.length > 2){
                    $.ajax({
                        url: '/setting/find/institute/',
                        dataType: 'json',
                        data:{'term': term},
                        success: function(data) {
                            // you can format data here if necessary
                            response($.map(data, function (el) {
                                return {
                                    label: el.name,
                                    value: el.name,
                                    id:el.id
                                };
                            }));
                        }
                    });
                }
            }
        });

        // view institute details
        $('#view_institute').click(function () {
            // request details
            var inst_id = $('#inst_id');
            var inst_name = $('#inst_name');
            var user_id = $('#user_id').val();

            // checking institute id
            if(inst_id.val()){
                // ajax request
                $.ajax({
                    url: "/setting/uno/institute/assign",
                    type: 'GET',
                    cache: false,
                    data:{'user_id': user_id, 'institute_id':inst_id.val()},
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
                            $('#institute_container_row').html(data.content);
                        }else{
                            alert(data.msg);
                        }
                    },

                    error:function(data){
                        waitingDialog.hide();
                        // statements
                        alert(JSON.stringify(data));
                    }
                });
            }else{
                // clear inst_name
                inst_name.val('');
                // alert notification
                alert('Please select a institute name')
            }

        });

        {{--$('.assingment').click(function () {--}}
            {{--var user_id = '{{$userId}}';--}}
            {{--var campus_id = $(this).attr('id');--}}
            {{--var institute_id = '{{$instituteId}}';--}}
            {{--var assignment_type = $(this).attr('data-key');--}}
            {{--var token = '{{csrf_token()}}';--}}
            {{--// ajax request--}}
            {{--$.ajax({--}}
                {{--url: "/setting/institute/campus/assign",--}}
                {{--type: 'POST',--}}
                {{--cache: false,--}}
                {{--data:{'user_id': user_id, 'campus_id':campus_id, 'institute_id':institute_id, 'assignment_type':assignment_type, '_token':token},--}}
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
                        {{--var button = $('#'+campus_id);--}}
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