{{--form--}}
<style type="text/css">
	.ui-autocomplete {z-index:2147483647;}
	.ui-autocomplete span.hl_results {background-color: #ffff66;}
</style>
<form id="academic_subject_group_assign_form" action="{{url('/academics/subject/group/store')}}" method="POST">
	<input type="hidden" name="_token" value="{{csrf_token()}}">
	<input type="hidden" id="sub_group_id" name="sub_group_id" value="{{$subGroupProfile->id}}">
	<div class="modal-header">
		<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
		<h4 class="modal-title">
			<i class="fa fa-info-circle"></i> Assign Subject for <b>{{$subGroupProfile->name}}</b> Subject Group
		</h4>
	</div>
	<!--modal-header-->
	<div class="modal-body">
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<label for="sub_name"> Subject Name:</label>
					<input type="text" id="sub_name" class="form-control" required>
					<input type="hidden" id="sub_id" name="sub_id" value="" />
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="submit" class="btn btn-info pull-left">Submit</button>
		<a class="btn btn-default pull-right" data-dismiss="modal">Cancel</a>
	</div>
</form>

<script type="text/javascript">
    $(function() {

        // document ready
        $('#sub_name').keypress(function(){
            // clear std_id
            $('#sub_id').val('');
            // empty std_report_card_row
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
                var term = $("#sub_name").val();
                $.ajax({
                    url: '/academics/find/campus/subject',
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
        $('form#academic_subject_group_assign_form').on('submit', function (e) {
            // alert('hello');
            e.preventDefault();

            var sub_id = $('#sub_id').val();
            var sub_name = $('#sub_name');
            var sub_group_id = $('#sub_group_id').val();
            // checking
            if(sub_id){
                // ajax request
                $.ajax({
                    url: '/academics/subject/group/assign/store',
                    type: 'POST',
                    cache: false,
                    data: $('form#academic_subject_group_assign_form').serialize(),
                    datatype: 'json/application',

                    beforeSend: function() {
                        // show waiting dialog
                        waitingDialog.show('Loading...');
                    },

                    success:function(data){
                        // modal dismiss
                        $('#globalModal').modal('toggle');
                        // hide waiting dialog
                        waitingDialog.hide();
						// checking
                        if(data.status=='success'){

                            var sub_count = $('#sub_group_table_'+sub_group_id+'_row_count');
                            sub_count.val(parseInt(sub_count.val())+1);

                            var tr = '<tr><td>'+sub_count.val()+'</td><td>'+sub_name.val()+'</td><td><a href="/academics/subject/group/assign/delete/'+data.assign_id+'"><i class="fa fa-trash-o"></i></a></td></tr>';

                            $('#sub_group_table_'+sub_group_id).removeClass('hide');
                            // append table row to the table body
	                        $('#sub_group_table_body_'+sub_group_id).append(tr);

                        }else{
                            alert(data.msg);
                        }
                    },

                    error:function(data){
                        // hide waiting dialog
                        waitingDialog.hide();
                        alert(JSON.stringify(data));
                    }
                });
            }else{
                // clear subject name
                sub_name.val('');
                alert('Please select a Subject');
            }
        });
    });
</script>
