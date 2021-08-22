
@extends('layouts.master')

@section('styles')
<!-- DataTables -->
  <link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="glyphicon glyphicon-th-list"></i> Manage |<small>Leave Type</small>        </h1>
            <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/hr/default/index">Human Resource</a></li>
                <li class="active">Leave Management</li>
                <li class="active">Leave Type</li>
            </ul>    </section>
        <section class="content">


            <div class="box box-solid">
                <div class="extraDiv">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-search"></i> Leave Type            </h3>
                        <div class="box-tools">
                            <a class="btn btn-success btn-sm" href="/employee/addleavetype" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-plus-square"></i> ADD</a>            </div>
                    </div>
                </div>
                <div class="box-body table-responsive">
                    <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">    <div id="w1" class="grid-view"><div class="summary">Showing <b>1-1</b> of <b>1</b> item.</div>
                            <table class="table table-striped table-bordered"><thead>
                                <tr><th>#</th><th><a href="/hr/leave-type/index?sort=elt_name" data-sort="elt_name">Leave Type</a></th><th><a href="/hr/leave-type/index?sort=elt_carray_forward" data-sort="elt_carray_forward">Carray Forward</a></th><th><a href="/hr/leave-type/index?sort=elt_percentage_of_cf" data-sort="elt_percentage_of_cf">Percentage of CF</a></th><th><a href="/hr/leave-type/index?sort=elt_max_cf_amount" data-sort="elt_max_cf_amount">Maximum CF Amount</a></th><th><a href="/hr/leave-type/index?sort=elt_cf_availability_period" data-sort="elt_cf_availability_period">CF Leave Availability Period</a></th><th class="action-column">&nbsp;</th></tr><tr id="w1-filters" class="filters"><td>&nbsp;</td><td><input type="text" class="form-control" name="LeaveTypeSearch[elt_name]"></td><td><select class="form-control" name="LeaveTypeSearch[elt_carray_forward]">
                                            <option value=""></option>
                                            <option value="1">YES</option>
                                            <option value="0">NO</option>
                                        </select></td><td><select class="form-control" name="LeaveTypeSearch[elt_percentage_of_cf]">
                                            <option value=""></option>
                                            <option value="10">10%</option>
                                            <option value="20">20%</option>
                                            <option value="30">30%</option>
                                            <option value="40">40%</option>
                                            <option value="50">50%</option>
                                            <option value="60">60%</option>
                                            <option value="70">70%</option>
                                            <option value="80">80%</option>
                                            <option value="90">90%</option>
                                            <option value="100">100%</option>
                                        </select></td><td><input type="text" class="form-control" name="LeaveTypeSearch[elt_max_cf_amount]"></td><td><select class="form-control" name="LeaveTypeSearch[elt_cf_availability_period]">
                                            <option value=""></option>
                                            <option value="1">1 Month</option>
                                            <option value="3">3 Month</option>
                                            <option value="6">6 Month</option>
                                            <option value="12">1 Year</option>
                                        </select></td><td>&nbsp;</td></tr>
                                </thead>
                                <tbody>
                                <tr data-key="1"><td>1</td><td>Annual</td><td><span class="label label-success">Yes</span></td><td>100%</td><td>2313123</td><td>1 Month</td><td><a href="/hr/leave-type/view?id=1" title="View" data-target="#globalModal" data-toggle="modal"><span class="glyphicon glyphicon-eye-open"></span></a> <a href="/hr/leave-type/update?id=1" title="Update" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><span class="glyphicon glyphicon-pencil"></span></a> <a href="/hr/leave-type/delete?id=1" title="Delete" aria-label="Delete" data-pjax="0" data-confirm="Are you sure you want to delete this item?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a></td></tr>
                                </tbody></table>
                        </div>    </div>    </div>
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
