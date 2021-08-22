<form action="{{url('/academics/physical/room/category/update/'.$category->id)}}" method="POST">
    @csrf
	
	<div class="modal-header">
		<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
		<h4 class="modal-title">
			<i class="fa fa-info-circle"></i> Edit {{$category->name}} Category
        </h4>
	</div>
	<!--modal-header-->
	<div class="modal-body">
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<label for="sub_name"> Category Name:</label>
					<input type="text" class="form-control" name="category_name" value="{{$category->name}}">
				</div>
			</div>
			<div class="col-sm-12">
				<div class="form-group">
					<label for="sub_name"> Is Club:</label>
					<input type="checkbox" name="cat_type" value="1" {{($category->cat_type == 1)?'checked':''}}>
					
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="submit" class="btn btn-info pull-left">Update</button>
		<a class="btn btn-default pull-right" data-dismiss="modal">Cancel</a>
	</div>
</form>

<script type="text/javascript">
    
</script>
