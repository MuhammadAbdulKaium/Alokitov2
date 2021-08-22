@extends('layouts.master')
<link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css"/>
<body class="layout-top-nav skin-blue-light">
<div class="wrapper">
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage |<small>Institute</small></h1>
            <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/academics/default/index">Setting</a></li>
                <li class="active">Institute</li>
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
                        <h3 class="box-title"><i class="fa fa-search"></i>View Institute List</h3>
                        <div class="box-tools">
                            <a class="btn btn-success btn-sm" href="{{url('setting/add-institute')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">Add Institute</a></div>
                    </div>
                </div>
                <div class="box-body table-responsive">
                    <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                        <div id="w1" class="grid-view">

                            <table id="myTable" class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th><a  data-sort="sub_master_name">Institute Name</a></th>
                                    <th><a  data-sort="sub_master_code">Institute Alias</a></th>
                                    <th><a  data-sort="sub_master_alias">Address Line 1</a></th>

                                    <th><a  data-sort="sub_master_alias">Phone</a></th>
                                    <th><a  data-sort="sub_master_alias">Email</a></th>

                                    <th><a  data-sort="sub_master_alias">Website</a></th>
                                    <th><a  data-sort="sub_master_alias">Logo</a></th>


                                    <th><a>Action</a></th>
                                </tr>

                                </thead>
                                <tbody>

                                @if(isset($data))
                                    @php

                                        $i = 1
                                    @endphp
                                    @foreach($data as $values)

                                        <tr class="gradeX">
                                            <td>{{$i++}}</td>
                                            <td>{{$values->institute_name}}</td>
                                            <td>{{$values->institute_alias}}</td>
                                            <td>{{$values->address1}}</td>

                                            <td>{{$values->phone}}</td>
                                            <td>{{$values->email}}</td>
                                            <td>{{$values->website}}</td>
                                            <td><img src="{{URL::asset('assets/users/images/'.$values->logo)}}" alt="Logo" height="60" width="80"></td>

                                            <td>
                                                {{--<a href="{{ route('--}}
                                                {{---currency', $values->id) }}" class="btn btn-info btn-xs" data-toggle="modal" data-target="#etsbModal" data-placement="top" data-content="view"><i class="fa fa-eye"></i></a>--}}
                                                <a href="{{ url('setting/institute-view', $values->id) }}" class="btn btn-primary btn-xs"   data-placement="top" data-content="view"><i class="fa fa-eye"></i></a>
                                                <a href="" id="institute_edit_{{$values->id}}" onclick="modalLoadEdit(this.id)" class="btn btn-primary btn-xs" data-target="#globalModalEdit"  data-toggle="modal" data-placement="top" data-content="update"><i class="fa fa-edit"></i></a>
                                                <a href="{{ url('setting/delete-institute', $values->id) }}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure to Delete?')" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                {{--{{ $data->render() }}--}}

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div><!-- /.box-body -->
            </div><!-- /.box-->
        </section>
    </div>
    <div id="slideToTop" ><i class="fa fa-chevron-up"></i></div>
</div>
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
</body>



    <script type = "text/javascript">


        function modalLoad(rowId) {

            var data = rowId.split('_'); //To get the row id

            //   alert(data);
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

        jQuery('#start_date').datepicker({"changeMonth":true,"changeYear":true,"defaultValue":null,"defaultDate":null,"dateFormat":"dd-mm-yy"});
        jQuery('#end_date').datepicker({"changeMonth":true,"changeYear":true,"defaultValue":null,"defaultDate":null,"dateFormat":"dd-mm-yy"});




    });
</script>

</body>
</html>
    