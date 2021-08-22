
@extends('reports::layouts.report-layout')
<!-- page content -->
@section('page-content')
    @php  $batchString="Class" @endphp
	<!-- grading scale -->
	<div class="col-md-12" style="padding: 0px">
		<h4 class="box-title"><i class="fa fa-filter"></i> Search</h4>
		<div class="box box-solid">
			<form id="InvoiceSearch"  method="get">
				<input type="hidden" name="_token" value="{{csrf_token()}}">
				<div class="col-sm-2">
						<div class="form-group field-batches-batch_academic_year_id required">
							<label class="control-label" for="batches-batch_academic_year_id">Academic Year</label>
							<select id="academic_year" class="academicYear form-control" required name="academic_year" aria-required="true">
								<option value="">--- Select Academic Year ---</option>
								@if(!empty($academicYear))
									@foreach($academicYear as $year )
										<option class="YearId" value="{{$year->id}}">{{$year->year_name}}</option>
									@endforeach;
								@endif
							</select>

							<div class="help-block">

							</div>
						</div>
				</div>

					<div class="col-sm-2">
						<div class="form-group field-batches-batch_course_id ">
							<label class="control-label" for="batches-batch_course_id">Academic Level</label>
							<select id="academic_level" class="form-control academicLevel" name="academic_level" >
								<option value="" disabled="true" selected="true">--- Select Level ---</option>
							</select>

							<div class="help-block"></div>
						</div>
					</div>

						<div class="col-sm-2">
							<div class="form-group">
								<label class="control-label" for="batch">{{$batchString}}</label>
								<select id="batch" class="form-control academicBatch" name="batch" onchange="">
									<option value="" selected disabled>--- Select {{$batchString}} ---</option>
								</select>
								<div class="help-block"></div>
							</div>
						</div>
						<div class="col-sm-2">
							<div class="form-group">
								<label class="control-label" for="section">Section</label>
								<select id="section" class="form-control academicSection" name="section">
									<option value="" selected disabled>--- Select Section ---</option>
								</select>
								<div class="help-block"></div>
							</div>
						</div>




						<div class="col-sm-2">
							<div class="form-group field-feespaymenttransactionsearch-fp_sdate required ">
								<label class="control-label" for="feespaymenttransactionsearch-fp_sdate">Start Date</label>
								<input type="text"  id="search_start_date" class="form-control" required  value="@if(!empty($allInputs->search_start_date)) {{date('d-m-Y',strtotime($allInputs->search_start_date))}}  @endif" name="search_start_date" placeholder="Start Date">
								<div class="help-block"></div>
							</div>			</div>

						<div class="col-sm-2">
							<div class="form-group field-feespaymenttransactionsearch-fp_edate required">
								<label class="control-label" for="feespaymenttransactionsearch-fp_edate">End Date</label>
								<input type="text" id="search_end_date" class="form-control" required name="search_end_date"   value="@if(!empty($allInputs->search_end_date)) {{date('d-m-Y',strtotime($allInputs->search_end_date))}}  @endif" placeholder="Start Date">


								<div class="help-block"></div>
							</div>			</div>


                        <div class="col-sm-2">
                            <div class="form-group field-feespaymenttransactionsearch-invoice_type required">
                                <label class="control-label" for="invoice_type-invoice_type">Invoice Type</label>
                                <select id="invoice_type" class="form-control" name="invoice_type" required>
                                    <option value="">Select Invoice Type</option>
                                    <option value="5" @if (!empty($allInputs) && ($allInputs->invoice_type =="5")) selected="selected" @endif>All</option>
                                    <option value="1" @if (!empty($allInputs) && ($allInputs->invoice_type =="1")) selected="selected" @endif >Fees Invoice</option>
                                    <option value="2" @if (!empty($allInputs) && ($allInputs->invoice_type =="2")) selected="selected" @endif >Attendance Invoice</option>
                                </select>

                                <div class="help-block"></div>
                            </div>
                        </div>

						<div class="col-sm-2">
							<div class="form-group field-feespaymenttransactionsearch-fees_pay_tran_mode required">
								<label class="control-label" for="feespaymenttransactionsearch-fees_pay_tran_mode">Payment Status</label>
								<select id="invoice_status" class="form-control" name="invoice_status" required>
									<option value="">Select Payment</option>
									<option value="5" @if (!empty($allInputs) && ($allInputs->invoice_status =="5")) selected="selected" @endif>All</option>
									<option value="1" @if (!empty($allInputs) && ($allInputs->invoice_status =="1")) selected="selected" @endif>Paid</option>
									<option value="2" @if (!empty($allInputs) && ($allInputs->invoice_status =="2")) selected="selected" @endif >Un Paid</option>
									<option value="4" @if (!empty($allInputs) && ($allInputs->invoice_status =="4")) selected="selected" @endif >Cancel</option>
								</select>

								<div class="help-block"></div>
							</div>
						</div>
					</div>




						</div>
					</div>
					<div class="box-footer">
						<button type="submit"  class="btn btn-primary btn-create">Search</button>
						<button type="reset"  class="btn btn-success btn-create">Reset</button>
					</div>


			</form>

			{{--//  Start advance search here from--}}

			{{--//  End advance search here from--}}

		</div>

	<div id="searchRresult">

			</div>

@endsection

@section('page-script')
		{{--<script>--}}


            // invoice serach  request
            $('form#InvoiceSearch').on('submit', function (e) {
                e.preventDefault();


                // ajax request
                $.ajax({

                    url: '/reports/invoice/search',
                    type: 'GET',
                    cache: false,
                    data: $('form#InvoiceSearch').serialize(),
                    datatype: 'json/application',

                    beforeSend: function() {
						{{--alert($('form#PayerStudent').serialize());--}}
                    },

                    success:function(data){
                       $("#searchRresult").html("");
                       $("#searchRresult").append(data);

                    },

                    error:function(data){
                        {{--alert(JSON.stringify(data));--}}
                    }
                });

				});





            // Invoice status Cancel Ajax Request
        $('.cancelInvoice').click(function() {
            var invoiceId= $(this).attr('id')

            // ajax request
            $.ajax({

                url: '/fees/invoice/update_status/'+invoiceId,
                type: 'GET',
                cache: false,
                beforeSend: function() {
					{{--alert($('form#Partial_allowForm').serialize());--}}
                },

                success:function(data){
                    $('#'+invoiceId).hide();
                    $('#unPainInvoiceStatus'+invoiceId).hide();
                    $('#cancelInvoiceStatus'+invoiceId).show();
                },

                error:function(data){
                    alert(JSON.stringify(data));
                }
            });

        });




        // invoice delete ajax request
        $('.delete_class').click(function(){
            var tr = $(this).closest('tr'),
                del_id = $(this).attr('id');

            $.ajax({
                url: "invoice/delete/"+ del_id,
                type: 'GET',
                cache: false,
                success:function(result){
                    tr.fadeOut(1000, function(){
                        $(this).remove();
                    });
                }
            });
        });


        $('#search_start_date').datepicker({"changeMonth":true,"changeYear":true,"dateFormat":"dd-mm-yy"});
        $('#search_end_date').datepicker({"changeMonth":true,"changeYear":true,"dateFormat":"dd-mm-yy"});


				// academic Year select ajax request
            $(".academicYear").on('change',function(){

                // get Year Id and find academic Level

                // get academic year id
                var year_id = $(this).val();
                var div = $(this).parent();
                var op="";

                $.ajax({
                    url: "{{ url('/academics/find/level') }}",
                    type: 'GET',
                    cache: false,
                    data: {'id': year_id }, //see the $_token
                    datatype: 'application/json',
                    beforeSend: function() {
                    },

                    success:function(data){

                        op+='<option value="0" selected disabled>--- Select Level ---</option>';
                        for(var i=0;i<data.length;i++){
                            // console.log(data[i].level_name);
                            op+='<option value="'+data[i].id+'">'+data[i].level_name+'</option>';
                        }

                        // set value to the academic batch
                        $('.academicLevel').html("");
                        $('.academicLevel').append(op);
                    },
                    error:function(){

                    }
                });

            });


    $(document).on('change','.academicLevel',function(){
        // get academic level id
        var level_id = $(this).val();
        var div = $(this).parent();
        var op="";

        $.ajax({
            url: "{{ url('/academics/find/batch') }}",
            type: 'GET',
            cache: false,
            data: {'id': level_id }, //see the $_token
            datatype: 'application/json',

            beforeSend: function() {
                // statements
            },
            success:function(data){
                op+='<option value="" selected disabled>--- Select Batch ---</option>';
                for(var i=0;i<data.length;i++){
                    op+='<option value="'+data[i].id+'">'+data[i].batch_name+'</option>';
                }
                // set value to the academic batch
                $('.academicBatch').html("");
                $('.academicBatch').append(op);
                // set value to the academic secton
                $('.academicSection').html("");
                $('.academicSection').append('<option value="0" selected disabled>--- Select Section ---</option>');

                $('.academicSubject').html("");
                $('.academicSubject').append('<option value="" selected disabled>--- Select Subject ---</option>');

                $('#assessment_table_row').html('');
                // semester reset
                $('.academicSemester option:first').prop('selected', true);
            },
            error:function(){
                // statements
            }
        });
    });


    // request for section list using batch id
    $(document).on('change','.academicBatch',function(){
        // get academic level id
        var batch_id = $(this).val();
        var div = $(this).parent();
        var op="";

        $.ajax({
            url: "{{ url('/academics/find/section') }}",
            type: 'GET',
            cache: false,
            data: {'id': batch_id }, //see the $_token
            datatype: 'application/json',

            beforeSend: function() {
                // statements
            },

            success:function(data){
                op+='<option value="" selected disabled>--- Select Section ---</option>';
                for(var i=0;i<data.length;i++){
                    op+='<option value="'+data[i].id+'">'+data[i].section_name+'</option>';
                }
                // set value to the academic batch
                $('.academicSection').html("");
                $('.academicSection').append(op);

                $('.academicSubject').html("");
                $('.academicSubject').append('<option value="" selected disabled>--- Select Subject ---</option>');

                $('#assessment_table_row').html('');
                // semester reset
                $('.academicSemester option:first').prop('selected', true);
            },
            error:function(){
                // statements
            },
        });
    });



        // ajax pagination

        $('body').on('click', '.pagination a', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        getinvoiceReport(url);
        {{--window.history.pushState("", "", url);--}}
        });

        function getinvoiceReport(url) {
        $.ajax({
        url : url
        }).done(function (data) {

        $('#searchRresult').html('');
        $('#searchRresult').append(data);
        {{--$('#batch_payment_list_row').html('');--}}
        {{--$('#batch_payment_list_row').append(data);--}}
        {{--//                alert("sdfsad");--}}

        }).fail(function () {
             {{--alert('Articles could not be loaded.');--}}
        });
        }



@endsection
