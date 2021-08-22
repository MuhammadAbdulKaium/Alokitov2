<form action="" method="POST">
    @csrf
<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
            aria-hidden="true">×</span></button>
    <h4 class="modal-title">Stock In Details</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label class="col-md-4 control-label required">Category</label>
                <div class="col-md-8 p-b-15">
                    Dirrect Purchase    
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-4 control-label required">Voucher No</label>
                <div class="col-md-8 p-b-15">
                    #DPUR-001
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-4 control-label required">Date</label>
                <div class="col-md-8 p-b-15">
                   04/22/2021
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label class="col-md-4 control-label required">Campus</label>
                <div class="col-md-8 p-b-15">
                    Main Campus
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-4 control-label required">Store</label>
                <div class="col-md-8 p-b-15">
                    Mess
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-4 control-label required">Representative</label>
                <div class="col-md-8 p-b-15">
                    Md. Sobahan
                </div>
            </div>
        </div>
    </div>
    
    <table class="responsive table table-striped table-bordered" cellspacing="0">
        <thead>
            <tr>
                <th>No.</th>
                <th>Item Name</th>
                <th>UOM</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td valign="middle">1</td>
                <td valign="middle">Item 1.</td>          
                <td valign="middle">Pics</td>              
                <td class="text-right" valign="middle">2</td>              
                <td class="text-right" valign="middle">100</td>              
                <td class="text-right" valign="middle">200</td>              
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
            </tr>
        </tfoot>
    </table>
    

    <div class="form-group">
        <label class="col-md-3 control-label required">Comments</label>
        <div class="col-md-9 p-b-15">
            Comments are here...
        </div>
    </div>
   
</div>
<div class="modal-footer">
    <button type="button" class="btn" data-dismiss="modal">Cancel</button>
</div>
 </form>


