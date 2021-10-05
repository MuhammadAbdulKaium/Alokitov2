@if($user->hasRole(['super-admin']) || $user->hasRole(['admin']))

    <link href="{{ URL::asset('css/jquery-ui.min.css') }}" rel="stylesheet" type="text/css"/>

    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Add Committee</h4>
            </div>

            <form id="add-information-form" name="add-information-form"  class="form-horizontal" action="{{url('website/committee/store')}}" method="post" role="form" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="modal-body">
                    <input id="campus_id" class="form-control" name="campus_id" type="hidden" value="{{session()->get('campus')}}">
                    <input id="institute_id" class="form-control" name="institute_id" type="hidden" value="{{session()->get('institute')}}">

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="image">Image</label>
                                <input id="image" class="form-control" name="image" accept=".jpg,.jpeg,.png" onchange="validateFileType()" type="file">
                                <b>NOTE : Upload only JPG, JPEG and PNG images </b>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label" for="name">Name*</label>
                                <input id="name" class="form-control" name="name" type="text" required>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label" for="designation">Designation</label>
                                <input id="designation" class="form-control" name="designation" maxlength="15" type="text">
                            </div>

                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label" for="email">Email</label>
                                <input id="email" class="form-control" name="email" type="email">
                            </div>
                            <div class="col-md-6">
                                <label class="control-label" for="phone">Phone</label>
                                <input id="phone" class="form-control" name="phone" type="number">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="speech">Speech</label>
                                <textarea name="speech" id="speech" class="form-control"></textarea>
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
            var fileName = document.getElementById("image").value;
            var idxDot = fileName.lastIndexOf(".") + 1;
            var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
            if (extFile=="jpg" || extFile=="jpeg" || extFile=="png"){
                //TO DO
            }else{
                alert("Only jpg/jpeg and png files are allowed!");
            }
        }
    </script>

@else
    <h1>YOU DO NOT HAVE PERMISSION FOR THIS PAGE!</h1>
@endif




