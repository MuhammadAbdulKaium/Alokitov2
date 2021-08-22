@extends('fees::layouts.master')
<!-- page content -->
@section('page-content')
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css"/>
  <!-- grading scale -->
  <div class="col-md-12">
    <div class="box box-solid">
      <form id="CreateItemForm" action="/fees/items/store/" method="post">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="hidden" name="item_id" @if(!empty($itemProfile)) value="{{$itemProfile->id}}" @endif>
        <div class="box-body">
          <div class="row">
            <div class="col-sm-2">
              <div class="form-group">
                <label class="control-label">Item Name</label>
                <input class="form-control" name="item_name" @if(!empty($itemProfile)) value="{{$itemProfile->item_name}}" @endif  id="item_name" type="text"  placeholder="Item Name">
                <div class="help-block"></div>
              </div>
            </div>

              <div class="col-sm-2">
                  <div class="form-group">
                      <label class="control-label">Accounting Charts</label>

                      <select name="acc_chart_id" id="acc_head"  class="form-control acc_head">
                          @if(!empty($accChartList))
                              <option value="">Select Charts</option>
                              @foreach($accChartList as $chart )
                                  @if(!empty($itemProfile->acc_chart_id))
                                      @if($chart->id==$itemProfile->acc_chart_id)
                                          <option selected value="{{$chart->id}}">{{$chart->chart_name}} </option>
                                      @endif
                                  @endif
                                  <option value="{{$chart->id}}">{{$chart->chart_name}}</option>
                              @endforeach
                          @endif
                      </select>

                      <div class="help-block"></div>
                  </div>
              </div>

              <div class="col-sm-2" style="margin-top: 22px">
                  <button type="submit" id="CreateItem"  class="btn btn-primary btn-create">Submit</button>
              </div>



            {{--<div class="col-sm-2">--}}
              {{--<div class="form-group field-feespaymenttransactionsearch-fees_pay_tran_mode">--}}
                {{--<label class="control-label" for="feespaymenttransactionsearch-fees_pay_tran_mode">Payment Status</label>--}}
                {{--<select id="payment_method" class="form-control" name="payment_method">--}}
                  {{--<option value="">Select Payment</option>--}}
                  {{--<option value="Paid">Paid</option>--}}
                  {{--<option value="Un-paid">Un-Paid</option>--}}
                {{--</select>--}}

                {{--<div class="help-block"></div>--}}
              {{--</div>--}}
            {{--</div>--}}
          {{--</div>--}}


        </div>
        </div>
      </form>
    </div>

      <div class="box box-solid">
          <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-search"></i> View Items List</h3>
          </div>
          <div class="box-body table-responsive">

              <div id="w1" class="grid-view">

                  <table id="myTable" class="table table-striped table-bordered">
                      <thead>
                      <tr>
                          <th>#ID</th>
                          <th><a  data-sort="sub_master_name">Item Name</a></th>
                          <th><a  data-sort="sub_master_alias">Created At</a></th>
                          <th><a>Action</a></th>
                      </tr>

                      </thead>
                      <tbody>

                      @if(!empty($itemList))
                          @php
                              $i = 1
                          @endphp
                          @foreach($itemList as $item)
                              <tr class="gradeX">
                                  <td>{{$item->id}}</td>
                                  <td>{{$item->item_name}}</td>
                                  <td>{{date("d-m-Y", strtotime($item->created_at))}}</td>
                                  <td>

                                      <a href="{{ url('fees/items/edit', $item->id) }}" class="btn btn-primary btn-xs" data-placement="top" data-content="update"><i class="fa fa-edit"></i></a>
                                      <a id="{{$item->id}}"  class="btn btn-danger btn-xs items_delete_class"  data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
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
  </div>


  @endsection



  @section('page-script')

      {{--<script>--}}

    {{--// item create--}}
          {{--$('#CreateItem').click(function (e) {--}}
              {{--e.preventDefault();--}}
              {{--// ajax request--}}
              {{--$.ajax({--}}

                  {{--url: '/fees/items/store',--}}
                  {{--type: 'POST',--}}
                  {{--cache: false,--}}
                  {{--data: $('form#CreateItemForm').serialize(),--}}
                  {{--datatype: 'json/application',--}}

                  {{--beforeSend: function () {--}}
                      {{--// alert($('form#class_section_form').serialize());--}}
                      {{--// show waiting dialog--}}
                      {{--waitingDialog.show('Loading...');--}}
                  {{--},--}}

                  {{--success: function (data) {--}}
                      {{--// hide waiting dialog--}}
                      {{--waitingDialog.hide();--}}
                      {{--if (data == 'insert') {--}}
                          {{--swal("Success!", "Item Successfully Added", "success");--}}
                      {{--} else if(data == 'update') {--}}
                        {{--swal("Success!", "Item Successfully Updated", "success");--}}
                     {{--}--}}

                  {{--},--}}

                  {{--error: function (data) {--}}
                      {{--alert('error');--}}
                  {{--}--}}
              {{--});--}}

          {{--});--}}





          // Items delete ajax request
          $('.items_delete_class').click(function () {
              var tr = $(this).closest('tr'),
                  del_fees_id = $(this).attr('id');

              swal({
                      title: "Are you sure?",
                      text: "You want to delete item",
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
                              url: "/fees/items/delete/" + del_fees_id,
                              type: 'GET',
                              cache: false,
                              success:function(result){
                                  if(result=='success') {
                                      tr.fadeOut(1000, function () {
                                          $(this).remove();
                                      });
                                      swal("Success!", "Fees  successfully deleted", "success");
                                  } else {
                                      swal("Waining!", "Can't delete fees", "warning");
                                  }
                              }
                          });

                      } else {
                          swal("Cancelled", "Your fees  is safe :)", "error");
                          e.preventDefault();
                      }
                  });

          });



          $(document).ready(function(){
      //alert();
      $('#myTable').DataTable();
      });



@endsection

