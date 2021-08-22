<!-- Modal content-->
<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Auto SMS Modules Update</h4>
    </div>
    <div class="modal-body">

            <form action="/setting/sms/autosmsmodule/update/" method="post" id="auto_sms_module">


                <input type="hidden" name="_token" value="3uGyaGtwl0tqCjoCrFEywPkHjJAAjcpZ5ELn1UxE">
                <input type="hidden" name="sms_modules_id" @if($autoSmsModuleProfile->id) value="{{$autoSmsModuleProfile->id}}" @endif>

                <div class="form-group">
                    <label for="pwd">Module Name:</label>
                    <input type="text" id="status_code" required="" @if($autoSmsModuleProfile->status_code) value="{{$autoSmsModuleProfile->status_code}}" @endif class="form-control" name="status_code">

                </div>

                <button type="submit" class="btn btn-success">Submit</button>
            </form>



    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</div>