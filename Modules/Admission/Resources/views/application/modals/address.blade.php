<div class="modal-header">
	<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
	<h4 class="modal-title">
		<i class="fa fa-info-circle"></i> Update Address
	</h4>
</div>
<form id="applicant_address_update_form" action="{{'/admission/applicant/address/'.$addressProfile->id.'/update'}}" method="POST">
	<input type="hidden" name="_token" value="{{csrf_token()}}">
	<div class="modal-body" style="overflow:auto;max-height:600px">
		<!--Start current address block-->
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<label class="control-label" for="address">Address</label>
					<textarea id="address" class="form-control" name="address" maxlength="255" required>{{$addressProfile->address}}</textarea>
					<div class="help-block"></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label" for="country">Country</label>
					<select id="country" class="form-control country" name="country" required>
						<option value="">--- Select Country ---</option>
						@foreach($allCountry as $country)
							<option value="{{$country->id}}" {{$addressProfile->country_id == $country->id?'Selected':''}}>{{$country->name}}</option>
						@endforeach
					</select>
					<div class="help-block"></div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label" for="state">State</label>
					<select id="state" class="form-control state" name="state" required onchange="">
						<option value="">--- Select State ---</option>
						@foreach($addressProfile->country()->allState() as $state)
							<option value="{{$state->id}}" {{$addressProfile->state_id == $state->id?'Selected':''}}>{{$state->name}}</option>
						@endforeach
					</select>
					<div class="help-block"></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label" for="city">City</label>
					<select id="city" class="form-control city" name="city" required>
						<option value="">--- Select City ---</option>
						@foreach($addressProfile->state()->allCity() as $city)
							<option value="{{$city->id}}" {{$addressProfile->city_id == $city->id?'Selected':''}}>{{$city->name}}</option>
						@endforeach
					</select>
					<div class="help-block"></div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label" for="zip">Zip Code</label>
					<input id="zip" class="form-control" name="zip" maxlength="6" type="text" value="{{$addressProfile->zip}}" required>
					<div class="help-block"></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label" for="house">House No</label>
					<input id="house" class="form-control" name="house" maxlength="25" type="text" value="{{$addressProfile->house}}" required>
					<div class="help-block"></div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label" for="phone">Phone No</label>
					<input id="phone" class="form-control" name="phone" maxlength="25" type="text" value="{{$addressProfile->phone}}" required>
					<div class="help-block"></div>
				</div>
			</div>
		</div>
	</div>
	<!--./modal-body-->
	<div class="modal-footer">
		<button type="submit" class="btn btn-info pull-left">Update</button>
		<a class="btn btn-default pull-right" href="#" data-dismiss="modal">Cancel</a>
	</div>
	<!--./modal-footer-->
</form>

<script type ="text/javascript">
    jQuery(document).ready(function () {
        // request state list for the selelcted prsent country
        jQuery(document).on('change','.country',function(){
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
                    $('.state').html("");
                    $('.state').append(op);

                    // set value to the academic batch
                    $('.city').html("");
                    $('.city').append('<option value="" selected disabled>--- Select City ---</option>');
                },

                error:function(){

                }
            });
        });


        // request state list for the selelcted prsent country
        jQuery(document).on('change','.state',function(){
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
                    $('.city').html("");
                    $('.city').append(op);
                },

                error:function(){

                }
            });
        });

    });
</script>
