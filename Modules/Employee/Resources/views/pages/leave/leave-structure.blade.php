
@extends('layouts.master')

@section('styles')
<!-- DataTables -->
  <link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="glyphicon glyphicon-th-list"></i> Manage |<small>Leave Structure</small>        </h1>
            <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/hr/default/index">Human Resource</a></li>
                <li class="active">Leave Management</li>
                <li class="active">Leave Structure</li>
            </ul>    </section>
        <section class="content">


            <div class="box box-solid">
                <div>
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-search"></i> Leave Structure            </h3>
                        <div class="box-tools">
                            <a class="btn btn-success btn-sm" href="/employee/addleavestructure" data-target="#globalModal" data-toggle="modal"><i class="fa fa-plus-square"></i> ADD</a>            </div>
                    </div>
                </div>
                <div class="box-body table-responsive">
                    <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                        <div id="w1" class="grid-view"><div class="summary">Showing <b>1-1</b> of <b>1</b> item.</div>
                            <table class="table table-striped table-bordered"><thead>
                                <tr><th>#</th><th><a href="/hr/hr-leave-structure/index?sort=els_name" data-sort="els_name">Leave Structure</a></th><th><a href="/hr/hr-leave-structure/index?sort=els_start_date" data-sort="els_start_date">Start Date</a></th><th><a href="/hr/hr-leave-structure/index?sort=els_end_date" data-sort="els_end_date">End Date</a></th><th><a href="/hr/hr-leave-structure/index?sort=els_status" data-sort="els_status">Status</a></th><th class="action-column">&nbsp;</th></tr><tr id="w1-filters" class="filters"><td>&nbsp;</td><td><input type="text" class="form-control" name="HrLeaveStructureSearch[els_name]"></td><td><input type="text" id="hrleavestructuresearch-els_start_date" class="form-control" name="HrLeaveStructureSearch[els_start_date]">
                                    </td><td><input type="text" id="hrleavestructuresearch-els_end_date" class="form-control" name="HrLeaveStructureSearch[els_end_date]">
                                    </td><td><select class="form-control" name="HrLeaveStructureSearch[els_status]">
                                            <option value=""></option>
                                            <option value="0">Active</option>
                                            <option value="1">Inactive</option>
                                        </select></td><td>&nbsp;</td></tr>
                                </thead>
                                <tbody>
                                <tr data-key="1"><td>1</td><td>a</td><td>5/1/17</td><td>5/31/17</td><td class="text-center"><a class="toggle-column" href="/hr/hr-leave-structure/toggle?id=1" title="Inactive" data-method="post" data-pjax="0"><span class="glyphicon glyphicon-ok-circle fa-lg"></span></a></td><td><a href="/hr/hr-leave-structure/update?id=1" title="Update" data-target="#globalModal" data-toggle="modal"><span class="glyphicon glyphicon-pencil"></span></a> <a href="/hr/hr-leave-structure/delete?id=1" title="Delete" aria-label="Delete" data-pjax="0" data-confirm="Are you sure you want to delete this item?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a></td></tr>
                                </tbody></table>
                        </div>        </div>    </div>
            </div>
        </section>
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
