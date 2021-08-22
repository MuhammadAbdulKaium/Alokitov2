@extends('layouts.master')

@section('content')
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css"/>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage |<small>Form</small></h1>
            <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/academics/default/index">Academics</a></li>
                <li class="active">Course Management</li>
                <li class="active">Form</li>
            </ul>
        </section>
        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
            @if(Session::has('message'))
                <p class="alert alert-success alert-auto-hide dism " style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('message') }}</p>
            @endif
        </div>
        <section class="content">
            <div class="box box-solid">
                <div>
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i>View Form List</h3>
                        <div class="box-tools">
                            <a class="btn btn-success btn-sm" href="{{url('academics/add-section')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-plus-square"></i> Add</a></div>
                    </div>
                </div>
                <div class="box-body table-responsive">
                    <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                        <div id="w1" class="grid-view">
                            <table id="myTable" class="table table-striped table-bordered text-center">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th><a  data-sort="sub_master_alias">Class Name</a></th>
                                    <th><a  data-sort="sub_master_alias">Group</a></th>
                                    <th><a  data-sort="sub_master_alias">Form Name</a></th>
                                    <th><a  data-sort="sub_master_alias">Intake</a></th>
                                    <th><a  data-sort="sub_master_alias">Status</a></th>
                                    <th><a>Action</a></th>
                                </tr>

                                </thead>
                                <tbody>

                                @if(isset($sections))
                                    @php $i = 1; @endphp
                                    @foreach($sections as $values)
                                        @php $batch = $values->batchName(); @endphp
                                        {{--checking--}}
                                        @if($batch==null) @continue @endif
                                        <tr class="gradeX">
                                            <td>{{$i++}}</td>
                                            <td>{{$batch?$batch->batch_name:''}}</td>
                                            @php $division = $batch->get_division(); @endphp
                                            {{-- <td>{{$division?$division->name:'-'}}</td> --}}
                                            <td>
                                                @foreach ($values->divisions as $division)
                                                <div class="badge badge-info">{{ $division->name }}</div>
                                                @endforeach
                                            </td>
                                            <td>{{$values->section_name }}</td>
                                            <td>{{$values->intake}}</td>
                                            <td>
                                                <a href="{{ url('academics/section-status-change', $values->id) }}" class="btn btn-xs" onclick="return confirm('Are you sure to Change Status?')" data-placement="top" data-content="delete">
                                                    @if($values->status==1)  <i class="fa fa-check" style="color:#0FFC45;" ></i>@endif
                                                    @if($values->status==0) <i class="fa fa-times" style="color:#F75432;"></i>@endif
                                                </a>
                                            </td>
                                            <td>
                                                <a href="" class="btn btn-primary btn-xs" id="section_view_{{$values->id}}" onclick="modalLoad(this.id)" data-target="#globalModal"  data-toggle="modal" data-placement="top" data-content="view"><i class="fa fa-eye"></i></a>
                                                <a href="" id="section_edit_{{$values->id}}" onclick="modalLoadEdit(this.id)" class="btn btn-primary btn-xs" data-target="#globalModal"  data-toggle="modal" data-placement="top" data-content="update"><i class="fa fa-edit"></i></a>
                                                <a href="{{ url('academics/delete-section', $values->id) }}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure to Delete?')" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                {{--{{ $data->render() }}--}}

                                </tbody>
                            </table>
                        </div>		</div>    </div><!-- /.box-body -->
            </div><!-- /.box-->
        </section>
    </div>
    <div id="slideToTop" ><i class="fa fa-chevron-up"></i>
    </div>

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

@section('scripts')

    <script src="{{ asset('js/datatable.js') }}" type="text/javascript"></script>
    <script type = "text/javascript">
        function modalLoad(rowId) {

            var data = rowId.split('_'); //To get the row id

            //   alert(data);
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {'X-CSRF-Token': $('meta[name=_token]').attr('content')},
                url: "{{ url('academics/view-section') }}" + '/' + data[2],
                type: 'GET',
                cache: false,
                data: {'_token': $_token}, //see the $_token
                datatype: 'html',

                beforeSend: function () {
                },

                success: function (data) {

                    // alert(data.length);
//                    $('.modal-content').html(data);
                    if (data.length > 0) {
                        // remove modal body
                        $('.modal-body').remove();
                        // add modal content
                        $('.modal-content').html(data);
                    } else {
                        // add modal content
                        $('.modal-content').html('info');
                    }
                }
            });

        }
        function modalLoadEdit(rowId) {

            var data = rowId.split('_'); //To get the row id

            //alert(data);
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {'X-CSRF-Token': $('meta[name=_token]').attr('content')},
                url: "{{ url('academics/edit-section-view') }}" + '/' + data[2],
                type: 'GET',
                cache: false,
                data: {'_token': $_token}, //see the $_token
                datatype: 'html',

                beforeSend: function () {
                },

                success: function (data) {

                    // alert(data.length);
//                    $('.modal-content').html(data);
                    if (data.length > 0) {
                        // remove modal body
                        $('.modal-body').remove();
                        // add modal content
                        $('.modal-content').html(data);
                    } else {
                        // add modal content
                        $('.modal-content').html('info');
                    }
                }
            });

        }
    </script>

    <script type="text/javascript">
        $(document).ready(function(){
            $('#myTable').DataTable();
        });
        jQuery(document).ready(function () {
            jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
                $(this).slideUp('slow', function() {
                    $(this).remove();
                });
            });
        });


        // request for batch list using level id
        jQuery(document).on('change','.academicYear',function(){
            // console.log("hmm its change");

            // get academic year id
            var year_id = $(this).val();
            var div = $(this).parent();
            var op="";

            $.ajax({
                url: "{{ url('/academics/find/level') }}",
                type: 'GET',
                cache: false,
                data: {'id': year_id }, //see the $_token
                datatype: 'application/json',

                beforeSend: function() {
                    console.log(year_id);

                },

                success:function(data){
                    console.log('success');

                    //console.log(data.length);
                    op+='<option value="0" selected disabled>--- Select Level ---</option>';
                    for(var i=0;i<data.length;i++){
                        // console.log(data[i].level_name);
                        op+='<option value="'+data[i].id+'">'+data[i].level_name+'</option>';
                    }

                    // set value to the academic batch
                    $('.academicBatch').html("");
                    $('.academicBatch').append('<option value="" selected disabled>--- Select Class ---</option>');

                    // set value to the academic batch
                    $('.academicLevel').html("");
                    $('.academicLevel').append(op);
                },

                error:function(){

                }
            });
        });

        // request for batch list using level id
        jQuery(document).on('change','.academicLevel', function(){
            // console.log("hmm its change");

            // get academic level id
            var level_id = $(this).val();
            var div = $(this).parent();
            var op="";

            $.ajax({
                url: "{{ url('/academics/find/batch') }}",
                type: 'GET',
                cache: false,
                data: {'id': level_id }, //see the $_token
                datatype: 'application/json',

                beforeSend: function() {
                    // console.log(level_id);
                },

                success:function(data){
                    console.log('success');

                    //console.log(data.length);
                    op+='<option value="" selected disabled>--- Select Class ---</option>';
                    for(var i=0;i<data.length;i++){
                        op+='<option value="'+data[i].id+'">'+data[i].batch_name+'</option>';
                    }

                    // set value to the academic batch
                    $('.academicBatch').html("");
                    $('.academicBatch').append(op);
                },

                error:function(){

                }
            });
        });


        // Request for finding batch divisions in Edit Modal
        jQuery(document).on('change', '.edit-academic-batch', function () {
            // console.log($(this).val());

            $.ajax({
                url: "{{ url('/academics/find/batch/division') }}",
                type: 'GET',
                cache: false,
                data: {'id': $(this).val() }, //see the $_token
                datatype: 'application/json',

                beforeSend: function() {
                    // console.log(level_id);
                },

                success:function(data){
                    if (data[0].length > 0) {
                        var txt = '';

                        data[0].forEach(function (item, index) {
                            txt += '<input checked disabled type="checkbox"> '+ item.name+' '+
                            '<input checked style="display: none" type="checkbox" name="divisions[]" value="'+ item.id +'"> ';
                        })

                        $('#edit-division-id').empty();
                        $('#edit-division-id').append(txt);
                    }else{
                        var txt = '';

                        data[1].forEach(function (item, index) {
                            txt += '<input type="checkbox" name="divisions[]" value="'+ item.id +'"> '+ item.name+' ';
                        })
                        
                        $('#edit-division-id').empty();
                        $('#edit-division-id').append(txt);
                    }
                },

                error:function(){

                }
            });
        });
        
        // Request for finding batch divisions
        jQuery(document).on('change', '.select-batch', function () {
            // console.log($(this).val());

            $.ajax({
                url: "{{ url('/academics/find/batch/division') }}",
                type: 'GET',
                cache: false,
                data: {'id': $(this).val() }, //see the $_token
                datatype: 'application/json',

                beforeSend: function() {
                    // console.log(level_id);
                },

                success:function(data){
                    if (data[0].length > 0) {
                        var txt = '';

                        data[0].forEach(function (item, index) {
                            txt += '<input checked disabled type="checkbox"> '+ item.name+' '+
                            '<input checked style="display: none" type="checkbox" name="section_divisions[]" value="'+ item.id +'"> ';
                        })

                        $('.division-checks').empty();
                        $('.division-checks').append(txt);
                    }else{
                        var txt = '';

                        data[1].forEach(function (item, index) {
                            txt += '<input type="checkbox" name="section_divisions[]" value="'+ item.id +'"> '+ item.name+' ';
                        })
                        
                        $('.division-checks').empty();
                        $('.division-checks').append(txt);
                    }
                },

                error:function(){

                }
            });
        });



    </script>

@endsection