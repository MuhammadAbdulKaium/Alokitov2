<script type = "text/javascript"> 
		$(document).ready(function(){

			$("#edit-personal").click(function(){
			    $_token = "{{ csrf_token() }}";
			    $.ajax({
			        url: "{{ url('/student/editpersonal') }}",
			        type: 'GET',
			        cache: false,
			        data: {'_token': $_token }, //see the $_token
			        datatype: 'html',

			        beforeSend: function() {
			        },

			        success: function(data) {
			            if(data.success == true) {
			              // remove modal body	              
			              $('#modal-body').remove();
			              // add modal content		              
			              $('#modal-content').html(data);
			            } else {
			              // add modal content	
			              $('#modal-content').html(data);
			            }
			        },

			        error: function(xhr,textStatus,thrownError) {
			            alert(xhr + "\n" + textStatus + "\n" + thrownError);
			        }
			    });
			});	


			$("#chage-photo").click(function(){
			    $_token = "{{ csrf_token() }}";
			    $.ajax({
			        url: "{{ url('/student/uploadphoto') }}",
			        type: 'GET',
			        cache: false,
			        data: {'_token': $_token }, //see the $_token
			        datatype: 'html',

			        beforeSend: function() {
			        },

			        success: function(data) {
			            // console.log('success');
			            // console.log(data);
			            if(data.success == true) {
			              // remove modal body	              
			              $('#modal-body').remove();
			              // add modal content		              
			              $('#modal-content').html(data);
			            } else {
			              // add modal content	
			              $('#modal-content').html(data);
			            }
			        },

			        error: function(xhr,textStatus,thrownError) {
			            alert(xhr + "\n" + textStatus + "\n" + thrownError);
			        }
			    });
			});
		

			$("#edit-email").click(function(){
			    $_token = "{{ csrf_token() }}";
			    $.ajax({
			        url: "{{ url('/student/editemail') }}",
			        type: 'GET',
			        cache: false,
			        data: {'_token': $_token }, //see the $_token
			        datatype: 'html',

			        beforeSend: function() {
			        },

			        success: function(data) {
			            if(data.success == true) {
			              // remove modal body	              
			              $('#modal-body').remove();
			              // add modal content		              
			              $('#modal-content').html(data);
			            } else {
			              // add modal content	
			              $('#modal-content').html(data);
			            }
			        },

			        error: function(xhr,textStatus,thrownError) {
			            alert(xhr + "\n" + textStatus + "\n" + thrownError);
			        }
			    });
			});
		});
	</script>