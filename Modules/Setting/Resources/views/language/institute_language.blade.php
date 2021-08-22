@extends('layouts.master')
<body class="layout-top-nav skin-blue-light">
<div class="wrapper">

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage <small>Institute Language</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/academics/default/index">Setting</a></li>
                <li class="active">Configuration</li>
                <li class="active">Institute Language </li>
            </ul>
        </section>
        <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
            @if(Session::has('message'))
                <p class="alert alert-success alert-auto-hide dism " style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('message') }}</p>
            @endif
        </div>
        <section class="content">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-plus-square"></i> Institute Language</h3>
                </div>
                    <form id="setting-Language-form" name="setting_Language_form" action="{{url('setting/language/institute/store')}}" method="post">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group field-subjectmaster-sub_master_name required">
                                        <label class="control-label" for="name">Select Institute</label>
                                        <select name="institute_name" class="form-control" id="institute_id">
                                            <option value="">Select One</option>
                                            <option value="1">Venus School and College</option>
                                            <option value="2">Other School</option>

                                        </select>
                                        <div class="help-block">
                                            @if ($errors->has('language_name'))
                                                <strong>{{ $errors->first('language_id') }}</strong>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group field-subjectmaster-sub_master_name required">
                                        <label class="control-label" for="name">Select Language </label>

                                        <select name="language_name" class="form-control" id="institute_id">
                                            @if($languages)
                                            <option value="">Select Langauge </option>
                                            @foreach($languages as $language)
                                            <option value="{{$language->id}}">{{$language->language_name}}</option>
                                                @endforeach
                                                @endif
                                        </select>

                                        <div class="help-block">
                                            @if ($errors->has('language_slug'))
                                                <strong>{{ $errors->first('institute_id') }}</strong>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary btn-create">Search</button>
                            <button type="reset" class="btn btn-default btn-create">Reset</button>
                        </div>
                        <!-- /.box-footer-->
                    </form>

            </div>
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-search"></i> View Language List</h3>
                </div>
                <div class="box-body table-responsive">
                    <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                        <div id="w1" class="grid-view">

                            <table id="myTable" class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th><a  data-sort="sub_master_name">Institute Name</a></th>
                                    <th><a  data-sort="sub_master_name">Language Name</a></th>


                                    <th><a>Action</a></th>
                                </tr>

                                </thead>
                                <tbody>

                                @if(isset($instituteLanguages))
                                    @php
                                        $i = 1
                                    @endphp
                                    @foreach($instituteLanguages as $language)
                                        <tr class="gradeX">
                                            <td>{{$i++}}</td>
                                            <td>{{$language->institute_id}}</td>
                                            <td>{{$language->language()->language_name}}</td>
                                            <td>
                                                {{--<a href="{{ route('--}}
                                                {{---currency', $values->id) }}" class="btn btn-info btn-xs" data-toggle="modal" data-target="#etsbModal" data-placement="top" data-content="view"><i class="fa fa-eye"></i></a>--}}

                                                <a href="{{ url('setting/edit-Language', $language->id) }}" class="btn btn-primary btn-xs" data-placement="top" data-content="update"><i class="fa fa-edit"></i></a>
                                                <a href="{{ url('setting/delete-Language', $language->id) }}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure to Delete?')" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                {{--{{ $data->render() }}--}}

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box-->
        </section>
        <!-- Modal  -->
        <div class="modal fade" id="etsbModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
        </div>
    </div>
    <div id="slideToTop"><i class="fa fa-chevron-up"></i></div>
</div>

<div class="modal modal-lg " id="globalModal" tabindex="-1" role="dialog"  aria-labelledby="esModalLabel" aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content" >
            <div class="modal-body">
                <div class="loader">
                    <div class="es-spinner">
                        <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--
TO load view of each row
 -->
@section('scripts')
    <script src="{{URL::asset('js/jquery-ui.min.js')}}" type="text/javascript"></script>


    <script type="text/javascript">

    </script>

@endsection

</body>

</html>
