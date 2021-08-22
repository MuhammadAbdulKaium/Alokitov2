@extends('layouts.master')

@section('styles')
    <!-- DataTables -->
    <link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
@endsection

<!-- page content -->
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage |<small>Institute</small></h1>
            <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/academics/default/index">Setting</a></li>
                <li class="active">Institute</li>
            </ul>
        </section>

        <section class="content">

            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            <div>
                @if(Session::has('message'))
                    <p class="alert alert-success alert-auto-hide dism " style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('message') }}</p>
                @endif
            </div>

            <div class="box box-solid">
                <div>
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i>View Institute List</h3>
                        <div class="box-tools">
                            <a class="btn btn-success btn-sm" href="{{url('setting/add-institute')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">Add Institute</a></div>
                    </div>
                </div>
                <div class="box-body table-responsive">
                    <table id="example1" class="table table-striped table-responsive">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th><a  data-sort="sub_master_name">Institute Name</a></th>
                            <th><a  data-sort="sub_master_code">Institute Alias</a></th>
                            <th><a  data-sort="sub_master_alias">Address</a></th>
                            <th><a  data-sort="sub_master_alias">Phone</a></th>
                            <th><a  data-sort="sub_master_alias">Email</a></th>
                            <th><a  data-sort="sub_master_alias">Website</a></th>
                            <th><a  data-sort="sub_master_alias">Logo</a></th>


                            <th><a>Action</a></th>
                        </tr>

                        </thead>
                        <tbody>
                        @if($institutes->count()>0)
                            @php $i = 1  @endphp
                            @foreach($institutes as $institute)
                                <tr class="gradeX">
                                    <td>{{$i++}}</td>
                                    <td><a href="{{url('setting/institute-view', $institute->id) }}">{{$institute->institute_name}}</a></td>
                                    <td>{{$institute->institute_alias}}</td>
                                    <td>{{$institute->address1}}</td>

                                    <td>{{$institute->phone}}</td>
                                    <td>{{$institute->email}}</td>
                                    <td>{{$institute->website}}</td>
                                    <td><img src="{{asset('assets/users/images/'.$institute->logo)}}" alt="Logo" height="50" width="50"></td>
                                    <td>
                                        <a href="" id="institute_edit_{{$institute->id}}" onclick="modalLoadEdit(this.id)" class="btn btn-primary btn-xs" data-target="#globalModalEdit"  data-toggle="modal" data-placement="top" data-content="update"><i class="fa fa-edit"></i></a>
                                        <a href="{{ url('setting/delete-institute', $institute->id) }}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure to Delete?')" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        {{--{{ $data->render() }}--}}

                        </tbody>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box-->
        </section>
    </div>
    <div id="slideToTop" ><i class="fa fa-chevron-up"></i></div>
    <div class="modal"  id="globalModalEdit" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
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
    <div class="modal"  id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
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
    <!-- DataTables -->
    <script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>

    <script type = "text/javascript">
        $("#example2").DataTable();
        $('#example1').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false
        });

        function modalLoad(rowId) {
            var data = rowId.split('_'); //To get the row id
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {'X-CSRF-Token': $('meta[name=_token]').attr('content')},
                url: "{{ url('setting/view-institute') }}" + '/' + data[2],
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
            //  alert(data);
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {'X-CSRF-Token': $('meta[name=_token]').attr('content')},
                url: "{{ url('setting/edit-institute-view') }}" + '/' + data[2],
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

        jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });

        jQuery('#start_date').datepicker({"changeMonth":true,"changeYear":true,"defaultValue":null,"defaultDate":null,"dateFormat":"dd-mm-yy"});
        jQuery('#end_date').datepicker({"changeMonth":true,"changeYear":true,"defaultValue":null,"defaultDate":null,"dateFormat":"dd-mm-yy"});
    </script>
@endsection
