
<div class="box box-solid">
    <button aria-label="Close" data-dismiss="modal" style="margin-right: 50px;margin-top:15px; " class="close" type="button">
        <span aria-hidden="true">×</span>
    </button>

    <form id="campus-add-form" enctype="multipart/form-data" action="{{url('setting/store-add-campus',$institute_id)}}" method="post">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <div class="box-body">
            <h2 class="page-header edusec-page-header-1">
                <i class="fa fa-university"></i>Add Campus</h2>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group field-organization-org_name required">
                        <label class="control-label" for="name">Name</label>
                        <input type="text" id="name" class="form-control" name="name" value="" maxlength="255" aria-required="true">

                        <div class="help-block">
                            @if ($errors->has('name'))
                                <strong>{{ $errors->first('name') }}</strong>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group field-organization-org_alias required">
                        <label class="control-label" for="campus_code">Campus Code</label>
                        <input type="text" id="campus_code" class="form-control" name="campus_code" value="" maxlength="255" aria-required="true">
                        <div class="help-block">
                            @if ($errors->has('phone'))
                                <strong>{{ $errors->first('phone') }}</strong>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group field-organization-org_address_line1 required">
                        <label class="control-label" for="address">Address</label>
                        <textarea id="address" class="form-control" name="address" maxlength="255" aria-required="true">
                                </textarea>
                        <div class="help-block">
                            @if ($errors->has('address'))
                                <strong>{{ $errors->first('address') }}</strong>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group field-organization-org_address_line2 required">
                        <label class="control-label" for="house">House</label>
                        <input id="house" class="form-control" name="house" maxlength="255" aria-required="true">
                        <div class="help-block">
                            @if ($errors->has('house'))
                                <strong>{{ $errors->first('house') }}</strong>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group field-organization-org_phone required">
                        <label class="control-label" for="street">Street</label>
                        <input type="text" id="street" class="form-control" name="street" value="" maxlength="25" aria-required="true">

                        <div class="help-block">
                            @if ($errors->has('street'))
                                <strong>{{ $errors->first('street') }}</strong>
                            @endif
                        </div>
                    </div>      </div>
                <div class="col-sm-6">
                    <div class="form-group field-organization-org_website required">
                        <label class="control-label" for="country">Country</label>
                        <select type="text" id="country_id" class="form-control" name="country_id" maxlength="60"  aria-required="true">
                            <option value="">-- Country Name --</option>
                            @foreach($countries as $country)
                                <option value="{{$country->id}}">{{$country->name}}</option>
                            @endforeach

                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group field-organization-org_website required">
                        <label class="control-label" for="state">City</label>
                        <select type="text" id="state_id" class="form-control" name="state_id" maxlength="60"  aria-required="true">
                            <option value="">--City Name --</option>
                            @foreach($states as $state)
                                <option value="{{$state->id}}">{{$state->name}}</option>
                            @endforeach
                        </select>
                        <div class="help-block">
                            @if ($errors->has('state_id'))
                                <strong>{{ $errors->first('state_id') }}</strong>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group required">
                        <label class="control-label" for="city">Area</label>
                        <select type="text" id="city_id" class="form-control" name="city_id" maxlength="60"  aria-required="true">
                            <option value="">--Area Name --</option>
                            @foreach($cities as $city)
                                <option value="{{$city->id}}">{{$city->name}}</option>
                            @endforeach

                        </select>


                        <div class="help-block">
                            @if ($errors->has('city_id'))
                                <strong>{{ $errors->first('city_id') }}</strong>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group field-organization-zip required">
                        <label class="control-label" for="zip">Zip</label>
                        <input type="text" class="form-control" id="zip" name="zip" aria-required="true">
                        <div class="help-block">
                            @if ($errors->has('zip'))
                                <strong>{{ $errors->first('zip') }}</strong>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group field-organization-phone required">
                        <label class="control-label" for="phone">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" aria-required="true">
                        <div class="help-block">
                            @if ($errors->has('phone'))
                                <strong>{{ $errors->first('phone') }}</strong>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="admin-name">Campus Admin Name:</label>
                        <input id="admin-name" type="text" class="form-control" name="campus-admin-name" required>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="admin-email">Campus Admin Email:</label>
                        <input id="admin-email" type="email" class="form-control" name="email" required>
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
        $("#campus-add-form").validate({
            rules: {
                name: "required",
                campus_code: "required",
                address:"required",
                house:'required',
                city:'required',

                state: "required",
                zip: "required",
                country:"required",
                phone: {
                    required: true,
                    number: true
                },
            },
            messages: {
                name: "Please enter campus name",
                campus_code: "Please enter campus code",
                address:"Please enter address",
                house:"Please enter house",
                state: "Please enter state",
                zip: "Please enter zip",
                country:"Please enter address",
                phone:"Please enter mobile number and only number is allowed",
                state:"Please enter state",


            }
        });
    });

</script>
