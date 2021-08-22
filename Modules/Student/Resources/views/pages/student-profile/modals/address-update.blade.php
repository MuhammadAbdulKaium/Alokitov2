<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title">
        <i class="fa fa-info-circle"></i> Address Details
    </h4>
</div>

@if($presentAddress && $permanentAddress)
    <form id="address-update-form" name="address-update-form" action="{{url('/student/profile/address/update', [$personalInfo->id])}}" method="POST">
        @else
            <form id="address-update-form" name="address-update-form" action="{{url('/student/profile/address/store', [$personalInfo->user()->id])}}" method="POST">
                @endif
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="modal-body" style="overflow:auto;max-height:600px">
                    <!--Start current address block-->
                    <h2 class="page-header edusec-page-header-2 edusec-border-bottom-warning">
                        Current Address
                    </h2>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label" for="present_address">Address</label>
                                <textarea id="present_address" class="form-control" name="present_address" maxlength="255" required>@if($presentAddress){{$presentAddress->address}}@endif</textarea>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="present_country">Country</label>
                                <select id="present_country" class="form-control presentCountry" name="present_country" required onchange="">
                                    <option value="">--- Select Country ---</option>
                                    @if($presentAddress)
                                        @foreach($allCountry as $country)
                                            <option value="{{$country->id}}" @if($presentAddress->country_id == $country->id) Selected @endif>{{$country->name}}</option>
                                        @endforeach
                                    @else
                                        @foreach($allCountry as $country)
                                            <option value="{{$country->id}}">{{$country->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="present_state">City</label>
                                <select id="present_state" class="form-control presentState" name="present_state" required onchange="">
                                    <option value="">--- Select City ---</option>
                                    @if($presentAddress)
                                        @foreach($presentAddress->country()->allState() as $state)
                                            <option value="{{$state->id}}" @if($presentAddress->state_id == $state->id) Selected @endif>{{$state->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="present_city">Area</label>
                                <select id="present_city" class="form-control presentCity" name="present_city" required>
                                    <option value="">--- Select Area ---</option>
                                   {{-- @if($presentAddress)
                                        @if($presentAddress->state() != false)
                                            @foreach($presentAddress->state()->allCity() as $city)
                                                <option value="{{$city->id}}" @if($presentAddress->city_id == $city->id) Selected @endif>{{$city->name}}</option>
                                            @endforeach
                                        @endif
                                    @endif--}}
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="present_zipcode">Zip Code</label>
                                <input id="present_zipcode" class="form-control" name="present_zipcode" maxlength="6" type="text" value="@if($presentAddress){{$presentAddress->zip}}@endif" required>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="present_house">House No</label>
                                <input id="present_house" class="form-control" name="present_house" maxlength="25" type="text" value="@if($presentAddress){{$presentAddress->house}}@endif" required>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="present_phone">Phone No</label>
                                <input id="present_phone" class="form-control" name="present_phone" maxlength="25" type="text" value="@if($presentAddress){{$presentAddress->phone}}@endif" required>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="present_house">গ্রাম/মহল্লা:</label>
                                <input id="bn_village" class="form-control" name="bn_village"  type="text" value="@if($presentAddress){{$presentAddress->bn_village}}@endif">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="present_phone">ডাকঘর</label>
                                <input id="bn_postoffice" class="form-control" name="bn_postoffice"  type="text" value="@if($presentAddress){{$presentAddress->bn_postoffice}}@endif">
                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="present_phone">উপজেলা: </label>
                                <input id="bn_upzilla" class="form-control" name="bn_upzilla" type="text" value="@if($presentAddress){{$presentAddress->bn_upzilla}}@endif">
                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="present_phone">জেলা:</label>
                                <input id="bn_zilla" class="form-control" name="bn_zilla" type="text" value="@if($presentAddress){{$presentAddress->bn_zilla}}@endif">
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>



                    <!--Start permanent address block-->
                    <h2 class="page-header edusec-page-header-2 edusec-border-bottom-warning">Permanent Address</h2>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label" for="permanent_address">Address</label>
                                <textarea id="permanent_address" class="form-control" name="permanent_address" maxlength="255" required>@if($permanentAddress){{$permanentAddress->address}}@endif</textarea>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="permanent_country">Country</label>
                                <select id="permanent_country" class="form-control permanentCountry" name="permanent_country" required onchange="">
                                    <option value="">--- Select Country ---</option>
                                    @if($permanentAddress)
                                        @foreach($allCountry as $country)
                                            <option value="{{$country->id}}" @if($permanentAddress->country_id == $country->id) Selected @endif>{{$country->name}}</option>
                                        @endforeach
                                    @else
                                        @foreach($allCountry as $country)
                                            <option value="{{$country->id}}">{{$country->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="permanent_state">City</label>
                                <select id="permanent_state" class="form-control permanentState" name="permanent_state" required onchange="">
                                    <option value="">--- Select City ---</option>
                                   {{-- @if($permanentAddress)
                                        @if($permanentAddress->state() != false)
                                            @foreach($permanentAddress->country()->allState() as $state)
                                                <option value="{{$state->id}}" @if($permanentAddress->state_id == $state->id) Selected @endif>{{$state->name}}</option>
                                            @endforeach
                                        @endif
                                    @endif--}}
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="permanent_city">Area</label>
                                <select id="permanent_city" class="form-control permanentCity" name="permanent_city" required>
                                    <option value="">--- Select Area ---</option>
                                    @if($permanentAddress)
                                        @if($permanentAddress->state())
                                        @foreach($permanentAddress->state()->allCity() as $city)
                                            <option value="{{$city->id}}" @if($permanentAddress->city_id == $city->id) Selected @endif>{{$city->name}}</option>
                                        @endforeach
                                        @endif
                                    @endif
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="permanent_zipcode">Zip Code</label>
                                <input id="permanent_zipcode" class="form-control" name="permanent_zipcode" maxlength="6" type="text" value="@if($permanentAddress){{$permanentAddress->zip}}@endif" required>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="permanent_house">House No</label>
                                <input id="permanent_house" class="form-control" name="permanent_house" maxlength="25" type="text" value="@if($permanentAddress){{$permanentAddress->house}}@endif" required>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="permanent_phone">Phone No</label>
                                <input id="permanent_phone" class="form-control" name="permanent_phone" maxlength="25" type="text" value="@if($permanentAddress){{$permanentAddress->phone}}@endif" required>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="present_house">গ্রাম/মহল্লা:</label>
                                <input id="bn_village" class="form-control" name="bn_village"  type="text" value="@if($permanentAddress){{$permanentAddress->bn_village}}@endif">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="present_phone">ডাকঘর</label>
                                <input id="bn_postoffice" class="form-control" name="bn_postoffice"  type="text" value="@if($permanentAddress){{$permanentAddress->bn_postoffice}}@endif">
                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="present_phone">উপজেলা: </label>
                                <input id="bn_upzilla" class="form-control" name="bn_upzilla" type="text" value="@if($permanentAddress){{$permanentAddress->bn_upzilla}}@endif">
                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="present_phone">জেলা:</label>
                                <input id="bn_zilla" class="form-control" name="bn_zilla" type="text" value="@if($permanentAddress){{$permanentAddress->bn_zilla}}@endif">
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>

                </div>
                <!--./modal-body-->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info">Update</button>
                    <a class="btn btn-default pull-right" href="#" data-dismiss="modal">Cancel</a>
                </div>
                <!--./modal-footer-->
            </form>

            <script type ="text/javascript">
                jQuery(document).ready(function () {

                    jQuery("form[name='address-update-form']").validate({
                        // Specify validation rules
                        rules: {
                            present_address: "required",
                            present_country: "required",
                            present_state: "required",
                            present_city: "required",
                            present_house: "required",
                            present_phone :"required",
                            present_zipcode: "required",
                            permanent_address: "required",
                            permanent_country: "required",
                            permanent_state: "required",
                            permanent_city: "required",
                            permanent_house: "required",
                            permanent_phone:"required",
                    permanent_zipcode: "required",
                },

                    // Specify validation error messages
                    messages: {
                        present_address: "Please enter present address",
                            present_country: "Please select present Country",
                            present_state: "Please select present State",
                            present_city: "Please select present City",
                            present_house: "Please enter present House No.",
                            present_phone: "required",
                            present_zipcode: "required",
                            permanent_address: "Please enter permanent address",
                            permanent_country: "Please select permanent Country",
                            permanent_state: "Please select permanent State",
                            permanent_city: "Please select permanent City",
                            permanent_house: "Please enter permanent House No.",
                            permanent_phone: "required",
                            permanent_zipcode: "required",
                    },

                    submitHandler: function(form) {
                        form.submit();
                    }
                });



                    // request state list for the selelcted prsent country
                    jQuery(document).on('change','.presentCountry',function(){
                        alert(100);
                        //console.log("hmm its change");

                        // get country id
                        var country_id = $(this).val();
                        var div = $(this).parent();
                        var op="";

                        $.ajax({
                            url: "{{ url('/setting/find/state') }}",
                            type: 'GET',
                            cache: false,
                            data: {'id': country_id }, //see the $_token
                            datatype: 'application/json',

                            beforeSend: function() {
                                console.log(country_id);

                            },

                            success:function(data){
                                //console.log('success');

                                //console.log(data.length);
                                op+='<option value="0" selected disabled>--- Select State ---</option>';
                                for(var i=0;i<data.length;i++){
                                    // console.log(data[i].level_name);
                                    op+='<option value="'+data[i].id+'">'+data[i].name+'</option>';
                                }

                                // set value to the academic batch
                                $('.presentState').html("");
                                $('.presentState').append(op);

                                // set value to the academic batch
                                $('.presentCity').html("");
                                $('.presentCity').append('<option value="" selected disabled>--- Select City ---</option>');
                            },

                            error:function(){

                            }
                        });
                    });


                    // request state list for the selelcted prsent country
                    jQuery(document).on('change','.presentState',function(){
                        // console.log("hmm its change");

                        // get country id
                        var state_id = $(this).val();
                        var div = $(this).parent();
                        var op="";

                        $.ajax({
                            url: "{{ url('/setting/find/city/') }}",
                            type: 'GET',
                            cache: false,
                            data: {'id': state_id }, //see the $_token
                            datatype: 'application/json',

                            beforeSend: function() {
                                console.log('state_id');

                            },

                            success:function(data){
                                console.log('success');

                                //console.log(data.length);
                                op+='<option value="0" selected disabled>--- Select City ---</option>';
                                for(var i=0;i<data.length;i++){
                                    // console.log(data[i].level_name);
                                    op+='<option value="'+data[i].id+'">'+data[i].name+'</option>';
                                }

                                // set value to the academic batch
                                $('.presentCity').html("");
                                $('.presentCity').append(op);
                            },

                            error:function(){

                            }
                        });
                    });


                    // request state list for the selelcted prsent country
                    jQuery(document).on('change','.permanentCountry',function(){
                        console.log("hmm its change");

                        // get country id
                        var country_id = $(this).val();
                        var div = $(this).parent();
                        var op="";

                        $.ajax({
                            url: "{{ url('/setting/find/state') }}",
                            type: 'GET',
                            cache: false,
                            data: {'id': country_id }, //see the $_token
                            datatype: 'application/json',

                            beforeSend: function() {
                                console.log(country_id);

                            },

                            success:function(data){
                                //console.log('success');

                                //console.log(data.length);
                                op+='<option value="0" selected disabled>--- Select State ---</option>';
                                for(var i=0;i<data.length;i++){
                                    // console.log(data[i].level_name);
                                    op+='<option value="'+data[i].id+'">'+data[i].name+'</option>';
                                }

                                // set value to the academic batch
                                $('.permanentState').html("");
                                $('.permanentState').append(op);

                                // set value to the academic batch
                                $('.permanentCity').html("");
                                $('.permanentCity').append('<option value="" selected disabled>--- Select City ---</option>');
                            },

                            error:function(){

                            }
                        });
                    });


                    // request state list for the selelcted prsent country
                    jQuery(document).on('change','.permanentState',function(){
                        //console.log("hmm its change");

                        // get country id
                        var state_id = $(this).val();
                        var div = $(this).parent();
                        var op="";

                        $.ajax({
                            url: "{{ url('/setting/find/city/') }}",
                            type: 'GET',
                            cache: false,
                            data: {'id': state_id }, //see the $_token
                            datatype: 'application/json',

                            beforeSend: function() {
                                console.log('state_id');

                            },

                            success:function(data){
                                //console.log('success');

                                //console.log(data.length);
                                op+='<option value="0" selected disabled>--- Select City ---</option>';
                                for(var i=0;i<data.length;i++){
                                    // console.log(data[i].level_name);
                                    op+='<option value="'+data[i].id+'">'+data[i].name+'</option>';
                                }

                                // set value to the academic batch
                                $('.permanentCity').html("");
                                $('.permanentCity').append(op);
                            },

                            error:function(){

                            }
                        });
                    });



                });

            </script>
