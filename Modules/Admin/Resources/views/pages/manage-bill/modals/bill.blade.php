<form action="{{url('admin/bill/store')}}" method="POST">
	<input type="hidden" name="_token" value="{{csrf_token()}}" />
	{{--modal header--}}
	<div class="modal-header">
		<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
		<h4 class="modal-title"><i class="fa fa-money"></i> Add Bill</h4>
	</div>
	{{--modal-body--}}
	<div class="modal-body">
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<label for="institute_list">Institute Name:</label>
					<select id="institute_list" class="form-control select2" name="institute_list[]" multiple="multiple" required>
						@if($instituteList->count()>0)
							{{--institute looping--}}
							@foreach($instituteList as $institute )
								<option value="{{ $institute->id }}"> {{$institute->institute_name}}</option>
							@endforeach
						@endif
					</select>
				</div>
				<label class="pull-right"><input type="checkbox" id="select_all"> Select All</label>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<label for="month">Month</label>
				<select class="form-control" id="month" name="month" required>
					<option value="" disabled selected="selected" class="bg-gray-active">Select Month</option>
					@for($monthNum=1; $monthNum<=12; $monthNum++)
						{{--find month name--}}
						@php $monthName = date("F", mktime(0, 0, 0, $monthNum, 10)); @endphp
						{{--print month name--}}
						<option value="{{$monthNum}}">{{$monthName}}</option>
					@endfor
				</select>
			</div>
		</div>
	</div>

	{{--modal-footer--}}
	<div class="modal-footer">
		<button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
		<button class="btn btn-primary pull-left" type="submit">Submit</button>
	</div>
</form>

<script>
    $(document).ready(function() {
        // select2 class initialization
        $('.select2').select2({
            placeholder: "Select a Institute", allowClear: true
        });

        // select all button action
        $('#select_all').click(function() {
            // checking
            if($(this).is(':checked')){
                // select all institute
                $('.select2 > option').prop("selected",true).trigger("change");
            }else{
                // clear all institute
                $(".select2").select2("val", "");
            }
        });

    });
</script>