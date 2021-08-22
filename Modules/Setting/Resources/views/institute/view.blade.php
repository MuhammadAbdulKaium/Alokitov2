@extends('setting::layouts.master')

@section('section-title')

    <h1>
        <i class="fa fa-eye"></i> View Institute  Details </h1>
    <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
        <li><a href="/default/index">Configuration</a></li>
        <li class="active">View Institute</li>
    </ul>
@endsection

@section('page-content')
    <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
        @if(Session::has('message'))
            <p class="alert alert-success alert-auto-hide dism " style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('message') }}</p>
        @endif
    </div>

    @foreach($institute as $value)
        <p class="text-right btn-view-group">
            <a class="btn btn-default btn-view btn-flat" href="{{url('setting')}}"><i class="fa fa-chevron-circle-left"></i> Back</a>
            <input type="button" class="btn btn-info btn-flat btn-view pull-left" style="width: 130px;" value="Add Campus" data-target="#globalModal"  data-toggle="modal" data-placement="top" data-content="add" id="add_campus_{{$value->id}}" onclick="modalLoad(this.id)"/>
        </p>

        <div class="box box-solid">
            <div class="box-body no-padding">
                <table id="w1" class="table table-striped table-bordered detail-view">

                    <tr class="odd">
                        <th class="col-sm-3">Institute Name</th>
                        <td class="col-sm-3">{{$value->institute_name}}</td>
                        <th class="col-sm-3">Alias</th>
                        <td class="col-sm-3">{{$value->institute_alias}}</td>
                    </tr>
                    <tr class="even">
                        <th colspan="" class="col-sm-3">Address Line1</th>
                        <td  class="col-sm-3" align="left">
                            {{$value->address1 }}
                        </td>

                    </tr>
                    <tr class="odd">
                        <th class="col-sm-3">Address Line 2</th>
                        <td class="col-sm-3">{{$value->address2  }}</td>
                        <th class="col-sm-3">Phone</th>
                        <td class="col-sm-3">{{$value->phone }}</td>
                    </tr>
                    <tr class="even">
                        <th class="col-sm-3">Email Id</th>
                        <td class="col-sm-3">{{$value->email }}</td>
                        <th class="col-sm-3">Website</th>
                        <td class="col-sm-3">{{$value->website }}</td>
                    </tr>

                    <tr class="odd">
                        <th class="col-sm-3">Logo</th>
                        <td class="col-sm-3">
                            <img src="{{URL::asset('assets/users/images/'.$value->logo)}}" alt="Logo" height="200" width="200">
                        </td>
                    </tr>
                    @endforeach
                </table>    </div><!-- /.box-body -->
        </div><!-- /.box-->
        </section>
        <section class="content">
            <div class="box box-solid">
                <div>
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i>View Campus List of this institute</h3>
                        <div class="box-tools">

                        </div>
                    </div>
                    <div class="box-body table-responsive">
                        <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                            <div id="w1" class="grid-view">

                                <table id="myTable" class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><a  data-sort="sub_master_name">Campus Name</a></th>
                                        <th><a  data-sort="sub_master_code">Campus Code</a></th>
                                        <th><a  data-sort="sub_master_alias">Address</a></th>

                                        <th><a  data-sort="sub_master_alias">House</a></th>
                                        <th><a  data-sort="sub_master_alias">Street</a></th>

                                        <th><a  data-sort="sub_master_alias">City</a></th>
                                        <th><a  data-sort="sub_master_alias">State</a></th>
                                        <th><a  data-sort="sub_master_alias">Country</a></th>
                                        <th><a  data-sort="sub_master_alias">Zip</a></th>
                                        <th><a  data-sort="sub_master_alias">Phone</a></th>


                                        <th><a>Action</a></th>
                                    </tr>

                                    </thead>
                                    <tbody>

                                    @if(isset($campusesOfThis))
                                        @php

                                            $i = 1
                                        @endphp
                                        @foreach($campusesOfThis as $campus)

                                            <tr class="gradeX">
                                                <td>{{$i++}}</td>
                                                <td>{{$campus->name}}</td>
                                                <td>{{$campus->campus_code}}</td>
                                                <td>{{$campus->address()?$campus->address()->address:''}}</td>
                                                <td>{{$campus->address()?$campus->address()->house:''}}</td>
                                                <td>{{$campus->address()?$campus->address()->street:''}}</td>
                                                <td>{{$campus->address()?$campus->address()->city()->name:''}}</td>
                                                <td>{{$campus->address()?$campus->address()->state()->name:''}}</td>
                                                <td>{{$campus->address()?$campus->address()->country()->name:''}}</td>
                                                <td>{{$campus->address()?$campus->address()->zip:''}}</td>
                                                <td>{{$campus->address()?$campus->address()->phone:''}}</td>
                                                <td>

                                                    <a href=""  data-target="#globalModal"  data-toggle="modal" data-placement="top" data-content="add" id="view_campus_{{$campus->id}}" onclick="modalLoadCampusView(this.id)"><i class="fa fa-eye"></i></a>
                                                    <a href=""  data-target="#globalModal"  data-toggle="modal" data-placement="top" data-content="add" id="campus_edit_{{$campus->id}}" onclick="modalLoadCampusEdit(this.id)"><i class="fa fa-edit"></i></a>

                                                    <a href="{{ url('setting/delete-campus', $campus->id) }}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure to Delete?')" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
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
                @endsection

                @section('page-script')

                    {{--$('#myTable').DataTable();--}}

                    jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
                    $(this).slideUp('slow', function() {
                    $(this).remove();
                    });
                    });

        @endsection

                <script type="application/javascript">
                    function  modalLoadCampusView(rowId) {
                        // alert(rowId);

                        var data = rowId.split('_'); //To get the row id

                        //  alert(data);
                        $_token = "{{ csrf_token() }}";
                        $.ajax({
                            headers: {'X-CSRF-Token': $('meta[name=_token]').attr('content')},
                            url: "{{ url('setting/campus-view') }}" + '/' + data[2],
                            type: 'GET',
                            cache: false,
                            data: {'_token': $_token}, //see the $_token
                            datatype: 'html',

                            beforeSend: function () {
                            },

                            success: function (data) {

                                $('.modal-content').html(data);

                            }
                        });

                    }

                    function modalLoadCampusEdit(rowId) {
                        // alert(rowId);

                        var data = rowId.split('_'); //To get the row id

                        //  alert(data);
                        $_token = "{{ csrf_token() }}";
                        $.ajax({
                            headers: {'X-CSRF-Token': $('meta[name=_token]').attr('content')},
                            url: "{{ url('setting/edit-campus-view') }}" + '/' + data[2],
                            type: 'GET',
                            cache: false,
                            data: {'_token': $_token}, //see the $_token
                            datatype: 'html',

                            beforeSend: function () {
                            },
                            success: function (data) {
                                $('.modal-content').html(data);

                            }
                        });
                    }

                    function modalLoad(rowId) {
                        // alert(rowId);

                        var data = rowId.split('_'); //To get the row id

                        //  alert(data);
                        $_token = "{{ csrf_token() }}";
                        $.ajax({
                            headers: {'X-CSRF-Token': $('meta[name=_token]').attr('content')},
                            url: "{{ url('setting/add-campus') }}" + '/' + data[2],
                            type: 'GET',
                            cache: false,
                            data: {'_token': $_token}, //see the $_token
                            datatype: 'html',

                            beforeSend: function () {
                            },

                            success: function (data) {

                                $('.modal-content').html(data);

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

