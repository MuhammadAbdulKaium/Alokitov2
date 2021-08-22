<button aria-label="Close" data-dismiss="modal" style="margin-right: 50px;margin-top:15px; " class="close" type="button"><span aria-hidden="true">×</span></button>
    <div class="content-wrapper">

        </section>

        <section class="content">

            <div class="box box-solid">
                @foreach($data as $value)
                <form id="institute-update-form" enctype="multipart/form-data" action="{{url('setting/store-institute-update',$value->id)}}" method="post">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <div class="box-body">

                        <h2 class="page-header edusec-page-header-1">
                            <i class="fa fa-university"></i> Institute Setup Update	</h2>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group field-organization-org_name required">
                                    <label class="control-label" for="institute_name">Institute Name</label>
                                    <input type="text" id="institute_name" class="form-control" name="institute_name" value="{{$value->institute_name}}" maxlength="255" aria-required="true">

                                    <div class="help-block"></div>
                                </div>		</div>
                            <div class="col-sm-6">
                                <div class="form-group field-organization-org_alias required">
                                    <label class="control-label" for="institute_alias">Institute Alias</label>
                                    <input type="text" id="institute_alias" class="form-control" name="institute_alias" value="{{$value->institute_name}}" maxlength="255" aria-required="true">

                                    <div class="help-block"></div>
                                </div>		</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group field-organization-org_address_line1 required">
                                    <label class="control-label" for="address1">Address Line 1</label>
<textarea id="address1" class="form-control" name="address1" maxlength="255" aria-required="true">{{$value->address1}}
</textarea>

                                    <div class="help-block"></div>
                                </div>		</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group field-organization-org_address_line2">
                                    <label class="control-label" for="address2">Address Line 2</label>
                                    <textarea id="address2" class="form-control" name="address2" maxlength="255">{{$value->address2}}</textarea>

                                    <div class="help-block"></div>
                                </div>		</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group field-organization-org_phone">
                                    <label class="control-label" for="phone">Phone</label>
                                    <input type="text" id="phone" class="form-control" name="phone" value="{{$value->phone}}" maxlength="25">

                                    <div class="help-block"></div>
                                </div>		</div>
                            <div class="col-sm-6">
                                <div class="form-group field-organization-org_email">
                                    <label class="control-label" for="email">Email</label>
                                    <input type="text" id="email" class="form-control" name="email" value="{{$value->email}}" maxlength="65">

                                    <div class="help-block"></div>
                                </div>		</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group field-organization-org_website">
                                    <label class="control-label" for="section_name">Website</label>
                                    <input type="text" id="website" class="form-control" name="website" value="{{$value->website}}" maxlength="120">

                                    <div class="help-block"></div>
                                </div>		</div>
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-sm-9 col-xs-8">
                                        <div class="form-group field-organization-logo">
                                            <label class="control-label" for="logo">Logo</label>

                                            <input type="file" id="logo" name="logo">

                                            <div class="help-block"></div>
                                        </div>				</div>
                                    <div class="col-sm-3 col-xs-4 text-right">
                                        <img src="{{URL::asset('assets/users/images/'.$value->logo)}}" alt="Logo" height="100" width="100">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-info btn-create">Update</button>    		<a class="btn btn-default btn-create" href="{{url('setting')}}">Cancel</a>    </div><!-- /.box-footer-->

                </form>
                    @endforeach
            </div>
        </section>
    </div>

    <div id="slideToTop" ><i class="fa fa-chevron-up"></i></div>
</div>
<div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="loader">
                    <div class="es-spinner">
                        <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('scripts')
    <script type="text/javascript">

        $().ready(function() {
            // validate  form on keyup and submit
            $("#institute-update-form").validate({
                rules: {
                    institute_name: "required",
                    institute_alias: "required",
                    address1:"required",


                },
                messages: {
                    institute_name: "Please enter institute name",
                    institute_alias: "Please enter institute alias",
                    address1:"Please enter address1",


                }
            });
        });

    </script>

@endsection

