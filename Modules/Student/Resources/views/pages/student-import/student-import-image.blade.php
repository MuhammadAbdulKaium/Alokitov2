@extends('layouts.master')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-upload"></i> Import Student        </h1>
            <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/student/default/index">Student</a></li>
                <li class="active">Import Student</li>
            </ul>
        </section>

        <section class="content">

            @if(Session::has('error'))
                <div class="alert alert-success alert-dismissible alert-auto-hide">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('error') }} </h4>
                </div>
            @elseif(Session::has('success'))
                <div id="w0-success-0" class="alert-success alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('success') }} </h4>
                </div>
            @elseif(Session::has('warning'))
                <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('warning') }} </h4>
                </div>
            @endif

            <form id="import-student" action="/student/image/import/upload" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{csrf_token()}}">

                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-file-excel-o"></i> Select File</h3>
                    </div><!--./box-header-->

                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group field-stumaster-importfile">
                                    <input type="file" name="image[]" multiple>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <div class="callout callout-info">
                                    <h4><i class="fa fa-bullhorn" aria-hidden="true"></i> Instructions :</h4>
                                    <ol>
                                        <li><b>Single / Multiple Image(s) can be uploaded.</b></li>
                                        <li><b>Image must be in .jpg format.</b></li>
                                        <li><b>Image must be below 50KB.</b></li>
                                    </ol>
                                    {{-- <h4>
                                        <strong>
                                            <a href="{{asset("/download/student_import.xlsx")}}" target="_blank">
                                                Click here to download
                                            </a>
                                        </strong>
                                        sample format of import data in <b>XLSX</b> format.
                                    </h4> --}}
                                </div><!--./callout-->
                            </div><!--./col-->
                        </div><!--./row-->
                    </div><!--./box-body-->
                    <div class="box-footer">
{{--                        <input type="submit" class="btn btn-primary">--}}
                        <button type="submit" id="submitBtn" class="btn btn-primary"><i class="fa fa-upload"></i> Import</button>	</div>
                </div><!--./box-->
            </form>
        </section>
    </div>

    <div id="slideToTop" ><i class="fa fa-chevron-up"></i></div>
    </div>



    <div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true" style="width:100%;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="loader">
                        <div class="preview" style="padding-left: 40%;">
                            <i id="icon_msg" class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
                            <h3 id="msg_up">Uploading...</h3>
                        </div>
                            <div class="progress" style="display:none">
                                <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
                                    0%
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


{{--@section('scripts')--}}
{{--    <script src="http://malsup.github.com/jquery.form.js"></script>--}}
{{--    <script src="{{URL::asset('js/jquery.redirect.js')}}" type="text/javascript"></script>--}}

{{--    <script type="text/javascript">--}}
{{--        $(document).ready(function(){--}}
{{--            $("#submitBtn").click(function () {--}}

{{--                if($('#student_import_file').val()){--}}
{{--                    $("#globalModal").modal();--}}
{{--                    var progressbar     = $('.progress-bar');--}}

{{--                    $("#import-student").ajaxForm({--}}
{{--                        beforeSend: function() {--}}

{{--                            $(".progress").css("display","block");--}}

{{--                            progressbar.width('0%');--}}

{{--                            progressbar.text('0%');--}}

{{--                        },--}}

{{--                        uploadProgress: function (event, position, total, percentComplete) {--}}


{{--                            progressbar.width(percentComplete + '%');--}}

{{--                            progressbar.text(percentComplete + '%');--}}
{{--                            if(percentComplete == 100){--}}
{{--                                $("#msg_up").text("Processing...");--}}
{{--                                progressbar.hide();--}}
{{--                            }--}}

{{--                        },--}}

{{--                        success:function(data){--}}
{{--                            console.log(data);--}}
{{--                            --}}{{--$("#globalModal").modal('toggle');--}}
{{--                            --}}{{--$.redirect('import/redirect',--}}
{{--                            --}}{{--    {--}}
{{--                            --}}{{--        'data': data,--}}
{{--                            --}}{{--        '_token':"{{csrf_token()}}"});--}}

{{--                        }--}}

{{--                    }).submit();--}}


{{--                }else{--}}
{{--                    alert('No file Selected')--}}
{{--                }--}}
{{--            });--}}
{{--        });--}}
{{--     </script>--}}
{{--@endsection--}}