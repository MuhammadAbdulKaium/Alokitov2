@extends('fees::layouts.master')
<!-- page content -->
@section('page-content')
  <!-- grading scale -->
  <div class="col-md-12">
    <h4 id="advance_search">Search</h4>
    <div class="box box-solid">
      <form id="FeesSearch" action="/fees/all/search/" method="get">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        {{--<input type="hidden" name="fee_status" value="0">--}}
        <div class="box-body">
          <div class="row">
            <div class="col-sm-2">
              <div class="form-group">
                <label class="control-label">Fees  ID</label>
                <input class="form-control" name="fees_id" id="search_fees_id" type="text" value="@if(!empty($allInputs)) {{$allInputs->fees_id}} @endif" placeholder="Type Fees ID">
                <div class="help-block"></div>
              </div>
            </div>

            <div class="col-sm-2">
              <div class="form-group">
                <label class="control-label">Fees Name</label>
                <input class="form-control" id="search_fees_name" name="fees_name" type="text" value="@if(!empty($allInputs)) {{$allInputs->fees_name}} @endif" placeholder="Type Fees Name">
                <div class="help-block"></div>
              </div>
            </div>

            {{--<div class="col-sm-2">--}}
              {{--<div class="form-group">--}}
                {{--<label class="control-label">Partial Payment</label>--}}
                {{--<select id="partial_allow" class="form-control" name="partial_allow">--}}
                  {{--<option value="">Select Payment</option>--}}
                  {{--<option value="1" @if (!empty($allInputs) && ($allInputs->partial_allowed =="1")) selected="selected" @endif>Yes</option>--}}
                  {{--<option value="0" @if (!empty($allInputs) && ($allInputs->partial_allowed =="0")) selected="selected" @endif >NO</option>--}}
                {{--</select>--}}
                {{--<div class="help-block"></div>--}}
              {{--</div>--}}
            {{--</div>--}}

            <div class="col-sm-2">
              <div class="form-group ">
                <label class="control-label">Due Date</label>
                <input type="text" id="search_due_date" class="form-control" name="search_due_date" value="@if(!empty($allInputs->search_due_date)) {{date('d-m-Y',strtotime($allInputs->search_due_date))}}  @endif"  placeholder="Start Date">
                <div class="help-block"></div>
              </div>
            </div>



            <div class="col-sm-2">
              <div class="form-group field-feespaymenttransactionsearch-fp_sdate ">
                <label class="control-label" for="feespaymenttransactionsearch-fp_sdate">Start Date</label>
                <input type="text"  id="search_start_date" class="form-control"  value="@if(!empty($allInputs->search_start_date)) {{date('d-m-Y',strtotime($allInputs->search_start_date))}}  @endif" name="search_start_date" placeholder="Start Date">
                <div class="help-block"></div>
              </div>			</div>


            <div class="col-sm-2">
              <div class="form-group field-feespaymenttransactionsearch-fp_edate ">
                <label class="control-label" for="feespaymenttransactionsearch-fp_edate">End Date</label>
                <input type="text" id="search_end_date" class="form-control" name="search_end_date"   value="@if(!empty($allInputs->search_end_date)) {{date('d-m-Y',strtotime($allInputs->search_end_date))}}  @endif" placeholder="Start Date">


                <div class="help-block"></div>
              </div>
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
        <div class="box-footer">
          <button type="submit"  class="btn btn-primary btn-create">Search</button>
        </div>
      </form>

      @if(!empty($searchFees))
        @php $allFees=$allFees; @endphp
      @endif

      @if($allFees->count()>0)

    <div class="box-body table-responsive">
      <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
        <div id="w1" class="grid-view">

          <table id="feesListTable" class="table table-striped table-bordered">
            <thead>
            <tr>
              <th>#</th>
              <th><a  data-sort="sub_master_name">Name</a></th>
              <th><a  data-sort="sub_master_code">Description</a></th>
              <th><a  data-sort="sub_master_alias">Type</a></th>
              <th><a  data-sort="sub_master_alias">Amount</a></th>
              <th><a  data-sort="sub_master_alias">Discount</a></th>
              {{--<th><a  data-sort="sub_master_alias">Partial Payment</a></th>--}}
              <th><a  data-sort="sub_master_alias">Due Date</a></th>
              {{--<th><a  data-sort="sub_master_alias">Status</a></th>--}}

              <th><a>Action</a></th>
            </tr>

            </thead>
            <tbody>

              @php

                $i = 1
              @endphp
              @foreach($allFees as $fees)

                <tr class="gradeX">
                  <td>{{$fees->id}}</td>
                  <td>{{$fees->fee_name}}</td>
                  <td>{{$fees->description}}</td>
                  <td>{{$fees->fees_type()->fee_type_name}}</td>

                    @php $subtotal=0; @endphp
                      @foreach($fees->feesItems() as $amount)
                        @php $subtotal += $amount->rate*$amount->qty;@endphp

                          @endforeach
                    {{--dsicount --}}
                    @if($discount =$fees->discount())
                        @php $discountPercent=$discount->discount_percent;
                                                    $totalDiscount=(($subtotal*$discountPercent)/100);
                                                    $totalAmount=$subtotal-$totalDiscount
                        @endphp
                    @endif

                <td>{{$subtotal}}</td>
                    <td>
                        {{--@php @endphp--}}
                        @if($discount = $fees->discount())
                            @php $totalDiscount=(($subtotal*$discountPercent)/100);@endphp
                            {{$totalDiscount }}
                        @endif

                    </td>

                    {{--discount--}}
{{--                  <td> @if ($fees->partial_allowed=="0") <span class="btn-orange">NO<span> @else <span>Yes</span> @endif</td>--}}
                    <td>{{date('d-m-Y',strtotime($fees->due_date))}}</td>
                    {{--<td>{{$fees->fee_status}}</td>--}}

                    <td>
                      {{--<a href="{{ route('--}}
                      {{---currency', $fees->id) }}" class="btn btn-info btn-xs" data-toggle="modal" data-target="#etsbModal" data-placement="top" data-content="view"><i class="fa fa-eye"></i></a>--}}
                        {{--<a href="" class="btn btn-primary btn-xs" id="batch_view_{{$fees->id}}" onclick="modalLoad(this.id)" data-target="#globalModal"  data-toggle="modal" data-placement="top" data-content="view"><i class="fa fa-eye"></i></a>--}}
                        <a href="{{ url('fees/invoice/add', $fees->id) }}" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a>
                        <a class="btn btn-info btn-xs"  href="/fees/due_date/update/{{$fees->id}}"  data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-pencil"></i></a>
                        <a id="{{$fees->id}}" class="fees_delete_class btn btn-danger btn-xs" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
                    </td>
                  </tr>
                @endforeach
              {{--{{ $data->render() }}--}}

              </tbody>
            </table>
          </div>
          <div class="link" style="float: right">
              {!! $allFees->appends(Request::only([
                  'search'=>'search',
                  'filter'=>'filter',
                  'fees_name'=>'fees_name',
                  'partial_allowed'=>'partial_allow',
                  'search_due_date'=>'search_due_date',
                  'start_date'=>'search_start_date',
                  'end_date'=>'search_end_date',
                  ]))->render() !!}
          </div>
      </div>

      @else
        <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <h5><i class="fa fa-warning"></i></i> No result found. </h5>
        </div>
      @endif

    </div><!-- /.box-body -->
  @endsection



  @section('page-script')

      {{--<script>--}}

            // Fees delete ajax request
            $('.fees_delete_class').click(function () {
                var tr = $(this).closest('tr'),
                del_fees_id = $(this).attr('id');

                swal({
                        title: "Are you sure?",
                        text: "You want to delete fees",
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
                                url: "delete/" + del_fees_id,
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


            // data time script
            $('#search_start_date').datepicker({format: 'dd-mm-yyyy'});
            $('#search_due_date').datepicker({format: 'dd-mm-yyyy'});
            $('#search_end_date').datepicker({format: 'dd-mm-yyyy'});
            $('#dates').datepicker('setDate', null);
            $('#dueDate').datepicker({"changeMonth":true,"changeYear":true,"dateFormat":"dd-mm-yy"});

    //get fees name

            $('#search_fees_name').keypress(function() {
                $(this).autocomplete({
                    source: loadFromAjax,
                    minLength: 1,

                    select: function(event, ui) {
                        // Prevent value from being put in the input:
                        this.value = ui.item.label;
                        // Set the next input's value to the "value" of the item.
                        $(this).next("input").val(ui.item.id);
                        event.preventDefault();
                    }
                });

                /// load student name form
                function loadFromAjax(request, response) {
                    var term = $("#search_fees_name").val();
                    $.ajax({
                        url: '/fees/find/all_fees',
                        dataType: 'json',
                        data: {
                            'term': term
                        },
                        success: function(data) {
                            // you can format data here if necessary
                            response($.map(data, function(el) {
                                return {
                                    label: el.fee_name,
                                    value: el.fee_name,
                                    id: el.id
                                };
                            }));
                        }
                    });
                }
            });

            @if(Session::has('message'))
                toastr.success("{{ Session::get('message') }}");
             @endif




@endsection

