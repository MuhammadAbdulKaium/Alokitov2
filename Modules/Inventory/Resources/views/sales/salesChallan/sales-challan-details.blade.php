@extends('layouts.master')
@section('css')
 <link href="{{ URL::asset('css/custom.css') }}" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css"/>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-plus"></i> Sales/Delivery Challan Details
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="#">Inventory</a></li>
                <li><a href="#">Sales</a></li>
                <li class="active">
                <i class="fa fa-plus"></i> Sales/Delivery Challan Details</li>
            </ul>
        </section>

        <section class="content">

            <div id="p0">
                @if(Session::has('message'))
                    <p class="alert alert-success alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('message') }}</p>
                @elseif(Session::has('alert'))
                    <p class="alert alert-warning alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('alert') }}</p>
                @elseif(Session::has('errorMessage'))
                    <p class="alert alert-danger alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('errorMessage') }}</p>
                @endif
            </div>
            
            <div class="box box-solid">
                <div class="box-body scroll-table">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Voucher No</label>
                                <div class="col-md-8 p-b-15">
                                    SC0001
                                </div>
                            </div>

                             <div class="form-group">
                                <label class="col-md-4 control-label required">Customer</label>
                                <div class="col-md-8 p-b-15">
                                    Customer
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Date</label>
                                <div class="col-md-8 p-b-15">
                                    04/22/2021
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label required">Comments</label>
                                <div class="col-md-8 p-b-15">
                                   Comments 
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    
                    <div class="table-responsive">
                    <table class="responsive table table-striped table-bordered" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th width="10%">Item Name</th>
                                <th>UOM</th>
                                <th width="10%">Qty</th>
                                <th>Rate</th>
                                <th>Amount</th>
                                <th>Discount</th>
                                <th>Vat</th>
                                <th>Vat Type</th>
                                <th>Net Amount</th>
                                <th>Ref</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td valign="middle">1</td>
                                <td valign="middle">Item 1.</td>          
                                <td valign="middle">Pics</td>              
                                <td valign="middle">
                                    <input type="number" name="" placeholder="qty" style="width: 80px">
                                </td>              
                                <td valign="middle" class="text-right">100</td>              
                                <td valign="middle" class="text-right">200</td>              
                                <td valign="middle" class="text-right">10</td>              
                                <td valign="middle" class="text-right">20</td>              
                                <td valign="middle">Fixed</td>              
                                <td class="text-right" valign="middle">210</td>              
                                <td valign="middle">Req001</td>              
                            </tr>
                            <!-- <tr>
                                <td colspan="6" align="center">Nothing here</td>
                            </tr> -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right"><b>Total</b></td>
                                <td class="text-right">2</td>
                                <td class="text-right">100</td>
                                <td class="text-right">200</td>
                                <td class="text-right">10</td>
                                <td class="text-right">20</td>
                                <td class="text-right"></td>
                                <td class="text-right">210</td>
                                <td class="text-right"></td>
                            </tr>
                        </tfoot>
                    </table>
                    </div>
                    <a class="btn btn-default" href="{{url('inventory/sales-challan')}}">Cancel</a>
                    
                </div>
            </div>
            
        </section>
    </div>    

@endsection




