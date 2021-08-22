<form id="institute_billing_info_table" action="{{'/admin/billing/info/store'}}" method="POST">
	<input type="hidden" name="_token" value="{{csrf_token()}}" />
	<input type="hidden" id="billing_info_id" name="billing_info_id" value="{{$billingInfoProfile?$billingInfoProfile->id:0}}" />
	{{--modal header--}}
	<div class="modal-header">
		<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
		<h4 class="modal-title"><i class="fa fa-money"></i> {{$billingInfoProfile?'Update':'Add'}} Billing Info</h4>
	</div>
	{{--modal-body--}}
	<div class="modal-body">
		<div class="row">
			<div class="form-group col-sm-6">
				<label for="institute_id">Institute Name:</label>
				<select id="institute_id" class="form-control" name="institute_id" required>
					@if($instituteList->count()>0)
						{{--checking--}}
						@if($billingInfoProfile)
							{{--institute looping--}}
							@foreach($instituteList as $institute )
								{{--checking--}}
								@if($billingInfoProfile AND $institute->id == $billingInfoProfile->institute_id)
									<option value="{{ $institute->id }}">{{ $institute->institute_name }}</option>
								@endif
							@endforeach
						@else
							<option selected value="">Select an Institute</option>
							{{--institute looping--}}
							@foreach($instituteList as $institute )
								@php $x = 0; @endphp
								@foreach($institute->campus() as $cam)
									{{--checking--}}
									@if((array_key_exists($institute->id, $allBillingInfoArrayList)) && (array_key_exists($cam->id, $allBillingInfoArrayListByCampus)))
										@php $x++; @endphp
									@endif
								@endforeach
								@if( $x < count($institute->campus()))
									<option value="{{ $institute->id }}">{{ $institute->institute_name }}</option>
								@endif
							@endforeach
						@endif
					@endif
				</select>
			</div>

			<div class="form-group col-sm-6">
				<label for="campus_id">Campus Name:</label>
				<select id="campus_id" class="form-control" name="campus_id"  style="height: 29px; border-radius: 3px;" required>
					@if($instituteList->count()>0)
						@if($billingInfoProfile)
							@foreach($instituteList as $institute )
								@if($billingInfoProfile AND $institute->id == $billingInfoProfile->institute_id)
									@foreach($institute->campus() as $cam)
										@if($cam->id == $billingInfoProfile->campus_id)
											<option value="{{ $cam->id }}" selected>{{ $cam->name }}</option>
										@endif
									@endforeach										
								@endif
							@endforeach
						@endif
					@endif
				</select>
			</div>
		</div>

		<div class="row">
			<div class="form-group col-sm-6">
				<label for="deposited">Diposited</label>
				<input id="deposited" type="number" class="form-control" name="deposited" value="{{$billingInfoProfile?$billingInfoProfile->deposited:''}}" />
			</div>
			<div class="form-group col-sm-6">
			</div>
		</div>

		<div class="row">
			<div class="form-group col-sm-6">
				<label for="rate_per_student">Rate/Student</label>
				<input id="rate_per_student" type="number" class="form-control" name="rate_per_student" value="{{$billingInfoProfile?$billingInfoProfile->rate_per_student:''}}" required />
			</div>

			<div class="form-group col-sm-6">
				<label for="total_student">Total Student:</label>
				<input id="total_student" readonly class="form-control" name="total_students" value="" required />
			</div>

			<div class="form-group col-sm-6">
				<label for="total_amount">Total Amount / Month</label>
				<input id="total_amount" type="number" class="form-control" name="total_amount" value="{{$billingInfoProfile?$billingInfoProfile->total_amount:''}}" readonly required />
			</div>
			
			<div class="form-group col-sm-6">
				<label for="accepted_amount">Accepted Amount / Month:</label>
				<input id="accepted_amount" type="number" class="form-control" name="accepted_amount" value="{{$billingInfoProfile?$billingInfoProfile->accepted_amount:''}}" required />
			</div>
		</div>

		<div class="row">
			<div class="form-group col-sm-6">
				<label for="rate_per_sms">Rate Per SMS</label>
				<input id="rate_per_sms" type="number" placeholder="00.00" step="0.01" class="form-control" name="rate_per_sms" value="{{$billingInfoProfile?$billingInfoProfile->rate_per_sms:''}}" />
			</div>

			<div class="form-group col-sm-6">
				<label for="total_sms">Total SMS</label>
				<input id="total_sms" readonly class="form-control" name="total_sms" value="" />
			</div>

			<div class="form-group col-sm-6">
				<label for="total_sms_price">Total SMS Price</label>
				<input id="total_sms_price" type="number" placeholder="00.00" class="form-control" name="total_sms_price" value="{{$billingInfoProfile?$billingInfoProfile->total_sms_price:''}}" readonly />
			</div>

			<div class="form-group col-sm-6">
				<label for="accepted_sms_price">Accepted SMS Price</label>
				<input id="accepted_sms_price" type="number" placeholder="00.00" step="0.01" class="form-control" name="accepted_sms_price" value="{{$billingInfoProfile?$billingInfoProfile->accepted_sms_price:''}}" />
			</div>
		</div>

		<div class="row">
			<div class="form-group col-sm-6">
				<label for="year">Year</label>
				@if($billingInfoProfile)
					<select id="year" name="year" class="form-control" required>
						<option value="{{ $billingInfoProfile->year }}" selected>{{ $billingInfoProfile->year }}</option>
					</select>

				@else
					@php
						$years = range(1900, strftime("%Y", time()));
					@endphp
					<select id="year" name="year" class="form-control" required>
						@foreach($years as $year)
							@if($year == date("Y"))
								<option value="{{ $year }}" selected>{{ $year }}</option>
							@else
								<option value="{{ $year }}">{{ $year }}</option>
							@endif
						@endforeach
					</select>
				@endif
			</div>

			<div class="form-group col-sm-6">
				<label for="month">Month</label>
				@if($billingInfoProfile)
					<select id="month" name="month" class="form-control" required>
						<option value="{{ $billingInfoProfile->month }}" selected>{{ $billingInfoProfile->month }}</option>
					</select>

				@else
					@php
						$monArr = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
					@endphp	

					<select id="month" name="month" class="form-control" required>
						@for ($m=0; $m<12; $m++) 					
							@if($monArr[$m] == date("F"))
								<option value="{{ $monArr[$m] }}" selected>{{ $monArr[$m] }}</option>
							@else
								<option value="{{ $monArr[$m] }}">{{ $monArr[$m] }}</option>
							@endif
						@endfor
					</select>
				@endif
			</div>
		</div>
	</div>

	{{--modal-footer--}}
	<div class="modal-footer">
		<button id="billing_info_close_btn" data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
		<button id="billing_info_submit_btn" class="btn btn-primary pull-left" type="submit">Submit</button>
	</div>
</form>

<script>
    // select2 class initialization
    $('.select2').select2({

	});

    $('.select2.select3').select2({

	});

    @if($billingInfoProfile) setStudentsCountByEdit({{$billingInfoProfile->institute_id}}, {{$billingInfoProfile->campus_id}}); @endif
	@if($billingInfoProfile) setSmsCount( {{ $billingInfoProfile->institute_id }}, {{ $billingInfoProfile->campus_id }} ); @endif

    $("#rate_per_student").keyup(function() {
        // checking
        if($('#institute_id').val()) {
            // total student
            var student =  $('#total_student').val();
            // get per student rate
            var rate = $(this).val();
            // total amount to be pain
            var amount = (student*rate);
            // total amount value
            $('#total_amount').val(amount);
            // total amount value
            $('#accepted_amount').val(amount);
        } else {
            //alert('Please select a institute');
            swal("Warning", 'Please select a institute', "warning");
        }
    });

    $("#rate_per_sms").keyup(function(){
        if(($('#institute_id').val()) && ($('#campus_id').val())) {

            var sms =  $('#total_sms').val();

            var rate = $(this).val();
 
			if((sms == "") || (sms == null) ) {
				$('#total_sms_price').val(null);
				$('#accepted_sms_price').val(null).prop('disabled', true);
				$('#rate_per_sms').val(null).prop('disabled', true);
			}

			else {
				$('#rate_per_sms').prop('disabled', false);
				$('#accepted_sms_price').prop('disabled', false);

				var amount = (sms*rate);
				var amount = amount.toFixed(2);

				$('#total_sms_price').val(amount);

				$('#accepted_sms_price').val(amount);
			}
        } else {
            swal("Warning", 'Please select a institute and campus', "warning");
        }
    });

    jQuery(document).on('change','#institute_id',function() {
		$("#deposited").val(null);
		$("#rate_per_student").val(null);
		$("#total_student").val(null);
		$("#total_amount").val(null);
		$("#accepted_amount").val(null);
		$("#rate_per_sms").val(null).prop('disabled', false);
		$("#total_sms").val(null);
		$("#total_sms_price").val(null);
		$("#accepted_sms_price").val(null).prop('disabled', false);
        var institute_id = $(this).val();
		var year =  $("form#institute_billing_info_table #year option:selected").val();
		var month =  $("form#institute_billing_info_table #month option:selected").val();
		setCampus(institute_id, year, month);
    });

	jQuery(document).on('change','#campus_id',function() {
		var institute_id =  $("form#institute_billing_info_table #institute_id option:selected").val();
		var campus_id =  $(this).val();
        setStudentsCount(institute_id, campus_id);
		setSmsCount(institute_id, campus_id)
    });

	jQuery(document).on('change','#month',function() {
		var year =  $("form#institute_billing_info_table #year option:selected").val();
		var month =  $(this).val();
		setInstituteCampusByMonth(year, month);
    });

	jQuery(document).on('change','#year',function() {
		var month =  $("form#institute_billing_info_table #month option:selected").val();
		var year =  $(this).val();
		setInstituteCampusByMonth(year, month);
    });

    function setCampus(institute_id, year, month) 
	{
        // ajax request
        $.ajax({
            url: "{{ url('/api/institute/campus') }}",
            type: 'POST',
            cache: false,
            data: {'institute_id': institute_id, 'year': year, 'month': month, '_token': '{{csrf_token()}}'}, //see the $_token
            datatype: 'application/json',

            // on success
            success: function (data) {
				datas = data;
				$("#campus_id").empty();
				var x='';
				x += '<option selected value="">Select a Campus</option>'
				for(var i=0; i<datas.length; i++)
				{
					x += '<option value="'+datas[i].id+'">'+datas[i].name+'</option>'
				}
				$("#campus_id").append(x);
            },

            // on error
            error : function (data) {
                //alert('Unable to load data form server');
                swal("Error", 'Unable to load data form server', "error");
            }
        });
	}

    function setStudentsCount(institute_id, campus_id) {
        // ajax request
        $.ajax({
            url: "{{ url('/api/institute/studentCount') }}",
            type: 'POST',
            cache: false,
            data: {'institute_id': institute_id, 'campus_id': campus_id, '_token': '{{csrf_token()}}'}, //see the $_token
            datatype: 'application/json',

            // on success
            success: function (data) {
				$('#total_student').val(data);
				$("#deposited").val(null);
				$("#rate_per_student").val(null);
				$("#total_amount").val(null);
				$("#accepted_amount").val(null);
				$("#rate_per_sms").val(null).prop('disabled', false);
				$("#total_sms_price").val(null);
				$("#accepted_sms_price").val(null).prop('disabled', false);
            },

            // on error
            error : function (data) {
                //alert('Unable to load data form server');
                swal("Error", 'Unable to load data form server', "error");
            }
        });
    }

	function setStudentsCountByEdit(institute_id, campus_id) {
        // ajax request
        $.ajax({
            url: "{{ url('/api/institute/studentCount') }}",
            type: 'POST',
            cache: false,
            data: {'institute_id': institute_id, 'campus_id': campus_id, '_token': '{{csrf_token()}}'}, //see the $_token
            datatype: 'application/json',

            // on success
            success: function (data) {
				$('#total_student').val(data);
            },

            // on error
            error : function (data) {
                //alert('Unable to load data form server');
                swal("Error", 'Unable to load data form server', "error");
            }
        });
    }

    function setSmsCount(institute_id, campus_id) {
        // ajax request
        $.ajax({
            url: "{{ url('/api/institute/smscount') }}",
            type: 'POST',
            cache: false,
            data: {'institute_id': institute_id, 'campus_id': campus_id, '_token': '{{csrf_token()}}'}, //see the $_token
            datatype: 'application/json',

            // on success
            success: function (data) {
                $('#total_sms').val(data);
            },

            error : function (data) {
                swal("Error", 'Unable to load data form server', "error");
            }
        });
    }

	function setInstituteCampusByMonth(year, month) 
	{
        // ajax request
        $.ajax({
            url: "{{ url('/api/institute/institutecampusbymonth') }}",
            type: 'POST',
            cache: false,
            data: {'year': year, 'month': month, '_token': '{{csrf_token()}}'}, //see the $_token
            datatype: 'application/json',

            // on success
            success: function (data) {
				datas = data;
				//alert(JSON.stringify(datas));
				$("#institute_id").empty();
				$("#campus_id").empty();
				$("#deposited").val(null);
				$("#rate_per_student").val(null);
				$("#total_student").val(null);
				$("#total_amount").val(null);
				$("#accepted_amount").val(null);
				$("#rate_per_sms").val(null).prop('disabled', false);
				$("#total_sms").val(null);
				$("#total_sms_price").val(null);
				$("#accepted_sms_price").val(null).prop('disabled', false);
				var x='';
				x += '<option value="" selected>Select an Institute</option>'
				for(var i=0; i<datas.length; i++)
				{
					x += '<option value="'+datas[i].insid+'">'+datas[i].insname+'</option>'
				}
				$("#institute_id").append(x);
            },

            // on error
            error : function (data) {
                //alert('Unable to load data form server');
                swal("Error", 'Unable to load data form server', "error");
            }
        });
	}

	function setStudentCount(institute_id) {
        // ajax request
        $.ajax({
            url: "{{ url('/api/institute/student') }}",
            type: 'POST',
            cache: false,
            data: {'institute_id': institute_id, '_token': '{{csrf_token()}}'}, //see the $_token
            datatype: 'application/json',

            // on success
            success: function (data) {
                $('#total_student').val(data);
            },

            // on error
            error : function (data) {
                //alert('Unable to load data form server');
                swal("Error", 'Unable to load data form server', "error");
            }
        });


//        // submit institute billing information
//        $('#billing_info_submit_btn').click(function () {
//            // per student rate
//            var rate = $('#rate_per_student').val();
//            var total_amount = $('#total_amount').val();
//            var accepted_amount = $('#accepted_amount').val();
//
//            // checking
//            if(rate && total_amount && accepted_amount){
//
//                // ajax request
//                $.ajax({
//                    url: '/admin/billing/info/store',
//                    type: 'post',
//                    cache: false,
//                    data: $('form#institute_billing_info_table').serialize(),
//                    datatype: 'application/json',
//
//                    beforeSend: function() {
//                        // show waiting dialog
//                        waitingDialog.show('Submitting...');
//                    },
//
//                    success:function(data){
//                        // hide dialog
//                        waitingDialog.hide();
//                        // checking
//                        if(data.status=='success'){
//                            // billing_info_id
//                            $('#billing_info_id').val(data.billing_info_id);
//                            // sweet alert
//                            swal("Success", data.msg, "success");
//                        }else{
//                            // sweet alert
//                            swal("Warning", data.msg, "warning");
//                        }
//                    },
//                    error:function(data){
//                        // hide waiting dialog
//                        waitingDialog.hide();
//                        // sweet alert
//                        swal("Error", 'Unable to load data form server', "error");
//                    }
//                });
//            }else{
//                alert('Please double check all inputs.');
//            }
//
//        });
    }
</script>