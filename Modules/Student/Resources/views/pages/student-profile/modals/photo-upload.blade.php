
         <div class="modal-header">
            <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title"><i class="fa fa-picture-o"></i> Upload Photo</h4>
         </div>
         @if($photoProfile = $studentProfile->singelAttachment("PROFILE_PHOTO"))
         <form id="stu-photo-form" action="{{url('/student/profile/photo/update', [$photoProfile->id])}}" method="POST" enctype="multipart/form-data">
             <input type="hidden" name=std_id value="{{$studentProfile->id}}">
         @else
         <form id="stu-photo-form" action="{{url('/student/profile/photo/store')}}" method="POST" enctype="multipart/form-data">
            <input type="hidden" name=std_id value="{{$studentProfile->id}}">
         @endif
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <div class="modal-body">
               <div class="row stu-photo-form text-center">
                  <div class="col-md-12">
                    @if($photoProfile)                   
                      <img id="stu-photo-prev" src="{{URL::asset('assets/users/images/'.$photoProfile->singleContent()->name)}}" alt="No Image" width="150px" height="150px">
                    @else
                     <img id="stu-photo-prev" src="{{URL::asset('assets/users/images/user-default.png')}}" alt="No Image" width="150px" height="150px"> 
                    @endif   
                  </div>
                  <div class="col-md-10 col-sm-offset-1">
                     <br>
                     <div class="form-group image">
                        <input name="image" value="" type="hidden">
                        <input id="image" name="image" title="Browse Photo" accept="image/*" data-filename-placement="inside" onchange="uploadImage(this)" data-target="#stu-photo-prev" type="file">
                        <div class="help-block"></div>
                     </div>
                  </div>
                  <div class="col-sm-12 text-red">
                     <b>NOTE : Upload only JPG, JPEG and PNG images and smaller than 300KB </b>
                  </div>
                   <div class="col-md-8 col-md-offset-2">
                       <label for="date">Enter Date</label>
                       <input type="date" class="form-control" name="date" required>
                   </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="submit" class="btn btn-info pull-left">Update</button>    
               <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
            </div>
         </form>

  <script type ="text/javascript"> 
    jQuery('#stu-photo-form').yiiActiveForm([{
      "id": "image",
      "name": "image",
      "container": ".image",
      "input": "#image",
      "validate": function (attribute, value, messages, deferred, $form) {
        yii.validation.file(attribute, messages, {
          "message": "File upload failed.",
          "skipOnEmpty": true,
          "mimeTypes": [],
          "wrongMimeType": "Only files with these MIME types are allowed: .",
          "extensions": ["jpg", "jpeg", "png"],
          "wrongExtension": "Only files with these extensions are allowed: jpg, jpeg, png.",
          "maxSize": 607200,
          "tooBig": "The file \"{file}\" is too big. Its size cannot exceed 500 KiB.",
          "maxFiles": 1,
          "tooMany": "You can upload at most 1 file."
        });
      }
    }], []);

    var imageTypes = ['jpeg', 'jpg', 'png', 'gif']; //Validate the images to show

    function showImage(src, target) {
      var fr = new FileReader();
      fr.onload = function (e) {
        target.src = this.result;
      };
      fr.readAsDataURL(src.files[0]);
    }

    var uploadImage = function (obj) {
      var val = obj.value;
      var lastInd = val.lastIndexOf('.');
      var ext = val.slice(lastInd + 1, val.length);
      if (imageTypes.indexOf(ext) !== -1) {
        var id = $(obj).data('target');
        var src = obj;
        var target = $(id)[0];
        showImage(src, target);
      }
    }  
  </script>


