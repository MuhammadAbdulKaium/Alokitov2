@extends('layouts.master')
@section('css')
 <link href="{{ URL::asset('css/custom.css') }}" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css"/>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-plus"></i> Sales order
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="#">Inventory</a></li>
                <li><a href="#">Sales</a></li>
                <li class="active">Sales Order</li>
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
            <form action="">
                @csrf
            <div class="box box-solid">
                <div class="box-body scroll-table-200">
                    
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Voucher No</label>
                                <div class="col-md-8 p-b-15">
                                    <input type="text" class="form-control" required placeholder="SO-001">
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-md-4 control-label required">Customer Name</label>
                                <div class="col-md-8 p-b-15">
                                    <select name="req_type" id="req_type" class="form-control select2" required>
                                        <option value="0">Customer</option>
                                    </select>                                                   
                                </div>
                            </div>
                            
                            

                            <div class="form-group">
                                <label class="col-md-4 control-label required">Campus</label>
                                <div class="col-md-8 p-b-15">
                                    <select name="req_type" id="req_type" class="form-control select2" required>
                                        <option value="0">Main Campus</option>
                                    </select>                                                   
                                </div>
                            </div>

                            
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Representative</label>
                                <div class="col-md-8 p-b-15">
                                    <select name="req_type" id="req_type" class="form-control select2" required>
                                        <option value="0">Shobhan</option>
                                    </select>                                                   
                                </div>
                            </div>


                            


                        </div>
                        <div class="col-sm-6">
                            
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Date</label>
                                <div class="col-md-8 p-b-15">
                                    <div class="input-group date bs-date">
                                        <input required class="form-control date-picker" id="req_date" name="req_date"placeholder="Choose a date"  />
                                        <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span> 
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label required">Due Date</label>
                                <div class="col-md-8 p-b-15">
                                    <div class="input-group date bs-date">
                                        <input required class="form-control date-picker" id="req_date" name="req_date"placeholder="Choose a date"  />
                                        <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span> 
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label required">Store</label>
                                <div class="col-md-8 p-b-15">
                                    <select name="req_type" id="req_type" class="form-control select2" required>
                                        <option value="0">Mess</option>
                                    </select>                                                   
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label required">Comments</label>
                                <div class="col-md-8 p-b-15">
                                   <textarea name="comments" class="form-control" placeholder="Write a comments"></textarea>
                                </div>
                            </div>


                        </div>
                    </div>

                    <p style="margin-top: 6px;margin-bottom: 0"><b>Choose Sales Order Item: </b></p> 
                    <div class="table-responsive">
                    <table class="responsive table table-striped table-bordered" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="20%">Item Name</th>
                                <th>Stock</th>
                                <th>Qty</th>
                                <th>Rate</th>
                                <th>Unit</th>
                                <th>Amount</th>
                                <th>Discount</th>
                                <th>Vat</th>
                                <th>Vat type</th>
                                <th>Net amount</th>
                                <th class="text-center" width="10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center" valign="middle" width="20%">
                                    <select class="form-control select2">
                                        <option value="">-- Choose Item --</option>
                                        <option value="">Item 1</option>
                                    </select>
                                </td>
                                <td class="text-center" valign="middle">
                                    10
                                </td>
                                <td class="text-center" valign="middle">
                                    <input type="number" name="qty" autocomplete="off" placeholder="Quantity" style="width: 80px">   
                                </td>
                                <td class="text-center" valign="middle">
                                    <input type="number" name="qty" autocomplete="off" placeholder="Rate" style="width: 100px">   
                                </td>
                                <td class="text-center" valign="middle">
                                    unit
                                </td>
                                <td class="text-center" valign="middle">
                                    <input type="number" name="qty" autocomplete="off" placeholder="Amount" style="width: 100px">   
                                </td>
                                <td class="text-center" valign="middle">
                                    <input type="number" name="qty" autocomplete="off" placeholder="Discount" style="width: 100px">   
                                </td>
                                <td class="text-center" valign="middle">
                                    <input type="number" name="qty" autocomplete="off" placeholder="Vate" style="width: 100px">   
                                </td>
                                <td class="text-center" valign="middle">
                                    <select name="" style="width: 100px">
                                        <option value="fixed">Fixed</option>
                                    </select>
                                </td>
                                <td class="text-center" valign="middle">
                                    <input type="number" name="qty" autocomplete="off" placeholder="Net amount" style="width: 100px">   
                                </td>

                                <td class="text-center">
                                    <button class="btn btn-info table-input-redious">ADD</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </div>

                    
                    <p style="margin-top: 6px;margin-bottom: 0"><b>Order Item Draft List: </b></p>
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
                                <th class="text-center" width="16%">Action</th>
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
                                <td class="text-center" valign="middle">
                                    <button class="btn-info btn-xs" title="Edit"><i class="fa fa-pencil-square-o"></i></button>
                                    <button class="btn-xs btn-danger" title="Delete"><i class="fa fa-trash"></i></button>
                                </td>              
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
                    <button type="submit" class="btn btn-primary">Create</button>   <a class="btn btn-default" href="{{url('inventory/sales-order')}}">Cancel</a>
                </div>
            </div>
            </form>
        </section>
    </div>    

@endsection



@section('scripts')
<script>

    $(document).ready(function() {
        $('.select2').select2();
        $('.date').datepicker();
        jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });
    });
</script>   
@endsection
