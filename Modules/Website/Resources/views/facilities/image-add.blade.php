@if($user->hasRole(['super-admin']) || $user->hasRole(['admin']))

    <link href="{{ URL::asset('css/jquery-ui.min.css') }}" rel="stylesheet" type="text/css"/>

    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Add Image</h4>
            </div>

            <form id="add-information-form" name="add-information-form"  class="form-horizontal" action="{{url('website/extra/add-image', $facility->id)}}" method="post" role="form" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="modal-body">
                    <input id="campus_id" class="form-control" name="campus_id" type="hidden" value="{{session()->get('campus')}}">
                    <input id="institute_id" class="form-control" name="institute_id" type="hidden" value="{{session()->get('institute')}}">
{{--                    <input id="type" class="form-control" name="type" type="hidden" value="{{$typeArray[1]}}">--}}

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="file">Image</label>
                                <input id="file" class="form-control" name="file[]" accept=".jpg,.jpeg,.png" onchange="validateFileType()" type="file" multiple>
                                <b>NOTE : Upload only JPG, JPEG and PNG images </b>
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
            var fileName = document.getElementsByName('file[]')[0].files;
            var flag = 0;

            Array.prototype.forEach.call(fileName, (item) => {
                // console.log(item.name);
                var img = item.name;
                var idxDot = img.lastIndexOf(".") + 1;
                var extFile = img.substr(idxDot, img.length).toLowerCase();
                if (extFile=="jpg" || extFile=="jpeg" || extFile=="png"){
                }else{
                    flag = 1;
                }
            });
            if(flag == 1)
            {
                alert("Only jpg/jpeg and png files are allowed!")
            }
        }
    </script>

@else
    <h1>YOU DO NOT HAVE PERMISSION FOR THIS PAGE!</h1>
@endif




