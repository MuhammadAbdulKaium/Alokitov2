<div class="box box-solid">
    <div class="et">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-image"></i> Upload Images</h3>
            <button class="btn btn-success pull-right" id="btnUpload">Upload</button>
        </div>
    </div>
    <form action="/student/upload/images/store"
          class="dropzone"
          id="dropzone">
        <input type="hidden" name="_token" value="{{csrf_token()}}">

    </form>
</div>

<script>
    $(function () {


        // init dropzone with auto process queue false
        var dropZone = new Dropzone("#dropzone", {
            paramName: "file",
            clickable: true,
            autoProcessQueue: false,
            uploadMultiple: true, // uplaod files in a single request
            parallelUploads: 100, // use it with uploadMultiple
            maxFilesize: 1, // MB
            maxFiles: 20,
            acceptedFiles: ".jpg, .jpeg, .png",
            addRemoveLinks: true,
            // Language Strings
            dictFileTooBig: "File is to big. Max allowed file size is 1 mb",
            dictInvalidFileType: "Invalid File Type",
            dictCancelUpload: "Cancel",
            dictRemoveFile: "Remove",
            dictMaxFilesExceeded: "Only 20 files are allowed",
            dictDefaultMessage: "Drop files here to upload",
            accept: function(file, done) {
                // if ( !someCheck() ) { return done('This is invalid!'); }
                return done();
            }
        });

        // on add file
        dropZone.on("addedfile", function(file) {
            // file name
            var file_name = file.name;
            var std_id = file_name.replace(/.jpg|.jpeg|.png/gi, "");
            // std list
            var std_list = $('#class_std_list').val().split(',');

            // checking std id
            if(($.inArray(std_id, std_list) > -1) && file.size > 512){
                // accept file
            }else{
                //alert("No "+" "+std_id);
                dropZone.removeFile(file);
            }

        });

        // on success
        dropZone.on('success', function(file, response ){
            // console.log(response);
        });

        // on error
        dropZone.on("error", function(file, response) {
            // console.log(response);
        });

        // on start
        dropZone.on("sendingmultiple", function(file) {
            // console.log(file);
        });
        // on success
        dropZone.on("successmultiple", function(file) {
            // console.log(file);
        });

        // disable queue auto processing on upload complete
        dropZone.on("queuecomplete", function() {
            dropZone.options.autoProcessQueue = false;
        });

        // Execute when file uploads are complete
        dropZone.on("complete", function() {
            // If all files have been uploaded
            if (dropZone.getQueuedFiles().length == 0 && dropZone.getUploadingFiles().length == 0) {
                // Remove all files
                //dropZone.removeAllFiles();
                // sweet alert
                swal("Success", 'Image Uploaded', "success");
            }
        });


        $(document).on('click', '#btnUpload', function () {
            // checking file length
            if (dropZone.getQueuedFiles().length > 0) {
                // enable auto process queue after uploading started
                dropZone.options.autoProcessQueue = true;
                // queue processing
                dropZone.processQueue();
            }else {
                // sweet alert
                swal("Warning", 'No Queued Image found', "warning");
            }
        });
    });
</script>