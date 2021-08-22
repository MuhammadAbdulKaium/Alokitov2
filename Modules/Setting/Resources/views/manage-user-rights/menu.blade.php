
@extends('setting::manage-user-rights.index')

{{--page content--}}
@section('page-content')
	<div class="row">
		{{--menu list--}}
		<div class="col-md-12">

			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label" for="module">Module</label>
						<select id="module" class="form-control module" name="module" required>
							<option value="" selected disabled>--- Select Module ---</option>
							@foreach($allModule as $module)
								<option value="{{$module->id}}">{{$module->name}}</option>
							@endforeach
						</select>
						<div class="help-block"></div>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label" for="sub_module">Sub-Module</label>
						<select id="sub_module" class="form-control sub_module" name="sub_module" required>
							<option value="" selected disabled>--- Select Sub-Module ---</option>
						</select>
						<div class="help-block"></div>
					</div>
				</div>
			</div>
			{{--menu list table--}}
			<div id="menu_table_row" class=""></div>
		</div>
	</div>
@endsection


{{--page scripts--}}
@section('page-script')
	<script>
        $(document).ready(function () {
            jQuery(document).on('change','.module',function(e){
                // preventDefault
                e.preventDefault();
                // ajax request
                $.ajax({
                    url: "{{ url('/setting/rights/find/module') }}",
                    type: 'GET',
                    cache: false,
                    data: {'module_id': $(this).val()},
                    datatype: 'application/json',

                    beforeSend: function() {
                        // statements
                        $('#menu_table_row').html('');
                    },

                    success:function(data){
                        var option = '<option value="" selected disabled>--- Select Sub-Module ---</option>';
                        // enlisting sub-module
                        if(data.length>0) {
                            for (var i = 0; i < data.length; i++) {
                                option += '<option value="' + data[i].id + '">' + data[i].name + '</option>';
                            }
                        }
                        // find sub module
                        var sub_module = $('#sub_module');
                        // empty html
                        sub_module.html("");
                        // append sub module list
                        sub_module.append(option);
                    },

                    error:function(){
                        // statements
                        alert('Unable to perform the action tt')
                    }
                });
            });

            jQuery(document).on('change','.sub_module',function(e){
                // preventDefault
                e.preventDefault();
                // ajax request
                $.ajax({
                    url: '/setting/rights/menu/find',
                    type: 'GET',
                    cache: false,
                    data: {'module_id': $('#module').val(), 'sub_module_id': $(this).val() },
                    datatype: 'html',

                    beforeSend: function() {
                        $('#menu_table_row').html('');
                    },

                    success:function(data){
                        var menu_table_row =  $('#menu_table_row');
                        menu_table_row.html('');
                        menu_table_row.append(data);
                    },

                    error:function(data){
                        // statements
                        alert('Unable to perform the action')
                    }
                });
            });
        });
	</script>
@endsection