@extends('layouts.master')
@section('css')
 <link href="{{ URL::asset('css/custom.css') }}" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css"/>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-edit"></i> Purchase order Details
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="#">Inventory</a></li>
                <li><a href="#">Purchase</a></li>
                <li class="active">Purchase Order Details</li>
            </ul>
        </section>

        <section class="content">
            <div class="box box-solid">
                <div class="box-body scroll-table-200">
                    
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label required">PO Type</label>
                                <div class="col-md-8 p-b-15">
                                    Category 1                                              
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label required">Voucher No</label>
                                <div class="col-md-8 p-b-15">
                                    PO-001
                                </div>
                            </div>

                            
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Insturction of</label>
                                <div class="col-md-8 p-b-15">
                                    Instruction                                           
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label required">Campus</label>
                                <div class="col-md-8 p-b-15">
                                    Main Campus                                               
                                </div>
                            </div>


                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Party Name</label>
                                <div class="col-md-8 p-b-15">
                                    Interpack Ltd.                                                
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Date</label>
                                <div class="col-md-8 p-b-15">
                                    04/21/2121
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Due Date</label>
                                <div class="col-md-8 p-b-15">
                                    04/25/2121
                                </div>
                            </div>                            

                        </div>
                    </div>                   
                    <div class="table-responsive">
                    <table class="responsive table table-striped table-bordered" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th width="10%">Item Name</th>
                                <th>UOM</th>
                                <th>Qty</th>
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
                                <td valign="middle" class="text-right">2</td>              
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
                       
                </div>
                <div class="box-footer">
                    <a class="btn btn-default" href="{{url('inventory/purchase-order')}}">Cancel</a>
                </div>
            </div>
        </section>
    </div>    

@endsection

