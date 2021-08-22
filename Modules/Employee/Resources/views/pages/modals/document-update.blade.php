

<div class="modal-header">
   <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
   <h4 class="modal-title"><i class="fa fa-plus-square"></i> Update Document</h4>
</div>
<form id="stu-upload-documents" action="{{url('/employee/profile/documents/update', [$attachment->id])}}" method="post" enctype="multipart/form-data">
   <input type="hidden" name="_token" value="{{csrf_token()}}">
   <div class="modal-body">
      <div class="row">
         <div class="col-sm-12">
            <div class="form-group">
               <label class="control-label" for="doc_type">Category</label>
               <select id="doc_type" class="form-control" name="document_category" required>
                  <option value="">--- Select Document Category ---</option>
                  <option value="nid" @if($attachment->document_category=="nid") selected @endif>NID</option>
                  <option value="passport" @if($attachment->document_category=="passport") selected @endif>Passport</option>
                  <option value="birth" @if($attachment->document_category=="birth") selected @endif>Birt Certificate NO</option>
                  <option value="tin" @if($attachment->document_category=="tin") selected @endif>TIN</option>
                  <option value="dl" @if($attachment->document_category=="dl") selected @endif>D.l</option>
                  <option value="vehicle" @if($attachment->document_category=="vehicle") selected @endif>Vehicle Registration Certificate</option>
                  <option value="academic" @if($attachment->document_category=="academic") selected @endif>Academic Certificate</option>
                  <option value="other" @if($attachment->document_category=="other") selected @endif>Others</option>
               </select>
               <div class="help-block"></div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-12">
            <div class="form-group">
               <label class="control-label" for="doc_details">Document Details</label>
               <textarea id="doc_details" class="form-control" name="document_details" maxlength="100" placeholder="Enter Document Related Description" required>@if($attachment->document_details){{$attachment->document_details}}@endif</textarea>
               <div class="help-block"></div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-12">
            <div class="form-group">
               <label class="control-label" for="doc_submited_at">Submited Date</label>
               <input id="doc_submited_at" class="form-control" name="document_submitted_at" readonly value="@if($attachment->document_submitted_at){{\Carbon\Carbon::parse($attachment->document_submitted_at)->format('m/d/Y')}}@endif" size="10" type="text" required>
               <div class="help-block"></div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-12">
            <div class="form-group">
               <label class="control-label" for="studocs-stu_docs_path">Document</label>
               <input id="image" name="document_file" title="Browse Document" type="file">
               <div class="hint-block">NOTE : Upload only JPG, JPEG, PNG, TXT and PDF file and smaller than 512KB</div>
               <div class="help-block"></div>
            </div>
         </div>
      </div>
   </div>
   <!--./modal-body-->
   <div class="modal-footer">
      <button type="submit" class="btn btn-info pull-left"><i class="fa fa-upload"></i> Upload</button>  <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
   </div>
   <!--./modal-footer-->
</form>





<script type="text/javascript"> 

      jQuery('#doc_submited_at').datepicker({
          "changeMonth":true,"changeYear":true,"autoSize":true,"dateFormat":"yy-mm-dd",
          "changeMonth": true,
          "yearRange": "1900:2018",
          "changeYear": true,
          "autoSize": true,
          "dateFormat": "yy-mm-dd"
      });
</script>

