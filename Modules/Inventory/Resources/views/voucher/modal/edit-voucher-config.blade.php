<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
            aria-hidden="true">×</span></button>
    <h4 class="modal-title">Update Voucher Config</h4>
</div>
<div class="modal-body">
    <form action="/inventory/update/voucher/{{$voucher->id}}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="row" style="margin-bottom: 15px">
                    <div class="col-sm-4">
                        <label for="">Type of Voucher: <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-sm-8">
                        <select name="type_of_voucher"  class="form-control" required>
                            <option <?php if($voucher->type_of_voucher ==1) echo 'selected'; ?> value="1">New Requisition</option>
                            <option <?php if($voucher->type_of_voucher ==2) echo 'selected'; ?> value="2">Issue From Inventory</option>
                            <option <?php if($voucher->type_of_voucher ==3) echo 'selected'; ?> value="3">Store Transfer Requisition</option>
                            <option <?php if($voucher->type_of_voucher ==4) echo 'selected'; ?> value="4">Store Transfer</option>
                            <option <?php if($voucher->type_of_voucher ==5) echo 'selected'; ?> value="5">Purchase Requisition</option>
                            <option <?php if($voucher->type_of_voucher ==14) echo 'selected'; ?> value="14">Comparative Statement</option>
                            <option <?php if($voucher->type_of_voucher ==6) echo 'selected'; ?> value="6">Purchase Order</option>
                            <option <?php if($voucher->type_of_voucher ==7) echo 'selected'; ?> value="7">Purchase Receive</option>
                            <option <?php if($voucher->type_of_voucher ==8) echo 'selected'; ?> value="8">Purchase Return</option>
                            <option <?php if($voucher->type_of_voucher ==9) echo 'selected'; ?> value="9">Sales Order</option>
                            <option <?php if($voucher->type_of_voucher ==10) echo 'selected'; ?> value="10">Sales/Delivery Challan</option>
                            <option <?php if($voucher->type_of_voucher ==11) echo 'selected'; ?> value="11">Sales Return</option>
                            <option <?php if($voucher->type_of_voucher ==12) echo 'selected'; ?> value="12">Stock In</option>
                             <option <?php if($voucher->type_of_voucher ==13) echo 'selected'; ?> value="12">Stock Out</option>
                        </select>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 15px">
                    <div class="col-sm-4">
                        <label for="">Numbering: <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-sm-8">
                        <select name="numbering" id="numbering"  class="form-control" required>
                            <option value="auto" selected>Auto</option>
                            <option <?php if($voucher->numbering =='menual') echo 'selected'; ?>  value="menual">Menual</option>
                        </select>
                    </div>
                </div>
                <div class="row numberingElement" style="margin-bottom: 15px; display: {{($voucher->numbering =='auto')?'block':'none'}}">
                    <div class="col-sm-4">
                        <label for="">Numeric Part: <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-sm-8">
                        <select name="numeric_part"  class="form-control">
                            <option <?php if($voucher->numeric_part ==1) echo 'selected'; ?> value="1">1</option>
                            <option <?php if($voucher->numeric_part ==2) echo 'selected'; ?> value="2">2</option>
                            <option <?php if($voucher->numeric_part ==3) echo 'selected'; ?> value="3">3</option>
                            <option <?php if($voucher->numeric_part ==4) echo 'selected'; ?> value="4">4</option>
                            <option <?php if($voucher->numeric_part ==5) echo 'selected'; ?> value="5">5</option>
                            <option <?php if($voucher->numeric_part ==6) echo 'selected'; ?> value="6">6</option>
                        </select>
                    </div>
                </div>
                <div class="row numberingElement" style="margin-bottom: 15px; display: {{($voucher->numbering =='auto')?'block':'none'}}">
                    <div class="col-sm-4">
                        <label for="">Suffix:</label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="suffix" maxlength="100" value="{{$voucher->suffix}}">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row" style="margin-bottom: 15px">
                    <div class="col-sm-4">
                        <label for="">Voucher Name: <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="voucher_name" required maxlength="100" value="{{$voucher->voucher_name}}">
                    </div>
                </div>
                <div class="row numberingElement" style="margin-bottom: 15px; display: {{($voucher->numbering =='auto')?'block':'none'}}">
                    <div class="col-sm-4">
                        <label for="">Starting Number: <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" name="starting_number" value="{{$voucher->starting_number}}">
                    </div>
                </div>
                <div class="row numberingElement" style="margin-bottom: 15px; display: {{($voucher->numbering =='auto')?'block':'none'}}">
                    <div class="col-sm-4">
                        <label for="">Prefix: <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="prefix" maxlength="100" value="{{$voucher->prefix}}">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <button class="btn btn-success pull-right">Update</button>
            </div>
        </div>
    </form>
</div>

<script>
    $("#numbering").change(function(){
        var numbering = $(this).val();
        if(numbering=='auto'){
            $('.numberingElement').css('display', 'block');
        }else{
            $('.numberingElement').css('display', 'none');
        }
    });
    
</script>