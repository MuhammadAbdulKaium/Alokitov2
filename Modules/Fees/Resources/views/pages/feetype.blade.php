@extends('fees::layouts.master')
<!-- page content -->
@section('page-content')
    <!-- grading scale -->
    <div class="col-md-12">
        <div class="box box-solid">

            @if(Session::has('insert'))
                <div id="w0-success-0" class="alert-success alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('insert') }} </h4>
                </div>

            @elseif(Session::has('update'))
                <div id="w0-success-0" class="alert-success alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('update') }} </h4>
                </div>

            @elseif(Session::has('warning'))
                <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('warning') }} </h4>
                </div>
            @endif

            <div class="box-body">
                <form action="/fees/feetype/" method="post" id="fees_report">
                    <div class="row">
                        <div class="col-sm-3">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <input type="hidden" name="feetype_id" @if(!empty($feetypeProfile)) value="{{$feetypeProfile->id}}" @endif">
                            <div class="form-group">
                                <label class="control-label" for="fees_type_name">Fees Type</label>
                                <input class="form-control" type="text" name="fee_type_name" @if(!empty($feetypeProfile)) value="{{$feetypeProfile->fee_type_name}}" @endif" id="fee_type_name">
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div><!-- /row-->
                    <button type="submit"   class="btn btn-primary btn-create">Create</button>
                </form>
            </div><!-- /box-body-->

        </div>


        @if($feetypes->count()>0)
            <div class="box-body table-responsive">
                <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                    <div id="w1" class="grid-view">

                        <table id="feesListTable" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th># NO</th>
                                <th><a  data-sort="sub_master_name">Fees Type</a></th>
                                <th><a>Action</a></th>
                            </tr>

                            </thead>
                            <tbody>

                            @php

                                $i = 1
                            @endphp
                            @foreach($feetypes as $feetype)
                                <tr class="gradeX">
                                    <td>{{$i++}}</td>
                                    <td>{{$feetype->fee_type_name}}</td>
                                    <td>
                                        <a href="/fees/feetype/edit/{{$feetype->id}}"  class=" btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
                                        <a id="{{$feetype->id}}" class="feetype_delete_class btn btn-danger btn-xs"  data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            {{--{{ $data->render() }}--}}

                            </tbody>
                        </table>
                    </div>
                    <div class="link" style="float: right"> {{ $feetypes->links() }}</div>
                </div>

                @else
                    <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="fa fa-warning"></i></i> No result found. </h5>
                    </div>
                @endif

            </div><!-- /.box-body -->


    </div>

    </div><!-- /.box-body -->
@endsection

@section('page-script')

    {{--<script>--}}

    // invoice delete ajax request
    $('.feetype_delete_class').click(function(e){
    del_id = $(this).attr('id');
    var tr = $(this).closest('tr');

    swal({
    title: "Are you sure?",
    text: "You want to delete fees type",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: '#DD6B55',
    confirmButtonText: 'Yes, I am sure!',
    cancelButtonText: "No, cancel it!",
    closeOnConfirm: false,
    closeOnCancel: false
    },
    function(isConfirm){

    if (isConfirm){
    $.ajax({
    url: "/fees/feetype/delete/"+ del_id,
    type: 'GET',
    cache: false,
    success:function(result){
    if(result=='success') {
    tr.fadeOut(1000, function () {
    $(this).remove();
    });
    swal("Success!", "Fees type deleted successfully", "success");
    } else {
    swal("Waining!", "Can't delete fees type", "warning");
    }
    }
    });

    } else {
    swal("Cancelled", "Your fees type is safe :)", "error");
    e.preventDefault();
    }
    });




    //            var x = confirm("Are you sure you want to delete?");
    //            if(x) {
    //                var tr = $(this).closest('tr'),
    //                    del_id = $(this).attr('id');
    //                $.ajax({
    //                    url: "/fees/feetype/delete/"+ del_id,
    //                    type: 'GET',
    //                    cache: false,
    //                    success:function(result){
    //                        tr.fadeOut(1000, function(){
    //                            $(this).remove();
    //                        });
    //                    }
    //                });
    //             }

    });



@endsection

