
<style>
	.ui-autocomplete {z-index:2147483647;}
	.ui-autocomplete span.hl_results {background-color: #ffff66 ;}
</style>
<div class="modal-header">
	<button aria-label="Close" data-dismiss="modal" class="close" type="button">
		<span aria-hidden="true">×</span>
	</button>
	<h4 class="modal-title">
		<i class="fa fa-plus-square"></i> Add Guardian
	</h4>
</div>
<form id="std_parent_add_form" action="{{url('/student/profile/guardian/parent/store/')}}" method="post">
	<input type="hidden" name="_token" value="{{csrf_token()}}">
	<input type="hidden" name="std_id" value="{{$std_id}}">
	<div class="modal-body">
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<input id="gud_name" class="form-control" placeholder="Type guardian Name" type="text">
					<input id="gud_id" name="gud_id" value="" type="hidden">
					<div class="help-block"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="submit" class="btn btn-success pull-left">Submit</button>
		<button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
	</div>
</form>

<script type="text/javascript">
    $(function () {
        // document ready
        $('#gud_name').keypress(function(){
            // clear std_id
            $('#gud_id').val('');

            // autoComplete
            $(this).autocomplete({
                source: loadFromAjax,
                minLength: 1,

                select: function (event, ui) {
                    // Prevent value from being put in the input:
                    this.value = ui.item.label;
                    // Set the next input's value to the "value" of the item.
                    $(this).next("input").val(ui.item.id);
                    event.preventDefault();
                }
            });

            function loadFromAjax(request, response) {
                var term = $("#gud_name").val();
                $.ajax({
                    url: '/student/find/student/guardian',
                    dataType: 'json',
                    data:{'term': term},
                    success: function(data) {
                        // you can format data here if necessary
                        response($.map(data, function (el) {
                            return {
                                label: el.name,
                                value: el.name,
                                id:el.id
                            };
                        }));
                    }
                });
            }
        });


        // request for section list using batch and section id
        $('form#std_parent_add_form').on('submit', function (e) {
            // alert('hello');
            e.preventDefault();

            if($('#gud_id').val()){
                // ajax request
                $.ajax({
                    url: '/student/profile/guardian/parent/store',
                    type: 'POST',
                    cache: false,
                    data: $('form#std_parent_add_form').serialize(),
//                    datatype: 'html',
                     datatype: 'json/application',

                    beforeSend: function() {
                        // show waiting dialog
                        waitingDialog.show('Loading...');
                    },

                    success:function(data){
                        // hide waiting dialog
                        waitingDialog.hide();
                        $('#globalModal').modal('toggle');
                        // checking
	                    if(data.status=='success'){
							var guardian_container = $('#guardian_container');
                            guardian_container.html('');
                            guardian_container.append(data.html);
//                            // checking
		                    if(data.parent_count==3){
			                    $('#guardian_add_btn_container').remove();
		                    }
	                    }else{
	                        alert(data.msg);
	                    }
                    },

                    error:function(data){
                    }
                });
            }else{
                // clear report card row
                $('#std_report_card_row').html('');
                // clear guardian name
                $('#std_name').val('');
                alert('Please select a Guardian');
            }

        });
    });
</script>
