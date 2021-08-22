

{{--<button aria-label="Close" data-dismiss="modal" style="margin-right: 50px;margin-top:15px; " class="close" type="button"><span aria-hidden="true">×</span></button>--}}

<div class="box box-solid">
    <form id="institute-add-form" enctype="multipart/form-data" action="{{url('setting/store-add-institute')}}" method="post">

        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <div class="box-body">

            <h2 class="page-header edusec-page-header-1">
                <i class="fa fa-university"></i> Institute Setup Details	</h2>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group field-organization-org_name required">
                        <label class="control-label" for="institute_name">Institute Name</label>
                        <input type="text" id="institute_name" class="form-control" name="institute_name" value="" maxlength="255" aria-required="true">

                        <div class="help-block"></div>
                    </div>		</div>
                <div class="col-sm-6">
                    <div class="form-group field-organization-org_alias required">
                        <label class="control-label" for="institute_alias">Institute Alias</label>
                        <input type="text" id="institute_alias" class="form-control" name="institute_alias" value="" maxlength="255" aria-required="true">

                        <div class="help-block"></div>
                    </div>		</div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group field-organization-org_address_line1 required">
                        <label class="control-label" for="address1">Address Line 1</label>
                        <textarea id="address1" class="form-control" name="address1" maxlength="255" aria-required="true">
</textarea>

                        <div class="help-block"></div>
                    </div>		</div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group field-organization-org_address_line2">
                        <label class="control-label" for="address2">Address Line 2</label>
                        <textarea id="address2" class="form-control" name="address2" maxlength="255"></textarea>

                        <div class="help-block"></div>
                    </div>		</div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group field-organization-org_phone">
                        <label class="control-label" for="phone">Phone</label>
                        <input type="text" id="phone" class="form-control" name="phone" value="" maxlength="25">

                        <div class="help-block"></div>
                    </div>		</div>
                <div class="col-sm-6">
                    <div class="form-group field-organization-org_email">
                        <label class="control-label" for="email">Email</label>
                        <input type="text" id="email" class="form-control" name="email" value="" maxlength="65">

                        <div class="help-block"></div>
                    </div>		</div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group field-organization-org_website">
                        <label class="control-label" for="section_name">Website</label>
                        <input type="text" id="website" class="form-control" name="website" value="" maxlength="120">

                        <div class="help-block"></div>
                    </div>		</div>
                <div class="col-sm-6">
                    <div class="row">
                        <div class="col-sm-9 col-xs-8">
                            <div class="form-group field-organization-logo">
                                <label class="control-label" for="logo">Logo</label>

                                <input type="file" id="logo" name="logo">

                                <div class="help-block"></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-sm-2 col-md-offset-1">
                    <div class="form-group">
                        <label class="control-label" for="eiin_code">EIIN</label>
                        <input type="text" id="eiin_code" class="form-control" name="eiin_code">
                        <div class="help-block"></div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="control-label" for="institute_code">Institute Code</label>
                        <input type="text" id="institute_code" class="form-control" name="institute_code">
                        <div class="help-block"></div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="control-label" for="center_code">Center Code</label>
                        <input type="text" id="center_code" class="form-control" name="center_code">
                        <div class="help-block"></div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="control-label" for="upazila_code">Upazila Code</label>
                        <input type="text" id="upazila_code" class="form-control" name="upazila_code">
                        <div class="help-block"></div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="control-label" for="zilla_code">Zilla Code</label>
                        <input type="text" id="zilla_code" class="form-control" name="zilla_code">
                        <div class="help-block"></div>
                    </div>
                </div>
            </div>
        </div><!-- /.box-body -->

        <div class="box-footer">
            <button type="submit" class="btn btn-info btn-create">Save</button>
            <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Cancel</button></td>
        </div><!-- /.box-footer-->
    </form>
</div>

<script type ="text/javascript">

    $(document).ready(function(){
        $('#myTable').DataTable();
    });

    jQuery(document).ready(function() {
        jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });
    });
    $().ready(function() {
        // validate  form on keyup and submit
        $("#institute-add-form").validate({
            rules: {
                institute_name: "required",
                institute_alias: "required",
                address1:"required",
                logo:'required'


            },
            messages: {
                institute_name: "Please enter institute name",
                institute_alias: "Please enter institute alias",
                address1:"Please enter address1",
                logo:"Please provide logo",


            }
        });
    });

</script>


