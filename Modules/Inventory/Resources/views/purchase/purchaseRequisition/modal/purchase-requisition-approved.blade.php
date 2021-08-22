<form action="" method="POST">
    @csrf
<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
            aria-hidden="true">×</span></button>
    <h4 class="modal-title">Approved New Requision</h4>
</div>
<div class="modal-body">
    

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="col-md-4 control-label required">Voucher No</label>
                    <div class="col-md-8 p-b-15">
                        <input type="text" class="form-control" required placeholder="REQ-001">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label required">Req By</label>
                    <div class="col-md-8 p-b-15">
                        <select name="req_type" id="req_type" class="form-control" required>
                            <option value="0">Md Shoban</option>
                        </select>                                                   
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="col-md-4 control-label required">Req Date</label>
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
                            <input required class="form-control" id="req_date" name="req_date"placeholder="Choose a date"  />
                            <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span> 
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow">
            <table class="responsive table table-striped table-bordered" cellspacing="0">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th width="30%">Item Name</th>
                        <th>UOM</th>
                        <th>Qty</th>
                        <th width="15%">Approve Qty</th>
                        <th width="16%">Approve</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td valign="middle">1</td>
                        <td valign="middle">Item 1.</td>          
                        <td valign="middle">Pics</td>              
                        <td class="text-right" valign="middle">2</td>              
                        <td class="text-right" valign="middle">
                            <input type="number" class="form-control" name="" value="2"> 
                        </td>              
                        <td class="text-center" valign="middle">
                            <input type="checkbox" name="">
                        </td>              
                    </tr>
                    <tr>
                        <td valign="middle">2</td>
                        <td valign="middle">Item 2.</td>          
                        <td valign="middle">Pics</td>              
                        <td class="text-right" valign="middle">3</td>              
                        <td class="text-right" valign="middle">
                            <input type="number" class="form-control" name="" value="3"> 
                        </td>              
                        <td class="text-center" valign="middle">
                            <input type="checkbox" name="">
                        </td>              
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="form-group">
            <label class="col-md-3 control-label required">Comments</label>
            <div class="col-md-9 p-b-15">
                <textarea placeholder="Comments" class="form-control"></textarea>
            </div>
        </div>
   
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-success"><i class="icon-spinner icon-spin icon-large"></i>Approved</button>
    <button type="button" class="btn" data-dismiss="modal">Cancel</button>
</div>
 </form>

<script type="text/javascript">
    $('.date').datepicker();
</script>

