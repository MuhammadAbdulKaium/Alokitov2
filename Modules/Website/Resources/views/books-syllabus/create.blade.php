@if($user->hasRole(['super-admin']) || $user->hasRole(['admin']))

    <link href="{{ URL::asset('css/jquery-ui.min.css') }}" rel="stylesheet" type="text/css"/>

    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Add Book $ Syllabus</h4>
            </div>

            <form id="add-information-form" name="add-information-form"  class="form-horizontal" action="{{url('website/extra/store')}}" method="post" role="form" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="modal-body">
                    <input id="campus_id" class="form-control" name="campus_id" type="hidden" value="{{session()->get('campus')}}">
                    <input id="institute_id" class="form-control" name="institute_id" type="hidden" value="{{session()->get('institute')}}">
                    <input id="type" class="form-control" name="type" type="hidden" value="{{$typeArray[1]}}">


                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="name">Class*</label>
                                <input id="name" class="form-control" name="name" type="text" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="file">Books</label>
                                <input id="file" class="form-control" name="file" accept=".pdf" onchange="validateFileType()" type="file">
                                <b>NOTE : Upload only PDF Files </b>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="file2">Syllabus</label>
                                <input id="file2" class="form-control" name="file2" accept=".pdf" onchange="validateFileType2()" type="file">
                                <b>NOTE : Upload only PDF Files </b>
                            </div>
                        </div>
                    </div>

                    <!--./body-->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-create">Save</button>
                        <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="{{URL::asset('js/jquery-ui.min.js')}}" type="text/javascript"></script>

    <script type="text/javascript">
        function validateFileType(){
            var fileName = document.getElementById("file").value;
            var idxDot = fileName.lastIndexOf(".") + 1;
            var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
            if (extFile=="pdf"){
                //TO DO
            }else{
                alert("Only pdf files are allowed!");
            }
        }

        function validateFileType2(){
            var fileName = document.getElementById("file2").value;
            var idxDot = fileName.lastIndexOf(".") + 1;
            var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
            if (extFile=="pdf"){
                //TO DO
            }else{
                alert("Only pdf files are allowed!");
            }
        }

        // $("#file2").change(function () {
        //     var fileExtension = ['pdf'];
        //     if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
        //         alert("Only formats are allowed : "+fileExtension.join(', '));
        //     }
        // });
    </script>

@else
    <h1>YOU DO NOT HAVE PERMISSION FOR THIS PAGE!</h1>
@endif




