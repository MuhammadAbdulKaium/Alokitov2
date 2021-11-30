<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
            aria-hidden="true">×</span></button>
    <h4 class="modal-title">Stock Item Serial Details</h4>
</div>
<div class="modal-body" v-if="!pageLoader">
    <div class="row">
        <div class="col-sm-12">
            <table class="table table-bordered">
               
                    <tr>
                        <td><b>Stock Item:</b> {{$stockItemSerial->product_name}}</td>
                        <td><b>Prefix:</b> {{$stockItemSerial->prefix}}</td>
                        <td><b>Suffix:</b> {{$stockItemSerial->suffix}}</td>
                    </tr>
                    <tr>
                        <td><b>Separator:</b> {{$stockItemSerial->separator_symbol}}</td>
                        <td><b>Serial From:</b> {{$stockItemSerial->serial_from}}</td>
                        <td><b>Serial To:</b> {{$stockItemSerial->serial_to}}</td>
                    </tr>
               
            </table>
        </div>
        
    </div>
    <div class="panel-body table-responsive" style="max-height: 300px">
        <table class="responsive table table-striped table-bordered" cellspacing="0">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Serial Code</th>
                    <th>Barcode</th>
                    <th>QR Code</th>
                </tr>
            </thead>
            <tbody>
                @foreach($serial_code_list as $key => $value)
                    <?php 
                        $barcode = (!empty($value->barcode))?$value->barcode:'empty';  
                        $qrcode = (!empty($value->qrcode))?$value->qrcode:'empty';  
                        if(\Illuminate\Support\Str::contains($barcode,$charAr)){ 
                            $barcode = 'invalid';
                        } 
                        if(\Illuminate\Support\Str::contains($qrcode,$charAr)){ 
                            $qrcode = 'invalid';
                        }  
                    ?>
                    <tr>
                        <td valign="middle">{{$key+1}}</td>
                        <td valign="middle">{{$value->serial_code}}</td>          
                        <td valign="middle">
                            <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($barcode, 'C39',1,33,array(0,0,0), true)}}" alt="barcode" width="90" height="30" />
                            
                        </td>              
                        <td valign="middle">
                            <?php echo DNS2D::getBarcodeHTML($qrcode, 'QRCODE',2,2); ?>
                        </td>              
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn" data-dismiss="modal">Cancel</button>
</div>