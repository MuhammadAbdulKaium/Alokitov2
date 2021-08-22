@extends('layouts.master')
@section('css')
 <link href="{{ URL::asset('css/custom.css') }}" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css"/>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-plus"></i> Sales/Delivery Challan
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="#">Inventory</a></li>
                <li><a href="#">Sales</a></li>
                <li class="active">
                <i class="fa fa-plus"></i> Sales/Delivery Challan</li>
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
                    <form action="{{url('inventory/sales-challan/create')}}" method="get">
                    <div class="row">
                        <div class="col-sm-11">
                            <div class="form-group"  style="width: 100%">
                                <label class="col-md-2 control-label required p-r-0">Customer</label>
                                <div class="col-md-4">
                                    <select name="customer_id" id="customer_id" style="width:100%;" class="select2" >
                                        <option value="">-- Customer list --</option>
                                        <option <?php if($customer_id==1) echo 'selected'; ?> value="1"> Customer 1</option>
                                        <option  <?php if($customer_id==2) echo 'selected'; ?> value="2">Customer 2</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-search" aria-hidden="true"></i>
                                        Search</button>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    </form>
                    <form action="">
                    @csrf
                    <hr>
                    <?php if(!empty($customer_id)){ ?>
                    <div class="row">
                        <div class="col-sm-6">
                            
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Voucher No</label>
                                <div class="col-md-8 p-b-15">
                                    <input type="text" class="form-control" required placeholder="POR-001">
                                </div>
                            </div>

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
                                <label class="col-md-4 control-label required">Comments</label>
                                <div class="col-md-8 p-b-15">
                                   <textarea name="comments" class="form-control" placeholder="Write a comments"></textarea>
                                </div>
                            </div>
                            

                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-md-4 control-label required">Reference Type</label>
                                <div class="col-md-8 p-b-15">
                                    <select name="req_type" id="req_type" class="form-control select2" required>
                                        <option value="0">None</option>
                                        <option value="0">Sales Order</option>
                                    </select>                                                   
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label required">Ref</label>
                                <div class="col-md-8 p-b-15">
                                    <button type="button" class="btn btn-success"><i class="icon-spinner icon-spin icon-large"></i>Add</button>                                        
                                </div>
                            </div>
                            <div class="panel-body table-responsive">
                                <table class="responsive table table-striped table-bordered" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Item Name</th>
                                            <th>Ref</th>
                                            <th>Qty</th>
                                            <th>Delivery Qty</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td valign="middle"><input type="checkbox" name=""></td>
                                            <td valign="middle">Item 1</td>
                                            <td valign="middle">SO001</td>          
                                            <td valign="middle">1</td>              
                                            <td valign="middle">0</td>              
                                            <td valign="middle">04/04/2021</td>              
                                        </tr>

                                        <tr>
                                            <td valign="middle"><input type="checkbox" name=""></td>
                                            <td valign="middle">Item 2</td>
                                            <td valign="middle">SO001</td>          
                                            <td valign="middle">1</td>
                                            <td valign="middle">0</td>              
                                            <td valign="middle">04/04/2021</td>              
                                        </tr>
                                        <tr>
                                            <td valign="middle"><input type="checkbox" name=""></td>
                                            <td valign="middle">Item 1</td>
                                            <td valign="middle">SO001</td>          
                                            <td valign="middle">1</td>   
                                            <td valign="middle">0</td>           
                                            <td valign="middle">04/04/2021</td>              
                                        </tr>

                                        <tr>
                                            <td valign="middle"><input type="checkbox" name=""></td>
                                            <td valign="middle">Item 2</td>
                                            <td valign="middle">SO001</td>          
                                            <td valign="middle">1</td>  
                                            <td valign="middle">0</td>            
                                            <td valign="middle">04/04/2021</td>              
                                        </tr>
                                        <tr>
                                            <td valign="middle"><input type="checkbox" name=""></td>
                                            <td valign="middle">Item 1</td>
                                            <td valign="middle">SO001</td>          
                                            <td valign="middle">1</td>  
                                            <td valign="middle">0</td>            
                                            <td valign="middle">04/04/2021</td>              
                                        </tr>

                                        <tr>
                                            <td valign="middle"><input type="checkbox" name=""></td>
                                            <td valign="middle">Item 2</td>
                                            <td valign="middle">SO001</td>          
                                            <td valign="middle">1</td> 
                                            <td valign="middle">0</td>             
                                            <td valign="middle">04/04/2021</td>              
                                        </tr>
                                    </tbody>
                                </table>
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
                                <th class="text-center" width="10%">Action</th>
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
                                <td class="text-center" valign="middle">
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
                                <td class="text-right"></td>
                            </tr>
                        </tfoot>
                    </table>
                    </div>
                    <button type="submit" class="btn btn-primary">Delivery</button>   <a class="btn btn-default" href="{{url('inventory/sales-challan')}}">Cancel</a>
                    </form>
                    <?php } ?>
   
                </div>
            </div>
            
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
