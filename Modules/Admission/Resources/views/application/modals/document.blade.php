
<div class="modal-header">
	<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
	<h4 class="modal-title">
		<i class="fa fa-plus-square"></i> {{$documentProfile?'Update':'Add'}} Document
	</h4>
</div>

<form id="stu-upload-documents" action="{{url('/admission/applicant/document/store')}}" method="post" enctype="multipart/form-data">
	<input type="hidden" name="_token" value="{{csrf_token()}}">
	<input type="hidden" name="applicant_id" value="{{$applicantProfile->id}}">
	<input type="hidden" name="document_id" value="{{$documentProfile?$documentProfile->id:'0'}}">
	<div class="modal-body">
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<label class="control-label" for="doc_type">Category</label>
					<select id="doc_type" class="form-control" name="doc_type" required>
						<option value="">--- Select Document Category ---</option>
						<option value="SSC_CERTIFICATE" @if($documentProfile){{$documentProfile->doc_type == 'SSC_CERTIFICATE'?'selected':''}}@endif>SSC Certificate</option>
						<option value="HSC_CERTIFICATE" @if($documentProfile){{$documentProfile->doc_type == 'HSC_CERTIFICATE'?'selected':''}}@endif>HSC Certificate</option>
						<option value="BIRTH_CERTIFICATE" @if($documentProfile){{$documentProfile->doc_type == 'BIRTH_CERTIFICATE'?'selected':''}}@endif>Birth Certificate</option>
					</select>
					<div class="help-block"></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<label class="control-label" for="doc_details">Document Details</label>
					<textarea id="doc_details" class="form-control" name="doc_details" maxlength="100" placeholder="Enter Document Related Description" required> {{$documentProfile?$documentProfile->doc_details:''}}</textarea>
					<div class="help-block"></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<label class="control-label" for="document">Document</label>
					<input id="document" name="document" title="Browse Document" type="file" required>
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