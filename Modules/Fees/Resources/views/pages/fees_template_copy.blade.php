@extends('fees::layouts.addFeesMaster')
@section('styles')
    <link rel="stylesheet" type="text/css" href="https://www.jqueryscript.net/demo/jQuery-Based-Bootstrap-Popover-Enhancement-Plugin-Bootstrap-Popover-X/bootstrap-popover-x.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />

@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1><i class="fa fa-plus-square"></i>Fees and Donations</h1>
        </section>
        <section class="content">
            {{--@if(Session::has('success'))--}}
            {{--<div class="alert-success alert-auto-hide alert fade in" id="w0-success-0" style="opacity: 423.642;">--}}
            {{--<button aria-hidden="true" class="close" data-dismiss="alert" type="button">×</button>--}}
            {{--<h4><i class="icon fa fa-check"></i>{{ Session::get('success') }}</h4>--}}
            {{--</div>--}}
            {{--@elseif(Session::has('warning'))--}}
            {{--<div class="alert-warning alert-auto-hide alert fade in" id="w0-success-0" style="opacity: 423.642;">--}}
            {{--<button aria-hidden="true" class="close" data-dismiss="alert" type="button">×</button>--}}
            {{--<h4><i class="icon fa fa-check"></i>{{ Session::get('warning') }}</h4>--}}
            {{--</div>--}}
            {{--@endif--}}
            <div class="panel panel-default">
                <div class="panel-body">
                    <div>
                        <ul class="nav-tabs margin-bottom nav" id="">
                            <li @if($page == "feeslist") class="active" @endif  id="#">
                                <a href="{{url('/fees/feeslist')}}">Fees/Donation List</a>
                            </li>
                            <li @if($page == "feesmanage") class="active" @endif  id="#">
                                <a href="{{url('/fees/feesmanage')}}">Manage Fees</a>
                            </li>
                            <li @if($page == "invoice") class="active" @endif  id="#">
                                <a href="{{url('/fees/invoice')}}">Invoice</a>
                            </li>
                            <li @if($page == "paymenttransaction") class="active" @endif  id="#">
                                <a href="{{url('/fees/paymenttransaction')}}">Payment Transaction</a>
                            </li>
                            <li @if($page == "addfees") class="active" @endif  id="#">
                                <a href="{{url('/fees/addfees')}}">Add Fees</a>
                            </li>
                            <li @if($page == "feestemplate") class="active" @endif  id="#">
                                <a href="{{url('/fees/feestemplate')}}">Fees Template</a>
                            </li>
                            <li @if($page == "feetype") class="active" @endif  id="#">
                                <a href="{{url('/fees/feetype')}}">Fees Type</a>
                            </li>

                            <li @if($page == "items") class="active" @endif  id="#">
                                <a href="{{url('/fees/items')}}">Items</a>
                            </li>

                            <li @if($page == "advance_payment") class="active" @endif  id="#">
                                <a href="{{url('/fees/advance_payment')}}">Advance Payment</a>
                            </li>
                            <li @if($page == "attendance_fine") class="active" @endif  id="#">
                                <a href="{{url('/fees/attendance_fine')}}">Attendance Fine </a>
                            </li>
                            <li @if($page == "attendance_fine_generate") class="active" @endif  id="#">
                                <a href="{{url('/fees/attendance_fine_generate')}}">Attendance Geneate </a>
                            </li>

                        </ul>
                        <!-- page content div -->


                        <style>

                            {{--month and year section--}}
                            /*.month-year-section {*/
                                /*display: none;*/
                            /*}*/

                            td.td_name { border: 1px solid #efefef;}
                            td.td_rate { border: 1px solid #efefef;}
                            td.td_qty { border: 1px solid #efefef;}
                            td.td_total { border: 1px solid #efefef; text-align: right}
                            td.td-sub-total { border: 1px solid #efefef; text-align: right;}
                            td.td-sub-total-count { border: 1px solid #efefef; text-align:right; }
                            td.td-discount { border: 1px solid #efefef; text-align:right; }
                            td.td-discount-count { border: 1px solid #efefef; text-align:right; }
                            td.td-grand-total { border: 1px solid #efefef; text-align: right;}
                            td.td-grand-total-count { border: 1px solid #efefef; text-align:right ;}
                            td.td_name .itemValidateColor { border:  1px solid red;}

                            .line-item-desc-block {
                                color: red;
                                margin: 1px;
                            }

                            .td_delete_icon {
                                border-top: 0;
                                font-size: 25px;
                                text-align: center;
                                color: red !important;
                                cursor: pointer;
                            }

                            td.td_qty input,td.td_rate input,td.td_total input, td.td-sub-total-count input,td.td-discount-count input, td.td-grand-total-count input {
                                text-align: right;
                            }

                            .td_delete_icon {
                                border: 1px solid #efefef;
                            }

                            .ui-menu .ui-menu-item {
                                border: 1px solid #efefef;
                            }
                            .DisableDiv {
                            pointer-events:none;
                            background:#efefef;
                            opacity: 0.4;
                            }

                            .add_fees_template_active {
                                background: orange;
                                color:#FFF;
                            }

                            .fees_template_button{
                                margin: 20px;
                            }
                            .delete-icon-remove {
                                display: none;
                            }
                            .removeAddItem {
                                display: none;
                            }

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
                            <div class="row">
                                <div class="col-md-9">
                                    <form action="/fees/template/manage" method="post"  id="fees_and_fees_item_form">
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        <input id="fees_status" type="hidden" @if(!empty($fee->fee_status)) value="0" @else value="0" @endif  name="fee_status">
                                        <input id="is_fees_discount" type="hidden" @if(!empty($fee) && !empty($fee->discount())) value="{{$fee->discount()->id}}"  name="discount_id" @endif>
                                        <input id="custom-discount-text-update" type="hidden" @if(!empty($fee) && !empty($fee->discount())) value="{{$fee->discount()->discount_percent}}" @endif>
                                        <input id="custom-discount-label-update" type="hidden" @if(!empty($fee) && !empty($fee->discount())) value="{{$fee->discount()->discount_name}}" @endif>

                                        <div class="col-md-5">
                                            <div class="form-group required ">
                                                <label class="control-label" for="feestd_report_card_search_form_name">Fees Name</label>
                                                <input id="first_name" class="form-control" @if(!empty($fee->fee_name)) value="{{$fee->fee_name}}" @endif name="fee_name" value="" aria-required="true" type="text">
                                                <div class="help-block">
                                                    @if ($errors->has('fee_name'))
                                                        <strong>{{ $errors->first('fee_name') }}</strong>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group required">
                                                <label class="control-label" for="fee_type">Fees Type</label>
                                                <select id="feestypename" class="fees_type_name form-control" required name="fees_type_name" aria-required="true">
                                                    <option value="">--- Select Fee Type ---</option>
                                                    @if(!empty($feeTypes))
                                                        @foreach($feeTypes as $feeType )
                                                            <option value="{{$feeType->id}}" @if(!empty($fee->id)) {{ ($fee->fee_type == $feeType->id ? "selected":"") }} @endif>{{$feeType->fee_type_name}}</option>
                                                        @endforeach;
                                                    @endif
                                                </select>

                                                {{--<input id="first_name" class="form-control" name="fee_type" @if(!empty($fee->fee_type)) value="{{$fee->fee_type}}" @endif  aria-required="true" type="text">--}}
                                                {{--<div class="help-block">--}}
                                            </div>

                                            <div class="form-group  ">
                                                <label class="control-label" for="description">Description</label>
                                                <textarea name="description" class="form-control">@if(!empty($fee->description)) {{$fee->description}} @endif</textarea>
                                                <div class="help-block">
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-md-5">
                                            <div class="form-group required">
                                                <label class="control-label require" for="enrolled_at">Due Date </label>
                                                <input type="datetime"  id="dueDate" class="form-control" @if(!empty($fee->due_date)) value="{{date("d-m-Y", strtotime($fee->due_date))}}" @endif name="due_date" required>

                                                <div class="help-block">
                                                </div>
                                            </div>
{{--                                            @if(!empty($fee->month))--}}
                                         <div class="month-year-section">
                                                <div class="form-group required">
                                                    <label class="control-label" for="month">Select Month </label>
                                                    <select id="selectMonth" class="form-control"  name="month" aria-required="true">
                                                        <option value="">--- Select Month ---</option>
                                                        @for($i = 1; $i <= 12;$i++)
                                                            @php  $date_str =  date("F", strtotime("$i/12/10")); @endphp
                                                            <option @if($i==$fee->month) selected @endif value="{{$i}}">{{$date_str}}</option>
                                                        @endfor
                                                    </select>
                                                </div>

                                                <div class="form-group required">
                                                    <label class="control-label" for="month">Select Year </label>
                                                    <select id="selectMonth" class="form-control" name="year" aria-required="true">
                                                        <option value="">--- Select Year ---</option>
                                                        @for($i = 2010; $i <= 2030;$i++)
                                                            <option @if($i==$fee->year) selected @endif  value="{{$i}}">{{$i}}</option>
                                                        @endfor
                                                    </select>
                                                </div>

                                         </div>
                                            {{--@endif--}}



                                            <div class="form-group" id="GetFeesId">
                                                    <input type="hidden" type="text" id="get_fees_id" class="form-control"
                                                           name="fees_id"  value="0"  readonly>
                                            </div>
                                        </div>

                                        {{--<div class="col-md-2">--}}
                                        {{--<div class="form-group ">--}}
                                        {{--<label class="control-label" for="enrolled_at">Total Amount</label>--}}
                                        {{--<br><h4>0.00 Usd</h4>--}}
                                        {{--<div class="help-block">--}}
                                        {{--</div>--}}
                                        {{--</div>--}}
                                        {{--</div>--}}

                                        <div class="row fee-line-item-block" style="padding: 15px;">
                                            <table class="col-md-12 col-sm-12 col-xs-12">
                                                <table class="table line-item-tbl" style="margin-top: 30px;">
                                                    <thead style="background: #48b04f; color: #fff">
                                                    <tr>
                                                        <th align="left" width="50%" style="border-right: 1px solid #fff">Item Name</th>
                                                        <th  align="right" width="15%" style="border-right: 1px solid #fff">Rate</th>
                                                        <th  align="right" width="15%" style="border-right: 1px solid #fff">Qty</th>
                                                        <th align="right" width="15%" style="border-right: 1px solid #fff" >Total</th>
                                                        <th align="right" width="5%" >Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="table_rows">
                                                    <tbody class="table" id="multipulRow">
                                                    <input type="hidden" name="fees_item_counter" value="@if(empty($fee)) 1 @else  {{$fee->feesItems()->count()}} @endif" id="fees_item_counter">
                                                    <input type="hidden" name="fees_delete_counter" value="0" id="fees_delete_counter">
                                                    @if(!empty($fee))
                                                        @php $subTotalAmount=0; @endphp
                                                        @foreach($fee->feesItems() as $key=>$item)
                                                            @php $subTotalAmount+=$item->rate*$item->qty @endphp
                                                            <tr class="line-item-block item" id="row_{{$key+1}}">

                                                                <td class="td_item_id" style="display: none"><input type="hidden"  id="row_{{$key+1}}_item" name="{{$key+1}}[item_id]" value="{{$item->id}}"></td>
                                                                <td class="td_item_tbl_id" style="display: none"><input type="hidden" id="row_{{$key+1}}_name_item_id" name="{{$key+1}}[item_tbl_id]" value="{{$item->item_id}}"></td>
                                                                <td class="td_name" style="border-bottom: 1px solid #ddd; "><div class="line-item-name-block">
                                                                @if(!empty($allItems))
                                                                    <select  data-show-subtext="true" data-live-search="true" class="selectpicker form-control searchItem"  name="{{$key+1}}[item_name]" id="row_{{$key+1}}_name" required>
                                                                        <option value="">Select Item</option>
                                                                        @foreach($allItems as $items)
                                                                            <option @if($items->id==$item->item_id) ll selected  @endif id="{{$items->id}}" value="{{$items->item_name}}">{{$items->item_name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    @endif
                                                                <td class="td_rate" style="border-bottom: 1px solid #ddd; text-align: right;"><div class="line-item-block"><input type="text" class=" form-control keydown-input-field" id="row_{{$key+1}}_rate" @if(!empty($item->rate)) value="{{$item->rate}}" @endif onkeyup="javascript:calculate_price_qty({{$key+1}})"  name="{{$key+1}}[rate]"></div></td>
                                                                <td class="td_qty" style="border-bottom: 1px solid #ddd; text-align: right;"><div class="line-item-block"><input type="text" class=" form-control keydown-input-field" id="row_{{$key+1}}_qty" @if(!empty($item->qty)) value="{{$item->qty}}" @endif  onkeyup="javascript:calculate_price_qty({{$key+1}})" class="qty" name="{{$key+1}}[qty]"></div></td>
                                                                <td class="td_total" style="border-bottom: 1px solid #ddd; text-align: right;"><div class="line-item-rate-block"><input type="text" class="total form-control keydown-input-field" value="{{$item->rate*$item->qty}}" id="row_{{$key+1}}_total" readonly></div></td>
                                                                @if($key+1 !=1)<td class="td_delete_icon"  style="border-top: 0;"><i class="fa fa-trash-o btn_remove delete_item" value="{{$item->id}}" type="button" name="remove" id="{{$key+1}}"></i></td>@endif
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr class="line-item-block item" id="row_1">
                                                            <td class="td_item_id" style="display: none"><input type="hidden" id="row_1_item" name="1[item_id]" value="0"></td>
                                                            <td class="td_item_tbl_id" style="display: none"><input type="hidden" id="row_1_name_item_id" name="1[item_tbl_id]" value="0"></td>
                                                            <td class="td_name" style="border-bottom: 1px solid #ddd; "><div class="line-item-name-block"><input type="text" id="row_1_name"  class="form-control searchItem" name="1[item_name]" required></div><div id="row_1_name_error" class="line-item-desc-block"></div></td>
                                                            <td class="td_rate" style="border-bottom: 1px solid #ddd; text-align: right;"><div class="line-item-block"><input type="text" class=" form-control keydown-input-field" id="row_1_rate"onkeyup="javascript:calculate_price_qty(1)" required  name="1[rate]"></div></td>
                                                            <td class="td_qty" style="border-bottom: 1px solid #ddd; text-align: right;"><div class="line-item-block"><input type="text" class=" form-control keydown-input-field" id="row_1_qty"  onkeyup="javascript:calculate_price_qty(1)" required class="qty" name="1[qty]"></div></td>
                                                            <td class="td_total" style="border-bottom: 1px solid #ddd; text-align: right;"><div class="line-item-rate-block"><input type="text" class="total form-control keydown-input-field" id="row_1_total" value="0" readonly></div></td>
                                                            {{--<td class="td_total" style="border-bottom: 1px solid #ddd; text-align: right;"><div class="line-item-rate-block"><p class="total" id="row_1_total">0</p></div></td>--}}
                                                            <td class="td_delete_icon" id="delete-icon" style="border-top: 0;"><i class="fa fa-trash-o" id="delete-icon-message" type="button"></i></td>
                                                        </tr>
                                                    @endif


                                                    </tbody>
                                                    <tfoot class="table">
                                                    {{--Sub total--}}
                                                    <tr>
                                                        <td colspan="3" class="td-sub-total" style="border-bottom: 1px solid #ddd; font-size: 16px;><div class="line-item-name-block"><b>Sub Total</b>:</td>
                                                        <td class="td-sub-total-count" style="border-bottom: 1px solid #ddd; text-align: right;"><div class="line-item-block"><input type="text" class="form-control subTotalAmount" id="subTotal" value="{{$subTotalAmount}}" readonly></div></td>

                                                    </tr>




                @if($discount =$fee->discount())
             @php $discountPercent=$discount->discount_percent;
                 $totalDiscount=(($subTotalAmount*$discountPercent)/100);
                                                    $totalAmount=$subTotalAmount-$totalDiscount
                                                        @endphp
                                                    @else
                                                        @php
                                                            $discountPercent=0;
                                                            $totalDiscount=0;    $totalAmount=$subTotalAmount;
                                                        @endphp

                                                    @endif



                                                    {{--total Disocunt--}}
                                                    <tr>
                                                        <td  colspan="3" class="td-discount"><a data-toggle="popover-x"
                                                                                                data-target="#myPopover">
                                                                Add Discount <span class="discountParcent">{{$discountPercent}}%</span>
                                                             </a></td>
                                                        <td class="td-discount-count" style="border-bottom: 1px solid #ddd; text-align: right;"><div class="line-item-block"><input type="text" class="form-control fee-discount-total keydown-input-field" value="{{$totalDiscount}}" readonly></div></td>

                                                    </tr>
                                                    {{--total--}}

                                                    <tr>
                                                        <td colspan="3" class="td-grand-total" style="border-bottom: 1px solid #ddd; font-size: 16px;><div class="line-item-name-block"><b>Total</b>:</td>
                                                        <td class="td-grand-total-count" style="border-bottom: 1px solid #ddd; text-align: right;"><div class="line-item-block"><input type="text" class="fee-paid-total form-control keydown-input-field" value="{{$totalAmount}}" readonly></div></td>

                                                    </tr>





                                                    </tfoot>

                                                </table>
                                                <button type="button" class="add-item btn btn-success"><i class="fa fa-plus"></i> Add Item</button>


                                        </div>


                                        <div class="clearfix"></div>
                                        <div class="box-footer">

                                            <button type="submit" class="btn btn-primary btn-create" >Create</button>

                                            <a class="btn btn-default btn-create" href="">Cancel</a>
                                        </div><!-- /.box-footer-->
                                </div>

                                <div class="col-md-3" style="border: 1px solid #efefef; padding-top:0px; padding-bottom: 10px">
                                    <div class="fee-setting-title-block">
                                        <input type="hidden" id="payerStudentSms" name="auto_sms" value="0">
                                        <div id="class_section_form">
                                            <input type="hidden" id="class_section_count" name="cs_count" value="{{count($feesClassSectionList)}}">

                                            @if(!empty($feesClassSectionList))
                                                @for($i=0; $i<count($feesClassSectionList); $i++)
                                                <input type="hidden" id="item_{{$feesClassSectionList[$i]['id']}}_batch" name="batch[]" value="{{$feesClassSectionList[$i]['batch_id']}}">
                                                <input type="hidden" id="item_{{$feesClassSectionList[$i]['id']}}_section" name="section[]" value="{{$feesClassSectionList[$i]['section_id']}}">
                                               @endfor
                                               @endif

                                            <h3>Fees Class Section</h3>
                                            <input id="my-text-input">
                                        </div>

                                    </div>

                                    </form>
                                </div>

                            </div>

                        </div>


                        <div id="myPopover" class="popover popover-default">
                            <div class="arrow"></div>
                            <h3 class="popover-title">
                                <span class="close pull-right" data-dismiss="popover-x">&times;</span>
                                Add Discount
                            </h3>
                            <div class="popover-content">
                                <div class="form-group margin-none" style="margin-bottom: 15px ! important;">
                                    <input class="form-control cust-discount-label" id="custom-discount-label" placeholder="Discount Label"  type="text"   @if(!empty($fee) && !empty($fee->discount())) value="{{$fee->discount()->discount_name}}" @endif>
                                </div>
                                <div class="form-group margin-none" style="margin-bottom: 15px ! important;">
                                    <input class="form-control cust-discount" id="custom-discount-text"  style="max-width:65px;display:inline-block;text-align:right;" placeholder="0.00" @if(!empty($fee) && !empty($fee->discount())) value="{{$fee->discount()->discount_percent}}" @endif type="text"> % of invoice subtotal
                                </div>
                                <div class="">
                                    <button type="button" class="btn btn-sm btn-primary save-discount">Add Discount</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>

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

    <script>

        function calculate_price_qty(id){
            //blur
            $("#row_"+id+"_rate").blur(function(){
                if($("#row_"+id+"_qty").val()=="") {
                    $("#row_" + id + "_qty").val(1);
                }
            });
            var rate = $("#row_"+id+"_rate").val();
            var qty = $("#row_"+id+"_qty").val();
            var total = qty * rate;

            $("#row_"+id+"_total").val(total);
            subTotal();
//           var subtotalAmount=$('.subTotalAmount').val();
//           $('.fee-paid-total').val(subtotalAmount);
        }

        function subTotal () {
            var count=$('#fees_item_counter').val();
            var amount_total=0;
            for(var i=1;i<=count;i++){
                var qty = $("#row_"+i+"_qty").val();
                var rate = $("#row_"+i+"_rate").val();
                amount_total += qty * rate;
            }

            $('.subTotalAmount').val(amount_total);


            var discount = $("#custom-discount-text").val();
            var subTotal = $("#subTotal").val();

            if (subTotal != "" && discount != "") {
                var discountTotal = ((discount * subTotal) / 100);
                $('.fee-discount-total').val(discountTotal);
                var discountLevel = $("#custom-discount-label").val();
                if (discountLevel != "") {
                    var discountParcent = +discount + "%";
                } else {
                    var discountParcent = +discount + "%";
                }
                if ($('#discount_name').length == 0) {
                    $(".discountParcent").after('<input id="discount_name" type="hidden"  name="discount_name" value="' +
                        discountLevel + '" /> <input id="discount_percent" type="hidden"  name="discount_percent" value="' +
                        discount + '" />');
                } else {
                    $('#discount_name').val(discountLevel);
                    $('#discount_percent').val(discount);

                }
                $('.discountParcent').text(discountParcent);
                var total = (subTotal - ((discount * subTotal) / 100));
            } else if (subTotal != "" && discount == "") {
                var total = subTotal;
            } else {
                var total = "";
            }
            $('.fee-paid-total').val(total);

        }

        function sorting_order(){
            // sorting order
            var count_row = 1;
            $("tr.item").each(function() {
                var row_id = "row_"+count_row;
                $(this).attr("id", row_id);
//                    alert("#"+row_id+"_item_id");
                // id
                $("#"+row_id+" .td_item_id input").attr('id', 'row_'+count_row+'_item');
                $("#"+row_id+" .td_item_id input").attr('name', count_row+'[item_id]');

                // items table_id
                $("#"+row_id+" .td_item_tbl_id input").attr('id', 'row_'+count_row+'_name_item_id');
                $("#"+row_id+" .td_item_tbl_id input").attr('name', count_row+'[item_tbl_id]');

                // name
                $("#"+row_id+" .td_name input").attr('id', 'row_'+count_row+'_name');
                $("#"+row_id+" .td_name input").attr('name', count_row+'[item_name]');
                // desc
                $("#"+row_id+" .td_desc input").attr('id', 'row_'+count_row+'_desc');
                $("#"+row_id+" .td_desc input").attr('name', count_row+'[desc]');

                // rate
                $("#"+row_id+" .td_rate input").attr('id', 'row_'+count_row+'_rate');
                $("#"+row_id+" .td_rate input").attr('name', count_row+'[rate]');
                $("#"+row_id+" .td_rate input").attr('onkeyup', 'javascript:calculate_price_qty('+count_row+')');

                // qty
                $("#"+row_id+" .td_qty input").attr('id', 'row_'+count_row+'_qty');
                $("#"+row_id+" .td_qty input").attr('name', count_row+'[qty]');
                $("#"+row_id+" .td_qty input").attr('onkeyup', 'javascript:calculate_price_qty('+count_row+')');

                // total
                $("#"+row_id+" .td_total input").attr('id', 'row_'+count_row+'_total');

                // deleteButton Icon
                $("#"+row_id+" .td_delete_icon .btn_remove").attr('id', count_row);
                // count
                count_row++;

            });
        }

    </script>


@endsection




@section('scripts')


    <script src="https://www.jqueryscript.net/demo/jQuery-Based-Bootstrap-Popover-Enhancement-Plugin-Bootstrap-Popover-X/bootstrap-popover-x.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>



    <script>

//        $('#feestypename').on('change', function() {
//            var selected_value= $(this).find(":selected").text();
//                if(selected_value=="Tuition"){
//                    $(".month-year-section").show();
//                } else {
//                    $(".month-year-section").hide();
//                }
//        });
//
//        selected_value= $("#feestypename").find(":selected").text();
//        if(selected_value=="Tuition") {
//            $(".month-year-section").show();
//        }


        $(document).ready(function () {

            $("#my-text-input").tokenInput(@php echo $data @endphp,
                {prePopulate: @php echo $data @endphp,
                 propertyToSearch: "name",
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
                {{--alert(tp_count+total_std);--}}
            },

            onDelete: function (item) {
                var cs_counter = parseInt($('#class_section_count').val())
                var item_id = item.id;
                var tp_count = parseInt($('#tp_count').text());

                var total_std = item.std_count;
                $('#tp_count').text(tp_count-total_std)
                {{--alert(tp_count-total_std);--}}

                // remove item batch section
                $('#item_'+item_id+'_batch').remove();
                $('#item_'+item_id+'_section').remove();
                $('#class_section_count').val(cs_counter-1);
            }
                });
        });




        $('#dueDate').datepicker({format: 'dd-mm-yyyy'});

        var i=1;
        $('.add-item').click(function(){
            var feesRowcounter = parseInt($('#fees_item_counter').val());
            var feesRow = feesRowcounter+1;
            $('#multipulRow').append('<tr class="line-item-block item" id="row_'+feesRow+'"><td class="td_item_id" style="display: none"><input type="hidden" id="row_'+feesRow+'_item" name="'+feesRow+'[item_id]" value="0"></td><td class="td_item_tbl_id" style="display: none"><input type="hidden" id="row_'+feesRow+'_name_item_id" name="'+feesRow+'[item_tbl_id]" value="0"></td><td class="td_name" style="border-bottom: 1px solid #ddd; "><div class="line-item-name-block">@if(!empty($allItems))<select id="row_'+feesRow+'_name" type="text"   class="selectpicker form-control searchItem"  name="'+feesRow+'[item_name]" data-show-subtext="true" data-live-search="true"  required><option value="">Select Item</option>@foreach($allItems as $items)<option id="{{$items->id}}" value="{{$items->item_name}}">{{$items->item_name}}</option>@endforeach</select>@endif</div><div id="row_'+feesRow+'_name_error" class="line-item-desc-block"></div></td><td class="td_rate" style="border-bottom: 1px solid #ddd; text-align: right;"><div class="line-item-block"><input type="text" onkeyup="javascript:calculate_price_qty('+feesRow+')" class="rate form-control keydown-input-field" id="row_'+feesRow+'_rate" name="'+feesRow+'[rate]" required></div></td><td class="td_qty" style="border-bottom: 1px solid #ddd; text-align: right;"><div class="line-item-block"><input onkeyup="javascript:calculate_price_qty('+feesRow+')" type="text" class="qty form-control keydown-input-field"  id="row_'+feesRow+'_qty" name="'+feesRow+'[qty]" required></div></td>                                        <td class="td_total" style="border-bottom: 1px solid #ddd; text-align: right;"><div class="line-item-rate-block"><input type="text" class="total form-control keydown-input-field" id="row_'+feesRow+'_total" readonly value=""></div></td><td  class="td_delete_icon" id="delete-icon" style="border-top: 0;"><i class="fa fa-trash-o btn_remove" type="button" name="remove" id="'+feesRow+'"></i></td></tr>');
            $('#fees_item_counter').val(feesRow);
            // only nemeric value input function
            only_numeric();
            search_item();
            $('.selectpicker').selectpicker('refresh');

        });



        only_numeric();
    search_item();



        // delete Item on database

        $(document).on('click', '.btn_remove', function(){
            var feesRowcounter = parseInt($('#fees_item_counter').val());

            var button_id = $(this).attr("id");

            var item_id = $('#row_'+button_id+'_item').val();

            if(item_id != 0){
                var deleteCount = parseInt($('#fees_delete_counter').val());
                var deleteList = '<input type="hidden" name="deleteList[]" value="'+item_id+'">';
                $('#multipulRow').append(deleteList);
                $('#fees_delete_counter').val(deleteCount+1);
            }
            $('#row_'+button_id).remove();
            $('#fees_item_counter').val(feesRowcounter-1);
            sorting_order();
            subTotal();

        });


        // remove muliple input field

        $("#remove-multiple-rows").click(function(){
            $('input').each(function() {

                if ($(this).val() == "")
                {
                    alert("empty field : " + $(this).attr('id'));
                }

            });
        });










        function only_numeric(){
            $('.line-item-block').on('keydown', '.keydown-input-field', function(e){-1!==$.inArray(e.keyCode,[46,8,9,27,13,110,190])||/65|67|86|88/.test(e.keyCode)&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});

        }

        // request for section list using batch and section id
        $('form#fees_and_fees_item_form').on('submit', function (e) {
            e.preventDefault();
            swal({
                    title: "Are you sure?",
                    text: "You want to create a fees",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Yes, I am sure!',
                    cancelButtonText: "No, cancel it!",
                    closeOnConfirm: false,
                    closeOnCancel: false
               }).then(function(result) {
                if (result.value) {

                  @if(!empty($feesModules) && ($feesModules->count()>0))

                  swal({
                      title: 'Do you want to send sms?',
                      input: 'checkbox',
                      inputPlaceholder: 'Yes'
                  }).then(function(result) {
                      if (result.value) {
                          $("#payerStudentSms").val(1);
                          create_fees_class_section();
                      }
                      else {
                          create_fees_class_section();
                      }
                      @else



                      @endif
                  });

                 } else {
                     swal("Cancelled", "Your fees type is safe :)", "error");
                     e.preventDefault();
                 }
                });


        });


        function create_fees_class_section(){
                var fees_id=parseInt($("#get_fees_id").val());
                // ajax request
                $.ajax({

                    url: '/fees/template/manage',
                    type: 'POST',
                    cache: false,
                    data: $('form#fees_and_fees_item_form').serialize(),
                    datatype: 'json/application',

                    beforeSend: function() {
                        {{--alert($('form#fees_and_fees_item_form').serialize());--}}
                    },

                    success:function(data){

                        if(data=="success") {

                            swal("Success!", "Fees  Successfully Created", "success");
                        } else {
                            swal("Wrong!", "Something Wrong Please Try Again", "error");
                        }



                    },

                    error:function(data){
                        swal("Wrong!", "Something Wrong Please Try Again", "error");
                    }
                });
        }





        /// payers option show
        $("#payer-block-link").click(function(){

            // on click payers option show

            $( ".payers-options" ).show( "slow" );

            $('.payers_type').click(function () {
                $('#tp_count').text(0);

                $('.hidden-block').each(function () {
                    if ($(this).is(':visible')) {
                        $(this).stop().slideUp('slow');
                    }
                });
                var block_id = $(this).val();
                $('#' + block_id).stop().slideDown('slow');
            });

        });


   // add item on keypress ajax request
{{--<script>--}}
        function search_item(){
            $('.searchItem').on('change', function() {
                var my_id = $(this).attr('id');
                var id = $(this).children(":selected").attr("id");
                $('#'+my_id+'_item_id').val(id);
            });
    }




        // get student name and class from ajax auto complete

        $('#std_name').keypress(function() {
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

            /// load student and and class ajax form
            function loadFromAjax(request, response) {
                var term = $("#std_name").val();
                $.ajax({
                    url: '/student/find/student',
                    dataType: 'json',
                    data: {
                        'term': term
                    },
                    success: function(data) {
                        // you can format data here if necessary
                        response($.map(data, function(el) {
                            return {
                                label: el.name,
                                value: el.name,
                                id: el.id
                            };
                        }));
                    }
                });
            }
        });


        // request for payers fees payer id and fees id
        $('form#PayerStudent').on('submit', function (e) {
            e.preventDefault();

            @if(!empty($feesModules) && ($feesModules->count()>0))

            swal({
                title: 'Do you want to send sms?',
                input: 'checkbox',
                inputPlaceholder: 'Yes'
            }).then(function(result) {
                if (result.value) {
                        // ajax request
                        $("#payerStudentSms").val(1);
                        payerStudentStore();

                    } else {
                        payerStudentStore();
                    }
                });
            @else

             payerStudentStore();

            @endif

        });


        // payer student add ajax request funciton

        function payerStudentStore() {
            $.ajax({

                url: '/fees/payer/feesStudent',
                type: 'POST',
                cache: false,
                data: $('form#PayerStudent').serialize(),
                datatype: 'json/application',

                beforeSend: function () {
                    {{--alert($('form#PayerStudent').serialize());--}}
                },

                success: function (data) {
                    if (data == 'success') {
                        setTimeout(function () {
                            swal("Success!", "Payer Successfully Added", "success");
                        }, 2000);
                    } else if (data == 'error') {
                        setTimeout(function () {
                            swal("Waining!", "Payer Already Exists", "warning");
                        }, 2000);
                    }
                },

                error: function (data) {
                    alert(JSON.stringify(data));
                }
            });
        }



        function selectIngredient(select) {
            var value = select.val();
            var $ul = $(select).prev('ul');

            if ($ul.find('input[value=' + value + ']').length == 0)
                $ul.append('<li onclick="$(this).remove();">' +
                    '<input type="hidden" name="ingredients[]" value="' +
                    value + '" /> ' + value + '</li>');
        }

        $('select.options').on('change' , function() {
            selectIngredient($(this));
        });


        // Patials Payment Ajax Form Submit

        $('input[name=partial_allowed]').change(function(){
            $('#Partial_allowForm').submit();

        });

        $('#Partial_allowForm').submit(function(e) {
            e.preventDefault();
            // ajax request
            $.ajax({

                url: '/fees/update/partial_payment/',
                type: 'POST',
                cache: false,
                data: $('form#Partial_allowForm').serialize(),
                datatype: 'json/application',

                beforeSend: function() {
                    {{--alert($('form#Partial_allowForm').serialize());--}}
                },

                success:function(data){
                    alert('Partial Allowed Success');
                },

                error:function(data){
                    {{--alert(JSON.stringify(data));--}}
                }
            });

        });

        /// Show discount pop-up box
        $("#fee-discount-pop").click(function() {
            $(".popover").show();
        });

        /// remove discount pop-up box
        $(".popoverbtnClose").click(function() {
            $(".popover").hide();
        });

        $(".save-discount").click(function() {
            discount_calculate();
            $(".popover").hide();

        });


        // auto disocunt calculate
        function discount_calculate() {
            var discount = $("#custom-discount-text").val();
            var subTotal = $("#subTotal").val();

            if (subTotal != "" && discount != "") {
                var discountTotal = ((discount * subTotal) / 100);
                $('.fee-discount-total').val(discountTotal);
                var discountLevel = $("#custom-discount-label").val();
                if (discountLevel != "") {
                    var discountParcent = +discount + "%";
                } else {
                    var discountParcent = +discount + "%";
                }
                if ($('#discount_name').length == 0) {
                    $(".discountParcent").after('<input id="discount_name" type="hidden"  name="discount_name" value="' +
                        discountLevel + '" /> <input id="discount_percent" type="hidden"  name="discount_percent" value="' +
                        discount + '" />');
                } else {
                    $('#discount_name').val(discountLevel);
                    $('#discount_percent').val(discount);

                }
                $('.discountParcent').text(discountParcent);
                var total = (subTotal - ((discount * subTotal) / 100));
            } else if (subTotal != "" && discount == "") {
                var total = subTotal;
            } else {
                var total = "";
            }

            $('.fee-paid-total').val(total);

        }






        $("#add_fees_template").click(function(){
            if($(this).hasClass('add_fees_template_active')){
                $("#fees_status").val(0);
                $(this).removeClass('add_fees_template_active')
                $(this).addClass('btn-primary')
            } else {
                $("#fees_status").val(1);
                $(this).removeClass('btn-primary')
                $(this).addClass('add_fees_template_active')
            }
        })

        {{--<script>--}}
        // if fees discount is here then show fees discount

        var is_fees_discount=$("#is_fees_discount").val();
        if(is_fees_discount>0){
                    {{--alert(100);--}}
            var discount = $("#custom-discount-text-update").val();
                    {{--alert(discount);--}}

            var subTotals = $("#subTotal").val();
            var discountTotal = ((discount * subTotals) / 100);
            $('.fee-discount-total').val(discountTotal);
            var discountLevel = $("#custom-discount-label-update").val();
            {{--alert(discountLevel);--}}
            {{--$(".discountParcent").html('<input id="discount_name" type="hidden"  name="discount_name" value="' +
                                discountLevel + '" /> <input id="discount_percent" type="hidden"  name="discount_percent" value="' +
                                discount + '" />');--}}
$("#custom-discount-label").attr("name","discount_name");
            $("#custom-discount-label").attr("value",discountLevel);

            $("#custom-discount-text").attr("name","discount_percent");
            $("#custom-discount-text").attr("value",discount);



            $('#discount_name').val(discountLevel);
            $('#discount_percent').val(discount);

            var total = (subTotals - ((discount * subTotals) / 100));
            {{--alert(total);--}}

            $('.fee-paid-total').val(total);
        }


    $("#delete-icon-message").click(function(){
        alert("there only one raw you can't delete");
    });



</script>

@endsection



