
<style>
	.applicant-list{ cursor: pointer;}
</style>

<div id="applicant_result_container" class="box box-solid">

	<div class="box-body" style="overflow-x:inherit">
		<p class="bg-blue-gradient text-center text-bold">Applicant Result Sheet</p>
		@if($examTaken==1)
		<ul id="applicant_result_list_btn_container" class="nav-tabs nav {{$applicantResultSheet->count()>0?'':'hide'}}">
			<li class="active list"><a id="LIST" class="applicant-list">Applicants List</a></li>
			<li class="list"><a id="PASSED" class="applicant-list">Passed</a></li>
			<li class="list"><a id="FAILED" class="applicant-list">Failed</a></li>
			<li class="list"><a id="MERIT" class="applicant-list">Merit</a></li>
			<li class="list"><a id="WAITING" class="applicant-list">Waiting</a></li>
			<li class="list"><a id="APPROVED" class="applicant-list">Approved</a></li>
			<li class="list"><a id="DISAPPROVED" class="applicant-list">Disapproved</a></li>
			<li class="list"><a id="ADMITTED" class="applicant-list">Admitted</a></li>
		</ul>
		@endif

		<div class="row box-body">
			<div id="applicant_result_list_container" class="col-md-12">
				@if($examTaken==1)
					@if($applicantResultSheet->count()>0)
						@include('admission::admission-assessment.modals.result-list')
					@else
						<div class="text-center">
							<p id="generate_applicant_result" class="btn btn-primary">Generate Result</p>
						</div>
					@endif
				@else
					<div class="text-center">
						<p class="btn btn-default text-bold">
							Exam has not been taken or Result has not been published.
						</p>
					</div>
				@endif
			</div>
		</div>
	</div> <!-- /.box-body -->
</div>


<script>
    $(function () {
        $('.applicant-list').click(function () {
            var list_type = $(this).attr('id');
            // ajax request
            $.ajax({
                type: 'GET',
                cache: false,
                url: '/admission/assessment/result/list',
                data: {
                    'academic_year': $('#academic_year').val() ,
                    'academic_level': $('#academic_level').val() ,
                    'batch': $('#batch').val() ,
                    'request_list_type': list_type,
                    'my_page': $('#my_page').val(),
                },
                datatype: 'html',

                beforeSend: function() {
                    // show waiting dialog
                    waitingDialog.show('Loading...');
                },

                success: function (data) {
                    // statements
                    var applicant_result_list_container=  $('#applicant_result_list_container');
                    applicant_result_list_container.html(null);
                    applicant_result_list_container.append(data);
                    waitingDialog.hide();
                },

                error:function(data){
                    alert(JSON.stringify(data));
                }
            });

            // applicant result list btn active action
            $('.list').each(function () {
                $(this).removeClass('active');
            });
            $('#'+list_type).parent().addClass('active');
        });

        // generate applicant result
        $('#generate_applicant_result').click(function () {
            // ajax request
            $.ajax({
                type: 'GET',
                cache: false,
                url: '/admission/assessment/result/manage',
                data: {
                    'academic_year': $('#academic_year').val() ,
                    'academic_level': $('#academic_level').val() ,
                    'batch': $('#batch').val() ,
                    'request_type': 'generate'
                },
                datatype: 'html',

                beforeSend: function() {
                    // show waiting dialog
                    waitingDialog.show('Loading...');
                },

                success: function (data) {
                    // statements
                    $('#applicant_result_list_btn_container').removeClass('hide');
                    var applicant_result_list_container=  $('#applicant_result_list_container');
                    applicant_result_list_container.html('');
                    applicant_result_list_container.append(data);
                    waitingDialog.hide();
                },

                error:function(data){
                    alert(JSON.stringify(data));
                }
            });
        });


        $('form#applicant_promote_form').on('submit', function () {
            if(verifyStdList()){

                if($('#promote_action').val()){
                    $(this)
                        .append('<input type="hidden" name="academic_year" value="'+$('#academic_year').val()+'"/>')
                        .append('<input type="hidden" name="academic_level" value="'+$('#academic_level').val()+'"/>')
                        .append('<input type="hidden" name="my_page" value="'+$('#my_page').val()+'"/>')
                        .append('<input type="hidden" name="batch" value="'+$('#batch').val()+'"/>');

                    // ajax request
                    $.ajax({
                        type: 'POST',
                        cache: false,
                        url: '/admission/assessment/result/promote',
                        data: $('form#applicant_promote_form').serialize(),
                        datatype: 'html',

                        beforeSend: function() {
                            // show waiting dialog
                            waitingDialog.show('Loading...');
                        },

                        success: function (data) {
                            // statements
                            var applicant_result_list_container =  $('#applicant_result_list_container');
                            applicant_result_list_container.html('');
                            applicant_result_list_container.append(data);
                            waitingDialog.hide();
                        },

                        error:function(data){
                            alert(JSON.stringify(data));
                        }
                    });
                    return false
                }else{
                    alert('Please Select a Promo Action Type');
                    return false
                }
            }else{
                alert('Please select one or more items from the list.');
                return false
            }
        });

        // check_all_applicant
        $("#check_all_applicant").click(function () {
            if($(this).is(':checked')){
                $(".applicant_list").each(function () {
                    $(this).prop('checked', true);
                });
            }else{
                $(".applicant_list").each(function () {
                    $(this).prop("checked", false);
                });
            }
        });

        // verify check_all_applicant
        function verifyStdList() {
            var stdListSelected = null;
            $(".applicant_list").each(function () {
                if($(this).is(':checked')){
                    stdListSelected = "checked";
                    return false;
                }
            });
            return (stdListSelected=="checked")?'true':'false';
        }
    });
</script>
