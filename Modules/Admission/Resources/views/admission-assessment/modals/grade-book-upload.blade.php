<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title">
        <i class="fa fa-plus-square"></i> Applicant Grades Upload
    </h4>
</div>
<form id="applicant-grade-upload-form" action="{{url('/admission/assessment/grade-book/upload')}}" method="post" enctype="multipart/form-data">
    <input name="_token" value="{{csrf_token()}}" type="hidden">
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="callout callout-info">
                    <h4><i class="fa fa-bullhorn" aria-hidden="true"></i> Instructions :</h4>
                    <ol>
                        <li><b>The field with red color are required.</b></li>
                        <li>All date must be enter <strong>DD-MM-YYYY</strong> format.</li>
                        <li>Student ID is auto generated.</li>
                        <li>Birth date must be less than current date.</li>
                        <li>Email ID should be in valid email format.</li>
                        <li>Max upload records limit is <strong>500</strong>.</li>
                        <li>Student import data must match with current application language.</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="control-label" for="applicant_grade_book">Document</label>
                    <input id="applicant_grade_book" name="applicant_grade_book" title="Browse Document" required type="file">
                    <div class="hint-block">NOTE : Upload only .xlsx file and smaller than 512KB</div>
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
    </div>
    <!--./modal-body-->
    <div class="modal-footer">
        <button type="button" id="submitBtn" class="btn btn-primary"><i class="fa fa-upload"></i> Import</button>
        <button data-dismiss="modal" class="btn btn-default " type="button">Close</button>
    </div>
    <!--./modal-footer-->
</form>

<script type="text/javascript">
    $(document).ready(function(){

        $("#submitBtn").click(function () {
            // checking
            if($('#applicant_grade_book').val()){
                var applicant_grade_upload_form =   $('#applicant-grade-upload-form')
                    .append('<input type="hidden" name="academic_year" value="'+$('#academic_year').val()+'"/>')
                    .append('<input type="hidden" name="academic_level" value="'+$('#academic_level').val()+'"/>')
                    .append('<input type="hidden" name="academic_batch" value="'+$('#batch').val()+'"/>')
                $("#globalModal").modal();

                var progressbar = $('.progress-bar');

                $(applicant_grade_upload_form).ajaxForm({
                    beforeSend: function() {
                        // show waiting dialog
                        waitingDialog.show('Submitting...');
                    },

                    success:function(data){
                        $("#globalModal").modal('toggle');
                        var applicant_grade_content_row = $('#applicant_grade_content_row');
                        applicant_grade_content_row.html('');
                        applicant_grade_content_row.append(data);
                        // hide dialog
                        waitingDialog.hide();
                    }
                }).submit();
            }else {
                alert('No Grade-Book Selected');
            }
        });
    });
</script>