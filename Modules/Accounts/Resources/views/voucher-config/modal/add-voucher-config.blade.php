<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
            aria-hidden="true">×</span></button>
    <h4 class="modal-title">Add New Voucher Config</h4>
</div>
<div class="modal-body">
    <form action="/accounts/voucher-config-list" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="row" style="margin-bottom: 15px">
                    <div class="col-sm-4">
                        <label for="">Campus: <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-sm-8">
                        <select name="campus_id" id="campus_id" class="form-control" required>
                            <?php foreach ($instititue_list as $v){ ?>
                            <optgroup label="{{$v->institute_name}}">
                                <?php foreach($v->campus() as $campus){ ?>
                                   <option value="{{$campus->id}}">{{$campus->name}}</option>
                                <?php } ?>
                            </optgroup>
                            <?php } ?>
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
                            <option value="manual">Manual</option>
                        </select>
                    </div>
                </div>
                <div class="row numberingElement" style="margin-bottom: 15px">
                    <div class="col-sm-4">
                        <label for="">Numeric Part: <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-sm-8">
                        <select name="numeric_part"  class="form-control">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                        </select>
                    </div>
                </div>
                <div class="row numberingElement" style="margin-bottom: 15px">
                    <div class="col-sm-4">
                        <label for="">Prefix: <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="prefix" maxlength="100">
                    </div>
                </div>

                <div class="row" style="margin-bottom: 15px">
                    <div class="col-sm-4">
                        <label for="">Status: <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-sm-8">
                        <select name="status"  class="form-control" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                
            </div>
            <div class="col-md-6">
                
                <div class="row" style="margin-bottom: 15px">
                    <div class="col-sm-4">
                        <label for="">Type of Voucher: <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-sm-8">
                        <select name="type_of_voucher" id="type_of_voucher" class="form-control" required>
                            <option value="1">Payment Voucher Bank</option>
                            <option value="2">Payment Voucher Cash</option>
                            <option value="3">Receive Voucher Bank</option>
                            <option value="4">Receive Voucher Cash</option>
                            <option value="5">Journal Voucher</option>
                            <option value="6">Contra Voucher</option>
                        </select>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 15px">
                    <div class="col-sm-4">
                        <label for="">Voucher Name: <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="voucher_name" required maxlength="100">
                    </div>
                </div>
                <div class="row numberingElement" style="margin-bottom: 15px">
                    <div class="col-sm-4">
                        <label for="">Starting Number: <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" name="starting_number">
                    </div>
                </div>

                <div class="row numberingElement" style="margin-bottom: 15px">
                    <div class="col-sm-4">
                        <label for="">Suffix:</label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="suffix" maxlength="100">
                    </div>
                </div>
                
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <button class="btn btn-success pull-right">Add</button>
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

    $(document).ready(function (){
        $('#type_of_voucher').select2();
    });
    
</script>