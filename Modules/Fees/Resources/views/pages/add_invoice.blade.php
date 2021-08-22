@extends('fees::layouts.master')
<!-- page content -->
@section('page-content')

    <style>

        /* Example tokeninput style #1: Token vertical list*/
        ul.token-input-list {
            overflow: hidden;
            height: auto !important;
            height: 1%;
            width: 240px;
            border: 1px solid #999;
            cursor: text;
            font-size: 12px;
            font-family: Verdana;
            z-index: 999;
            margin: 0;
            padding: 0;
            background-color: #fff;
            list-style-type: none;
            clear: left;
        }

        ul.token-input-list li {
            list-style-type: none;
        }

        ul.token-input-list li input {
            border: 0;
            width: 350px;
            padding: 3px 8px;
            background-color: white;
            -webkit-appearance: caret;
        }

        li.token-input-token {
            overflow: hidden;
            height: auto !important;
            height: 1%;
            margin: 3px;
            padding: 3px 5px;
            background-color: #d0efa0;
            color: #000;
            font-weight: bold;
            cursor: default;
            display: block;
        }

        li.token-input-token p {
            float: left;
            padding: 0;
            margin: 0;
        }

        li.token-input-token span {
            float: right;
            color: #777;
            cursor: pointer;
        }

        li.token-input-selected-token {
            background-color: #08844e;
            color: #fff;
        }

        li.token-input-selected-token span {
            color: #bbb;
        }

        div.token-input-dropdown {
            position: absolute;
            width: 400px;
            background-color: #fff;
            overflow: hidden;
            border-left: 1px solid #ccc;
            border-right: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            cursor: default;
            font-size: 12px;
            font-family: Verdana;
            z-index: 1;
        }

        div.token-input-dropdown p {
            margin: 0;
            padding: 5px;
            font-weight: bold;
            color: #777;
        }

        div.token-input-dropdown ul {
            margin: 0;
            padding: 0;
        }

        div.token-input-dropdown ul li {
            background-color: #fff;
            padding: 3px;
            list-style-type: none;
        }

        div.token-input-dropdown ul li.token-input-dropdown-item {
            background-color: #fafafa;
        }

        div.token-input-dropdown ul li.token-input-dropdown-item2 {
            background-color: #fff;
        }

        div.token-input-dropdown ul li em {
            font-weight: bold;
            font-style: normal;
        }

        div.token-input-dropdown ul li.token-input-selected-dropdown-item {
            background-color: #d0efa0;
        }
    </style>

    <!-- grading scale -->
    <div class="col-md-12">
            <div class="col-md-5">
                <div class="form-group ">
                    <label class="control-label" for="feestd_report_card_search_form_name">Fees Name:</label>
                    <span>  @if(!empty($fee->fee_name ))  {{$fee->fee_name}} @else No @endif</span>


                </div>

                <div class="form-group ">
                    <label class="control-label" for="fee_type">Fee Type:</label>
                    <span>   @if(!empty($fee->fee_type ))  {{$fee->fees_type()->fee_type_name}} @else No @endif</span>

                </div>
                <div class="form-group ">
                    <label class="control-label" for="enrolled_at">Total Amount:</label>
                    @php $totalAmount=0; @endphp
                    @foreach($fee->feesItems() as $amount)
                        @php $totalAmount += $amount->rate*$amount->qty;@endphp

                    @endforeach
                    {{$totalAmount}}
                </div>

                <div class="form-group ">
                    <label class="control-label" for="enrolled_at">Due Date:</label>
                    <span>   @if(!empty($fee->due_date)) @php $due_date=date('d-m-Y',strtotime($fee->due_data)) @endphp {{$fee->due_date}} @endif</span>

                </div>

                {{--<div class="form-group ">--}}
                    {{--<label class="control-label" for="enrolled_at">Partial Payment:</label>--}}
                    {{--<span>   @if(!empty($fee->partial_allowed && $fee->partial_allowed=='1'))  Yes @else No @endif</span>--}}

                {{--</div>--}}

                {{--<div id="PayersFeeSection">--}}
                    {{--<div class="fee-setting-block" style="padding:15px;">--}}
                        {{--<div class="payer-block" style="border-top: 1px solid #dfdfdf;">--}}
                            {{--<div class="clearfix"></div>--}}
                            {{--<div class="payers-options" style="margin: 10px 0px;">--}}
                                {{--<div class="inline-block"><input class="payers_type" name="data[Fees][payers_type]" id="payers_type_D" value="class" type="radio"> <label for="payers_type_D">Class - Section&nbsp;&nbsp; &nbsp; </label></div>--}}
                                {{--<div class="inline-block"><input class="payers_type" name="data[Fees][payers_type]" id="payers_type_L" value="subject" type="radio"> <label for="payers_type_L">Subjects&nbsp;&nbsp; &nbsp; </label></div>--}}
                                {{--<div class="inline-block"><input class="payers_type" name="data[Fees][payers_type]" id="payers_type_S" value="select-setudent" type="radio"> <label for="payers_type_S">Student</label></div>--}}
                                {{--<p id="payers_type_error" class="has-error help-block" style="display: none;">Please select payers</p>--}}
                                {{--<p id="payers_type_D_error" class="has-error help-block" style="display: none;">Please select atleast one division</p>--}}
                                {{--<p id="payers_type_S_error" class="has-error help-block" style="display: none;">Please select atleast Student</p>--}}
                                {{--<p id="payers_type_L_error" class="has-error help-block" style="display: none;">Please select atleast Subjects</p>--}}
                            {{--</div>--}}
                            {{--<div class="clearfix"></div>--}}
                            {{--<div class="hidden-block" id="class" style="display: none">--}}
                                {{--<form id="class_section_form">--}}
                                    {{--<input type="hidden" name="_token" value="{{csrf_token()}}">--}}
                                    {{--<input type="hidden" id="class_section_count" name="cs_count" value="0">--}}
                                    {{--<div style="padding-left: 0px; padding-right: 0px;margin-top: 10px;">--}}
                                        {{--<a href="javascript:void(0)" id="allclass" style="text-decoration: underline;"> Select All Class-Divisions </a>--}}
                                        {{--<h6 style="padding-top: 6px;">Select Class</h6>--}}
                                        {{--<input type="text" class="form-control" id="demo-input-local" name="class-seciton" />--}}
                                        {{--<input type="submit">--}}
                                        {{--<div class="clearfix"></div>--}}
                                    {{--</div>--}}
                                {{--</form>--}}
                            {{--</div>--}}
                            {{--<div class="hidden-block" id="subject" style="display: none">--}}
                                {{--<div style="padding-left: 0px; padding-right: 0px;margin-top: 10px; ">--}}
                                    {{--<h6>Select Subjects</h6>--}}
                                    {{--<input name="data[Fees][payers_subjects]" id="subject_ids" type="hidden">--}}
                                    {{--<ul class="token-input-list"><li class="token-input-input-token"><input autocomplete="off" autocapitalize="off" style="outline: medium none; width: 116px;" id="token-input-subjects_multiselect" type="text"><tester style="position: absolute; top: -9999px; left: -9999px; width: auto; font-size: 13px; font-family: &quot;Open Sans&quot;,sans-serif; font-weight: 400; letter-spacing: 0px; white-space: nowrap;"></tester></li></ul><input name="" class="col-md-12 padding-none" id="subjects_multiselect" style="display: none;">--}}
                                {{--</div>--}}
                                {{--<div class="clearfix"></div>--}}
                            {{--</div>--}}
                            {{--<div class="clearfix"></div>--}}
                        {{--</div>--}}

                        {{--<div class="hidden-block" id="select-setudent" style="display:none">--}}
                            {{--<form id="PayerStudent">--}}
                                {{--<input type="hidden" name="_token" value="{{csrf_token()}}">--}}
                                {{--<div style="padding-left: 0px; padding-right: 0px;margin-top: 10px; ">--}}
                                    {{--<h6>Select Student</h6>--}}
                                    {{--<input class="form-control" id="std_name" type="text" placeholder="Type Student Name">--}}
                                    {{--<input id="std_id" name="std_id" type="hidden" value="" />--}}
                                    {{--<input type="submit">--}}
                                {{--</div>--}}
                                {{--<div class="clearfix"></div>--}}
                            {{--</form>--}}
                        {{--</div>--}}


                    {{--</div>--}}

                  {{--</div>--}}
                </div>

            <div class="col-md-5">

                <div class="form-group ">
                    <label class="control-label" for="description">Description</label>
                    <span>{{$fee->description}}</span>
                </div>


                <div class="form-group ">
                    <label class="control-label" for="Comment">Comment</label>

                </div>


                <div class="form-group" id="GetFeesId">

                        <label class="control-label" for="enrolled_at">Fees ID:</label>
                    <span>   @if(!empty($fee->id ))  {{$fee->id}} @else No @endif</span>

                </div>

                <div class="form-group" id="payerList">

                    <label class="control-label" for="enrolled_at">Payers:</label>
                  <a  href="/fees/payers/list/{{$fee->id}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">
                        <span id="tp_count" style="font-size: 16px !important; font-weight: bold">{{$fee->payers()->count()}}</span> </a>

                </div>

            </div>
    </div>
</div>
    </div>



    @if(!empty($feeItems->count()))
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">Fees Items</div>
            <div class="panel-body">

                {{--<div class="box-body table-responsive">--}}

                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Serial NO.</th>
                            <th><a  data-sort="sub_master_alias">Item Name</a></th>
                            <th><a  data-sort="sub_master_alias">Rate</a></th>
                            <th><a  data-sort="sub_master_alias">Qty</a></th>
                            <th><a  data-sort="sub_master_alias">Total</a></th>
                        </tr>

                        </thead>
                        <tbody>


                        @php

                            $i = 1; $total=0;
                        @endphp
                        @foreach($feeItems as $items)

                            <tr class="gradeX">
                                <td>{{$i++}}</td>
                                <td>{{$items->item_name}}</td>
                                <td>{{$items->rate}}</td>
                                <td>{{$items->qty}}</td>
                                <td>{{$items->qty*$items->rate}}</td>
                          </tr>
{{--                            @php $total+=$items->qty*$items->rate; @endphp--}}
                        @endforeach
                        </tbody>

                        {{--<tfoot>--}}
                        {{--<tr class="gradeX">--}}
                            {{--<td colspan="3"></td>--}}
                            {{--<td style="float: right; background: #efefef">Grand Total</td>--}}
                            {{--<td style="font-weight: bold">{{$total}}</td>--}}
                        {{--</tr>--}}
                        {{--</tfoot>--}}
                    </table>
                {{--</div><!-- /.box-body -->--}}

            </div>
        </div>
    </div>


    @endif



    @if(!empty($feesBatchSections->count()))
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Fees Class Section</div>
                <div class="panel-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Serial NO.</th>
                            <th><a  data-sort="sub_master_alias">Class Name</a></th>
                            <th><a  data-sort="sub_master_alias">Section Name</a></th>
                            <th><a  data-sort="sub_master_alias">Division Name</a></th>
                            <th><a  data-sort="sub_master_alias">Number of Student</a></th>
                        </tr>

                        </thead>
                        <tbody>


                            @php

                                $i = 1
                            @endphp
                            @foreach($feesBatchSections as $fees_batch_section)

                                <tr class="gradeX">
                                    <td>{{$i++}}</td>
                                    {{--@php--}}
                                        {{--$std=$invoice->payer();--}}
                                       {{--$enroll=$std->singleEnroll();--}}
                                       {{--$batch=$enroll->batch();--}}
                                       {{--$section=$enroll->section();--}}
                                    {{--@endphp--}}
                                    <td>{{$fees_batch_section->batch()->batch_name}}</td>
                                    <td>{{$fees_batch_section->section()->section_name}}</td>
                                    <td>@if(!empty($fees_batch_section->batch()->division())) {{$fees_batch_section->batch()->division()->name}}  @endif</td>
                                    <td>{{getNumberOfStudentByBatchSection($fees_batch_section->batch_id,$fees_batch_section->section_id)->count()}}</td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
        </div><!-- /.box-body -->
            </div>
        </div>
    @endif


            <div class="clearfix"></div>
            <div class="box-footer">
            </div><!-- /.box-footer-->
         </div>
    </form>
    </div>
        @endsection

  @section('page-script')

      /// Token Input and get all class and section Data;
      $("#demo-input-local").tokenInput("/fees/data/test/",{
      preventDuplicates: true,
      searchDelay: 200,

      onAdd: function (item) {
      var cs_counter = parseInt($('#class_section_count').val())
      var item_id = item.id;
      var tp_count = parseInt($('#tp_count').text());
      var batch = '<input type="hidden" id="item_'+item_id+'_batch" name="batch[]" value="'+item.batch_id+'">';
      var section = '<input type="hidden" id="item_'+item_id+'_section" name="section[]" value="'+item.section_id+'">';

      $('#class_section_form').append(batch);
      $('#class_section_form').append(section);
      $('#class_section_count').val(cs_counter+1);



      var total_std = item.std_count;


      $('#tp_count').text(tp_count+total_std)
             alert(tp_count+total_std);
      },

      onDelete: function (item) {
      var cs_counter = parseInt($('#class_section_count').val())
      var item_id = item.id;
      var tp_count = parseInt($('#tp_count').text());

      var total_std = item.std_count;
      $('#tp_count').text(tp_count-total_std)
      alert(tp_count-total_std);

      // remove item batch section
      $('#item_'+item_id+'_batch').remove();
      $('#item_'+item_id+'_section').remove();
      $('#class_section_count').val(cs_counter-1);
      }
      });

      {{--// request for payers fees payer id and fees id--}}
      {{--$('form#class_section_form').on('submit', function (e) {--}}
      {{--e.preventDefault();--}}


      {{--// ajax request--}}
      {{--$.ajax({--}}

          {{--url: '/fees/data',--}}
          {{--type: 'POST',--}}
          {{--cache: false,--}}
          {{--data: $('form#class_section_form').serialize(),--}}
          {{--datatype: 'json/application',--}}

          {{--beforeSend: function() {--}}
           {{--alert($('form#PayerStudent').serialize());--}}
          {{--},--}}

          {{--success:function(data){--}}
          {{--alert("Fees added success");--}}
          {{--},--}}

          {{--error:function(data){--}}
          {{--alert('error');--}}
          {{--}--}}
      {{--});--}}


      {{--});--}}

      // on click payers option show

      $('.payers_type').click(function () {
      $('.hidden-block').each(function () {
      if ($(this).is(':visible')) {
      $(this).stop().slideUp('slow');
      }
      });
      var block_id = $(this).val();
      $('#' + block_id).stop().slideDown('slow');
      });


      $(document).ready(function(){
      $('#invoicePayerList').DataTable();
      });


  @endsection

