
@extends('layouts.master')



@section('styles')
<!-- DataTables -->
  <link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
@endsection


@section('content')
  <div class="content-wrapper">
    <section class="content-header">
       <h1>
          <i class="fa fa-th-list"></i> Manage |<small>Designation </small>
       </h1>
       <ul class="breadcrumb">
          <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
          <li><a href="#">Human Resource</a></li>
          <li><a href="#">Employee Management</a></li>
          <li class="active">Designation </li>
       </ul>
    </section>

    <section class="content">
        @if(Session::has('success'))
          <div id="w0-success-0" class="alert-success alert-auto-hide alert fade in" style="opacity: 423.642;">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <h4><i class="icon fa fa-check"></i> {{ Session::get('success') }} </h4>
          </div>
        @elseif(Session::has('alert'))
          <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <h4><i class="icon fa fa-check"></i> {{ Session::get('alert') }} </h4>
          </div>
        @elseif(Session::has('warning'))
          <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <h4><i class="icon fa fa-check"></i> {{ Session::get('warning') }} </h4>
          </div>
        @endif
        
        <div class="box box-solid">
          <div class="box-header with-border">
             <h3 class="box-title"><i class="fa fa-search"></i> View Designation List </h3>
            <a class="btn btn-primary btn-sm pull-right" href="/employee/designation/create" oncontextmenu="return false;" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-plus" aria-hidden="true"></i>
 Create Designation</a>
          </div>
          <div class="box-body table-responsive">
           <div class="box-header">
            </div>
            <div class="box-body">
              <table id="example1" class="table table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Alias</th>
                  <th>Department</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                  <?php $i = 1;?>
                  @foreach ($allDesignations as $designation)
                   <tr>
                  <td>{{$i++}}</td>
                  <td>{{$designation->name}}</td>
                       @if(!empty($designation->department()))
                           <td>{{$designation->department()->name}}</td>
                           @else
                           <td></td>
                           @endif

                  <td>{{$designation->alias}}</td>
                  <td>
                    <!-- <a href="/employee/designations/edit/{{$designation->id}}" oncontextmenu="return false;" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><button type="button" class="btn btn-default btn-sm"><i class="fa fa-pencil-square-o"></i></button></a>
                    <a href="{{ url('/employee/designations/delete/'.$designation->id) }}" onclick="return confirm('Are you sure you want to delete this item?');"><button type="button" class="btn btn-default btn-sm"><i class="fa fa-trash-o"></button></i></a> -->

                    <a href="/employee/designations/{{$designation->id}}" title="View" data-target="#globalModal" data-toggle="modal"><span class="glyphicon glyphicon-eye-open"></span></a> 
                    <a href="/employee/designations/edit/{{$designation->id}}" title="Update" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><span class="glyphicon glyphicon-pencil"></span></a>
                    <a href="{{ url('/employee/designations/delete/'.$designation->id) }}" title="Delete" data-confirm="Are you sure you want to delete this item?" data-method="get"><span class="glyphicon glyphicon-trash"></span></a>
                  </td>
                  @endforeach
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
             </div>
          </div>
       </div>
    </section>

    <div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content" id="modal-content">
                <div class="modal-body" id="modal-body">
                    <div class="loader">
                        <div class="es-spinner">
                            <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
                        </div>
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
  <!-- datatable script -->
  <script>
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
    });

    jQuery(document).ready(function () {
      jQuery('.alert-auto-hide').fadeTo(7500, 500, function () {
        $(this).slideUp('slow', function () {
          $(this).remove();
        });
      });
    });

  </script>
@endsection
