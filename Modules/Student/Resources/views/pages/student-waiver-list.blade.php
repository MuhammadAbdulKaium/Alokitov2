
@extends('layouts.master')

<!-- page content -->
@section('content')
	<div class="content-wrapper">
		<section class="content-header">
			<h1>
				<i class="fa fa-plus-square"></i> Cadet <small> Waiver List </small>
			</h1>
			<ul class="breadcrumb">
				<li><a href="/"><i class="fa fa-home"></i>Home</a></li>
				<li><a href="/student">Cadet</a></li>
				<li><a href="/student/manage/profile">Waiver List</a></li>
				<li class="active">Waiver List</li>
			</ul>
		</section>
		<section class="content">
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
                    @if(!empty($studentWaivers)>0)
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-search"></i> Cadet Waiver List
                        </h3>
                    </div>
                        <div class="box-body table-responsive">
                            <div id="p0" data-pjax-container="" data-pjax-push-state="" data-pjax-timeout="10000">
                                <div id="w2" class="grid-view">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Cadet Name</th>
                                            <th>Waiver Type</th>
                                            <th>Type</th>
                                            <th>Amount</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Status</th>
                                            <th class="action-column">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($studentWaivers as $studentWaiver)

                                            <tr>
                                                <td>{{$studentWaiver->id}}</td>
                                                <td>{{$studentWaiver->student()->first_name.' '.$studentWaiver->student()->middle_name.' '.$studentWaiver->student()->last_name}}</td>

                                                <td>
                                            @if($studentWaiver->waiver_type==1)
                                                    <span class="label label-info">General
                                            @elseif($studentWaiver->waiver_type==2)
                                                    <span class="label label-primary">Upbritti</span>
                                            @elseif($studentWaiver->waiver_type==3)
                                                    <span class="label label-primary">Scholarship</span>
                                                @endif

                                                </td>
                                                <td>
                                                    @if($studentWaiver->type==1)
                                                        <span class="label label-info">Percent
                                                        @else
                                                        <span class="label label-primary">Amount</span>
                                                        @endif
                                                </td>
                                                <td>
                                                    {{$studentWaiver->value}}  @if($studentWaiver->type==1) % @else TK. @endif
                                                <td>{{$studentWaiver->start_date}}</td>
                                                <td>{{$studentWaiver->end_date}}</td>
                                                <td>

                                                    @if($studentWaiver->status==1)
                                                            <span class="label label-success">Active
                                                             @else
                                                                <span class="label label-primary">Deactive</span>
                                                    @endif

                                                </td>
                                                <td>
                                                    <a href="/student/student-waiver/update-waiver/{{$studentWaiver->id}}" title="waiver" data-target="#globalModal" data-toggle="modal"><span class="glyphicon glyphicon-pencil"></span></a>
                                                    <a  id="{{$studentWaiver->id}}" class="btn btn-danger btn-xs deleteWaiver" onclick="return confirm('Are you sure to Delete?')" data-placement="top" data-content="delete"><span class="glyphicon glyphicon-trash"></span></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="paginate" style="float: right">
                                {{ $studentWaivers->links() }}
                            </div>


                            @else
                                <div class="container" style="margin-top: 20px">
                                    <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="fa fa-warning"></i></i> No result found. </h5>
                                    </div>
                                </div>

                            @endif

                        <!-- /.box-body -->

@endsection

            <!-- global modal -->
                <div aria-hidden="true" aria-labelledby="esModalLabel" class="modal" id="globalModal" role="dialog" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="loader">
                                    <div class="es-spinner">
                                        <i class="fa fa-spinner fa-pulse fa-5x fa-fw">
                                        </i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

@section('scripts')
	<script type="text/javascript">

        // waiver delete Ajax Request
        $('.deleteWaiver').click(function() {
            var tr = $(this).closest('tr');
            var waiverId= $(this).attr('id');

            // ajax request
            $.ajax({
                url: '/student/student-waiver/delete/'+waiverId,
                type: 'GET',
                cache: false,
                success:function(data){
                    tr.fadeOut(1000, function(){
                        $(this).remove();
                    });
                    toastr.info('Waiver Successfully Deleted');

                }
            });

        });


       </script>
@endsection
