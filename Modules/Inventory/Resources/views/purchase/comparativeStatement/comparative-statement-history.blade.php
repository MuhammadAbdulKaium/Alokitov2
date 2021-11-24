@extends('layouts.master')
@section('content')
<div id="app">
    <div class="content-wrapper">
        <div>
            <section class="content-header">
                <h1>
                    <i class="fa fa-plus-square"></i> Comparative Statement History
                </h1>
                <ul class="breadcrumb">
                    <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                    <li><a href="#">Inventory</a></li>
                    <li><a href="#">SOP Setup</a></li>
                    <li><a href="#">Purchase</a></li>
                    <li class="active">Comparative Statement</li>
                </ul>
            </section>

            <section class="content">                
                <div class="box box-solid">
                    <div class="box-body">
                        <div class="row scroll-table-300">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-md-4 control-label required">Voucher No</label>
                                    <div class="col-md-8 p-b-15">
                                        {{$formData->voucher_no}}
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-4 control-label required">Campus</label>
                                    <div class="col-md-8 p-b-15">
                                        {{$formData->campus_name}}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label required">Instruction of</label>
                                    <div class="col-md-8 p-b-15">
                                        {{@$formData->name}}
                                    </div>
                                </div>
                                
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-md-4 control-label required">CS Date</label>
                                    <div class="col-md-8 p-b-15">
                                        {{$formData->cs_date}}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label required">Due Date</label>
                                    <div class="col-md-8 p-b-15">
                                        {{$formData->due_date_formate}}
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="responsive table table-striped table-bordered" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th colspan="5"></th>
                                        @foreach($vendorData as $vendor)
                                            <th colspan="6" v-bind:key="vi">
                                                <span style="float:left;">Vendor Name: {{$vendor->name}}</span>
                                                 <span style="float:right;">Gl Code: {{$vendor->gl_code}}</span>
                                            </th>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Item SKU</th>
                                        <th>Qty</th>
                                        <th>UOM</th>
                                        <th>Remarks</th>
                                        @foreach($vendorData as $vendor) 
                                            <th>Rate</th>
                                            <th>Amt.</th>
                                            <th>Discount</th>
                                            <th>VAT</th>
                                            <th>VAT Type</th>
                                            <th>Net Amt.</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($formData->voucherDetailsData as $data) 
                                    <tr>
                                        <td valign="middle">{{$data->product_name}}</td>
                                        <td valign="middle">{{$data->sku}}</td>          
                                        <td class="text-right" valign="middle">{{number_format($data->qty,$data->decimal_point_place)}}</td> 
                                        <td valign="middle">{{$data->uom}}</td>
                                        <td valign="middle">{{$data->remarks}}</td>   
                                        @foreach($vendorData as $vendor_id => $vendor) 
                                            @foreach($fields as $filed)
                                                @php $accessId = $filed.'_'.$data->reference_details_id.'_'.$data->item_id.'_'.$vendor_id; @endphp  
                                                <td valign="middle">{{($filed=='vat_type')?$price_catalog_component_data[$accessId]:number_format($price_catalog_component_data[$accessId], 2)}}</td>
                                            @endforeach
                                        @endforeach
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td rowspan="6" colspan="5"></td>
                                        @foreach($vendorData as $vendor) 
                                            <td colspan="6"></td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        @foreach($vendorData as $vendor) 
                                            <td colspan="6">
                                                <span class="text-left" style="float: left;"><b>Credit Limit:</b> {{number_format($vendor->credit_limit,2)}}</span> 
                                                <span class="text-left" style="float: right;"><b>Credit Period:</b> {{$vendor->credit_priod}}</span> 
                                            </td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        @foreach($vendorData as $vendor) 
                                            <td colspan="6">
                                                <b>Terms & Coditions:</b>
                                                <ul>
                                                    @foreach($vendor->terms_condition as $terms_condition) 
                                                        <li>{{$terms_condition->term_condition}}</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                       @foreach($vendorData as $vendor) 
                                            <td colspan="6"></td>
                                       @endforeach
                                    </tr>
                                    <tr>
                                        @foreach($vendorData as $vendor) 
                                            <td colspan="6"></td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        @foreach($vendorData as $vendor_id => $vendor) 
                                            @if($vendor_id == $formData->vendor_id)
                                                <td colspan="6"> <input type="radio" id="" name="gender" checked="checked"> <label for="">Proceed with this vendor</label></td>
                                            @else
                                                <td colspan="6"></td>
                                            @endif
                                        @endforeach
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-1 control-label">Comments:</label>
                                    <div class="col-md-11">
                                        {{$formData->comments}}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="box-footer">
                        <a class="btn btn-default" href="{{url('inventory/comparative-statement')}}">Back</a>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

@endsection





