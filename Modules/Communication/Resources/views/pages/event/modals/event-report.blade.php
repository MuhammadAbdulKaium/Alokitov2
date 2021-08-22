<div class="modal-header">
	<button aria-label="Close" data-dismiss="modal" class="close" type="button">
		<span aria-hidden="true">×</span>
	</button>
	<h4 class="modal-title">
		<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Academic Event Report<br>
	</h4>
</div>
<form id="#" action="{{url('/reports/event/report/download')}}" method="post">
	<input type="hidden" name="_token" value="{{csrf_token()}}">
	<div class="modal-body">
		<div class="row">
			<div class="col-sm-4">
				<div class="form-group">
					<label class="control-label" for="from_date">From Date</label>
					<input readonly="" class="form-control datePicker" name="from_date" id="from_date" type="text">
					<div class="help-block"></div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
					<label class="control-label" for="to_date">To Date</label>
					<input readonly="" class="form-control datePicker" name="to_date" id="to_date" type="text">
					<div class="help-block"></div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
					<label class="control-label" for="doc_type">Type</label>
					<select id="doc_type" class="form-control" name="doc_type" required="">
						<option value="">--- Select Type ---</option>
						<option value="pdf">PDF</option>
						<option value="xlsx">Excel</option>
					</select>
					<div class="help-block"></div>
				</div>
			</div>
		</div>
	</div>
	<!--./body-->
	<div class="modal-footer">
		<button type="submit" class="btn btn-info">Submit</button>
		<button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
	</div>
</form>

<script type="text/javascript">
    $(function() { // document ready
        //Date picker
        $('.datePicker').datepicker({ autoclose: true});
    });
</script>