@extends('fees::layouts.master')
<!-- page content -->
@section('page-content')
<link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css"/>
<body class="layout-top-nav skin-blue-light">
<div class="wrapper">

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage  |<small>Payment method</small>        </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/academics/default/index">Fees</a></li>
                <li class="active">Payment</li>
                <li class="active">Payment Method</li>
            </ul>
        </section>
        <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
            @if(Session::has('message'))
                <p class="alert alert-success alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('message') }}</p>
            @endif
        </div>
        <section class="content">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-plus-square"></i> Create Payment Method</h3>
                </div>
                    <form id="paymentMethod_Form">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group field-payment_methodmaster-sub_master_name required">
                                        <label class="control-label" for="payment_method_name">Payment Method Name</label>
                                        <input type="text" id="method_name" class="form-control" name="method_name" maxlength="60" placeholder="Enter Payment Method Name" aria-required="true">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary btn-create">Create</button>
                            <button type="reset" class="btn btn-default btn-create">Reset</button>
                        </div>
                        <!-- /.box-footer-->
                    </form>

            </div>
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-search"></i> View Payment Method List</h3>
                </div>
                <div class="box-body table-responsive">

                    <div id="w1" class="grid-view">
                        <table id="myTable" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th><a  data-sort="sub_master_name">Method  Name</a></th>
                                <th><a  data-sort="sub_master_code">Created At</a></th>
                                <th><a  data-sort="sub_master_alias">Update At</a></th>

                                <th><a>Action</a></th>
                            </tr>
                            </thead>



                            <tbody>

                            @if(isset($methodList))
                                @php
                                    $i = 1
                                @endphp
                                @foreach($methodList as $method)
                                    <tr class="gradeX">
                                        <td>{{$i++}}</td>
                                        <td>{{$method->method_name}}</td>
                                        <td>{{$method->created_at}}</td>
                                        <td>{{$method->updated_at}}</td>

                                        <td>
                                            <a href="" class="btn btn-primary btn-xs" data-placement="top" data-content="update"><i class="fa fa-edit"></i></a>
                                            <a href="" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure to Delete?')" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            {{--{{ $data->render() }}--}}

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->

            <!-- /.box-->
        </section>
    </div>

</div>
{{----}}
@endsection

@section('page-script')
        $(document).ready(function(){

            $('#myTable').DataTable();
        });


        // request for payers fees payer id and fees id
        $('form#paymentMethod_Form').on('submit', function (e) {
        e.preventDefault();
        alert(100);

        // ajax request
        $.ajax({

            url: '/fees/payment/method/store',
            type: 'POST',
            cache: false,
            data: $('form#paymentMethod_Form').serialize(),
            datatype: 'json/application',

            beforeSend: function() {
            // alert($('form#paymentMethod_Form').serialize());
            },

            success:function(data){
            alert("Method Added Success Fully");
            },

            error:function(data){
            alert('error');
            }
        });


        });


@endsection

</body>

</html>
