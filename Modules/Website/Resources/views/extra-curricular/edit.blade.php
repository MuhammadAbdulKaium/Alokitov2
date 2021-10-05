@if($user->hasRole(['super-admin']) || $user->hasRole(['admin']))

    <link href="{{ URL::asset('css/jquery-ui.min.css') }}" rel="stylesheet" type="text/css"/>

    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Edit Facilities</h4>
            </div>

            <form id="add-information-form" name="add-information-form"  class="form-horizontal" action="{{url('website/extra/update', $extraCurricular->id)}}" method="post" role="form" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="modal-body">
                    <input id="campus_id" class="form-control" name="campus_id" type="hidden" value="{{session()->get('campus')}}">
                    <input id="institute_id" class="form-control" name="institute_id" type="hidden" value="{{session()->get('institute')}}">
                    <input id="type" class="form-control" name="type" type="hidden" value="{{$typeArray[1]}}">

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="curricular_type">Extra Curricular Type</label>
                                <select id="curricular_type" class="form-control academicLevel" name="curricular_type">
                                    <option value="" disabled selected hidden>{{$extraCurricular->curricular_type}}</option>
                                    <option value="Club">Club</option>
                                    <option value="Event">Event</option>
                                </select>
                                <div class="help-block help-block-error "></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="file">Image</label>
                                <input id="file" class="form-control" name="file" accept=".jpg,.jpeg,.png" onchange="validateFileType()" type="file">
                                <b>NOTE : Upload only JPG, JPEG and PNG images </b>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="name">Title (Bangla)*</label>
                                <input id="name" class="form-control" name="name" value="{{$extraCurricular->name}}" type="text" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="description">Description (Bangla)</label>
                                <textarea name="description" id="description" class="form-control">{{$extraCurricular->description}}</textarea>
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




