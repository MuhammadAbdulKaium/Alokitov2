

<div class="modal-header">
   <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
   <h4 class="modal-title">
      <i class="fa fa-plus-square"></i> Upload New Document       
   </h4>
</div>
<form id="stu-upload-documents" action="{{url('/employee/profile/documents/store')}}" method="POST" enctype="multipart/form-data">
   <input type="hidden" name="_token" value="{{csrf_token()}}">
   <input type="hidden" name="employee_id" value="{{$emp_id}}">
   <div class="modal-body">
      <div class="row">
         <div class="col-sm-12">
            <div class="form-group">
               <label class="control-label" for="doc_type">Category</label>
               <select id="doc_type" class="form-control" name="document_category" required>
                  <option value="">--- Select Document Category ---</option>
                  <option value="nid">NID</option>
                  <option value="passport">Passport</option>
                  <option value="birth">Birt Certificate NO</option>
                  <option value="tin">TIN</option>
                  <option value="dl">D.l</option>
                  <option value="vehicle">Vehicle Registration Certificate</option>
                  <option value="academic">Academic Certificate</option>
                  <option value="other">Others</option>
               </select>
               <div class="help-block"></div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-12">
            <div class="form-group">
               <label class="control-label" for="doc_details">Document Details</label>
               <textarea id="doc_details" class="form-control" name="document_details" maxlength="100" placeholder="Enter Document Related Description" required></textarea>
               <div class="help-block"></div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-12">
            <div class="form-group">
               <label class="control-label" for="doc_submited_at">Submited Date</label>
               <input id="doc_submited_at" class="form-control" name="document_submitted_at" readonly placeholder="Select Submited Date" size="10" type="text" required>
               <div class="help-block"></div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-12">
            <div class="form-group">
               <label class="control-label" for="studocs-stu_docs_path">Document</label>
               <input id="image" name="document_file" title="Browse Document" type="file" required>
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

