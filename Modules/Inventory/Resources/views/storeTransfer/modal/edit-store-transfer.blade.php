<form action="" method="POST">
    @csrf
<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
            aria-hidden="true">×</span></button>
    <h4 class="modal-title">Edit Store Transfer</h4>
</div>
<div class="modal-body scroll-table">
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label class="col-md-4 control-label required">Voucher No</label>
                <div class="col-md-8 p-b-15">
                    <input type="text" class="form-control" required placeholder="IFI-001">
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
                <label class="col-md-4 control-label required">Store from</label>
                <div class="col-md-8 p-b-15">
                    <select name="req_type" id="req_type" class="form-control select2" required>
                        <option value="0">Mess</option>
                    </select>                                                   
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label required">Store to</label>
                <div class="col-md-8 p-b-15">
                    <select name="req_type" id="req_type" class="form-control select2" required>
                        <option value="0">Canteen</option>
                    </select>                                                   
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label required">Representative</label>
                <div class="col-md-8 p-b-15">
                    <select name="req_type" id="req_type" class="form-control select2" required>
                        <option value="0">Md Shoban</option>
                    </select>                                                   
                </div>
            </div>


        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label class="col-md-4 control-label required">Reference Type</label>
                <div class="col-md-8 p-b-15">
                    <select name="req_type" id="req_type" class="form-control select2" required>
                        <option value="0">None</option>
                        <option value="0">Requisition</option>
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
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td valign="middle"><input type="checkbox" name=""></td>
                            <td valign="middle">Item 1</td>
                            <td valign="middle">Req001</td>          
                            <td valign="middle">1</td>              
                            <td valign="middle">04/04/2021</td>              
                        </tr>

                        <tr>
                            <td valign="middle"><input type="checkbox" name=""></td>
                            <td valign="middle">Item 2</td>
                            <td valign="middle">Req001</td>          
                            <td valign="middle">1</td>              
                            <td valign="middle">04/04/2021</td>              
                        </tr>
                        <tr>
                            <td valign="middle"><input type="checkbox" name=""></td>
                            <td valign="middle">Item 1</td>
                            <td valign="middle">Req001</td>          
                            <td valign="middle">1</td>              
                            <td valign="middle">04/04/2021</td>              
                        </tr>

                        <tr>
                            <td valign="middle"><input type="checkbox" name=""></td>
                            <td valign="middle">Item 2</td>
                            <td valign="middle">Req001</td>          
                            <td valign="middle">1</td>              
                            <td valign="middle">04/04/2021</td>              
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <p style="margin-top: 6px;margin-bottom: 0"><b>Choose Store Transfer Item: </b></p> 
    <div class="overflow">
        <table class="responsive table table-striped table-bordered" cellspacing="0">
            <thead>
                <tr>
                    <th width="60%">Item Name</th>
                    <th>Quantity</th>
                    <th class="text-center" width="15%">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center table-multiselect" valign="middle" width="30%">
                        <select class="form-control">
                            <option value="">-- Choose Item --</option>
                            <option value="">Item 1</option>
                        </select>
                    </td>
                    <td class="text-center" valign="middle">
                        <input type="number" name="qty" class="form-control" autocomplete="off" placeholder="Quantity">   
                    </td>
                    <td class="text-center">
                        <button class="btn btn-info table-input-redious">ADD</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>


    

    <p style="margin-top: 6px;margin-bottom: 0"><b>Transfer Item Draft List: </b></p>
    
   
    <table class="responsive table table-striped table-bordered" cellspacing="0">
        <thead>
            <tr>
                <th>No.</th>
                <th width="30%">Item Name</th>
                <th>UOM</th>
                <th>Qty</th>
                <th>Ref</th>
                <th class="text-center" width="16%">Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td valign="middle">1</td>
                <td valign="middle">Item 1.</td>          
                <td valign="middle">Pics</td>              
                <td class="text-right" valign="middle">2</td>              
                <td valign="middle">Req001</td>              
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
                <td></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
   

    <div class="form-group">
        <label class="col-md-3 control-label required">Comments</label>
        <div class="col-md-9 p-b-15">
            <textarea placeholder="Comments" class="form-control"></textarea>
        </div>
    </div>
        
   
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-success"><i class="icon-spinner icon-spin icon-large"></i>Update</button>
    <button type="button" class="btn" data-dismiss="modal">Cancel</button>
</div>
 </form>

<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2();
    });
    $('.date').datepicker();
</script>

