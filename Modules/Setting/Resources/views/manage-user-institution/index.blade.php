@extends('setting::layouts.master')
<link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>


@section('section-title')
    <h1>
        <i class="fa fa-plus-square"></i> Manage User Institution
    </h1>
    <ul class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
        <li><a href="/academics/">Setting</a></li>
        <li class="active">Manage User Institution</li>
    </ul>
@endsection

@section('page-content')
    @if(Session::has('success'))
        <div id="w0-success-0" class="alert-success alert-auto-hide alert fade in" style="opacity: 423.642;">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> {{ Session::get('success') }} </h4>
        </div>
    @elseif(Session::has('warning'))
        <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> {{ Session::get('warning') }} </h4>
        </div>
    @endif

    <div class="box box-solid">
        <div class="et">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-search"></i> Search User</h3>
                {{--<div class="box-tools">--}}
                {{--<a class="btn btn-success btn-sm" href="#"><i class="fa fa-plus-square"></i> Add</a>--}}
                {{--</div>--}}
            </div>
        </div>
        <form id="user_institution_assignment_search_form">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="control-label" for="institute">Institute</label>
                            <select id="institute" class="form-control institute changer" name="institute" required>
                                <option value="">--- Select institute ---</option>
                                @foreach($instituteList as $institute)
                                    <option value="{{$institute->id}}">{{$institute->institute_name}}</option>
                                @endforeach
                            </select>
                            <div class="help-block"></div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="control-label" for="campus">Campus</label>
                            <select id="campus" class="form-control campus changer" name="campus" required>
                                <option value="" selected disabled>--- Select Campus ---</option>
                            </select>
                            <div class="help-block"></div>
                        </div>
                    </div>
                    <div class="col-sm-4" style="margin-top: 25px;">
                        <div class="form-group">
                            <input id="user_name" class="form-control" placeholder="Enter user name or e-mail address" type="text" required>
                            <input id="user_id" name="user_id" value="" type="hidden">
                            <div class="help-block"></div>
                        </div>
                    </div>
                </div>
                <!-- ./box-body -->
                <div class="box-footer pull-right">
                    <button type="reset" class="btn btn-default">Reset</button>
                    <button type="submit" class="btn btn-info">Submit</button>
                </div>
            </div>
        </form>
    </div>

    {{--user assignment--}}
    <div class="row">
        <div class="col-md-12">
            <div id="user_institution_assignment_container">
                {{--user assignment form will be here--}}
            </div>
        </div>
    </div>
    </section>
    </div>

    <!-- global modal -->
    <div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="loader">
                        <div class="es-spinner">
                            <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
<!-- DataTables -->
<script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>


@section('page-script')
    $(function () {
    $("#example1").DataTable();
    $('#example2').DataTable({
    "paging": true,
    "lengthChange": false,
    "searching": false,
    "ordering": true,
    "info": true,
    "autoWidth": false
    });

    $('.alert-auto-hide').fadeTo(7500, 500, function () {
    $(this).slideUp('slow', function () {
    $(this).remove();
    });
    });

    // request for section list using batch and section id
    $('form#user_institution_assignment_search_form').on('submit', function (e) {
    e.preventDefault();

    if($('#user_id').val() && $('#campus').val() ){
    // ajax request
    $.ajax({
    url: "/setting/institute/campus/assign/user",
    type: 'GET',
    cache: false,
    data: $('form#user_institution_assignment_search_form').serialize(),
    datatype: 'html',

    beforeSend: function() {
    // show waiting dialog
    waitingDialog.show('Loading...');
    },

    success:function(data){
    var assignment_container = $('#user_institution_assignment_container');
    assignment_container.html('');
    assignment_container.append(data);
    // hide waiting dialog
    waitingDialog.hide();
    },

    error:function(data){
    // statements
    alert(JSON.stringify(data));
    }
    });
    }else{
    $('#user_id').val('');
    $('#user_name').val('');
    $('#user_institution_assignment_container').html('');
    alert('Please double check all inputs are selected.');
    }

    });

    $('#user_name').keypress(function(){
    // empty user_id
    $('#user_id').val('');
    $('#user_institution_assignment_container').html('');
    // checking
    if($('#campus').val()){
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

    function loadFromAjax(request, response) {
    var term = $("#user_name").val();
    var campus_id = $('#campus').val();
    var institute_id = $('#institute').val();
    $.ajax({
    url: '/setting/find/user',
    dataType: 'json',
    data:{'term': term, 'campus_id':campus_id, 'institute_id':institute_id},
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
    }else{
    $('#user_id').val('');
    $('#user_name').val('');
    $('#user_institution_assignment_container').html('');
    alert('Please select a campus');

    }
    });



    // reset user name and id
    jQuery(document).on('change','.changer',function(){
    $('#user_id').val('');
    $('#user_name').val('');
    $('#user_institution_assignment_container').html('');
    });

    // request for campus list using institute id
    jQuery(document).on('change','.institute',function(){
    // get institute id
    var institute_id = $(this).val();
    var options = "";
    // ajax request
    $.ajax({
    url: "/setting/find/campus",
    type: 'GET',
    cache: false,
    data: {'id': institute_id }, //see the $_token
    datatype: 'application/json',

    beforeSend: function() {
    // statements
    },

    success:function(data){
    options+='<option value="" selected disabled>--- Select Campus ---</option>';
    for(var i=0;i<data.length;i++){
    options+='<option value="'+data[i].id+'">'+data[i].name+'</option>';
    }
    // set value to the academic batch
    $('.campus').html("");
    $('.campus').append(options);
    },

    error:function(){
    // statements
    }
    });
    });






    });

@endsection

