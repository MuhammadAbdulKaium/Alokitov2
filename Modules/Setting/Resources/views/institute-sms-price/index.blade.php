@extends('setting::layouts.master')

@section('section-title')

    <h1>
        <i class="fa fa-th-list"></i> Manage <small>Instituition SMS Price</small>
    </h1>
    <ul class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
        <li><a href="/academics/default/index">Setting</a></li>
        <li class="active">Configuration</li>
        <li class="active">Instituition SMS Price</li>
    </ul>
@endsection

@section('page-content')
    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-plus-square"></i> {{ !empty($institutionSmsPriceProfile) ? "Update" : "Create" }} Institution SMS Price </h3>
           @if(!empty($institutionSmsPriceProfile))
            <a style="float: right;     margin-top: -40px;" class="btn btn-primary btn-create" >Create</a>
            @endif
        </div>
        @if(Session::has('message'))
            <p class="alert alert-success alert-auto-hide dism " style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('message') }}</p>
        @endif

        @if(Session::has('error'))
            <p class="alert alert-warning alert-auto-hide dism " style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('error') }}</p>
        @endif

        <form id="setting-institutionSmsPriceProfile-form" name="setting_institutionSmsPriceProfile_form" action="{{url('setting/institute/sms-price/store')}}" method="post" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <input type="hidden" id="institutionSmsPriceProfile_id" name="institutionSmsPriceProfile_id" @if(!empty($institutionSmsPriceProfile)) value="{{$institutionSmsPriceProfile->id}}" @endif>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group field-subjectmaster-sub_master_name required ">
                            <label class="control-label" for="name" >Institute Name</label>
                            <select id="institute_name"  class="academicYear form-control" required name="institute_id" aria-required="true">
                                <option value="">--- Select Institute ---</option>
                                @if(!empty($institutes))
                                    @foreach($institutes as $institute )
                                        @if(!empty($institutionSmsPriceProfile->institution_id))
                                            <option value="{{ $institute->id }}" {{ ($institutionSmsPriceProfile->institution_id == $institute->id) ? 'selected' : '' }}>
                                                {{ $institute->institute_name }} </option>
                                        @else
                                            <option value="{{ $institute->id }}"> {{ $institute->institute_name }}</option>
                                        @endif
                                    @endforeach;
                                @endif
                            </select>
                            <div class="help-block"></div>
                        </div>
                    </div>


                    <div class="col-sm-3">
                        <div class="form-group field-subjectmaster-sub_master_name required">
                            <label class="control-label" for="name">SMS Price</label>
                            <input type="text" class="form-control" required @if(!empty($institutionSmsPriceProfile->sms_price)) value="{{$institutionSmsPriceProfile->sms_price}}"  @endif name="sms_price">
                        </div>
                    </div>

               </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                @if(empty($institutionSmsPriceProfile))
                    <button type="submit" class="btn btn-primary btn-create">Create</button>
                @else
                    <button type="submit" class="btn btn-primary btn-create">Update</button>
                @endif
                <button type="reset" class="btn btn-default btn-create">Reset</button>
            </div>
            <!-- /.box-footer-->
        </form>

    </div>
    @if(!empty($institutionSmsPriceList) && ($institutionSmsPriceList->count()>0))
    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-search"></i> View Institute Login Screen</h3>
        </div>
        <div class="box-body table-responsive">
            <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                <div id="w1" class="grid-view">

                    <table id="myTable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Institute Name</th>
                            <th>SMS Price</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>

                            @php
                                $i = 1
                            @endphp
                            @foreach($institutionSmsPriceList as $institutionSmsPrice)
                                <tr class="gradeX">
                                    <td>{{$i++}}</td>
                                    <td>{{$institutionSmsPrice->institute()->institute_name}}</td>
                                    <td>{{$institutionSmsPrice->sms_price}}</td>
                                    <td>
                                        {{--<a href="{{ route('--}}
                                        {{---currency', $values->id) }}" class="btn btn-info btn-xs" data-toggle="modal" data-target="#etsbModal" data-placement="top" data-content="view"><i class="fa fa-eye"></i></a>--}}

                                        <a href="{{ url('setting/institute/sms-price/edit', $institutionSmsPrice->id) }}" class="btn btn-primary btn-xs" ><i class="fa fa-edit"></i></a>
                                        <a  id="{{$institutionSmsPrice->id}}" class="btn btn-danger btn-xs delete_class"  data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
                                    </td>
                                </tr>
                            @endforeach

                        {{--{{ $data->render() }}--}}

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- /.box-body -->

    </div>
    @endif
    <!-- /.box-->
@endsection

@section('page-script')



    // institute institutionSmsPriceProfile delete

        $('.delete_class').click(function(){
    var x = confirm("Are you sure you want to delete?");
            if(x) {
            var tr = $(this).closest('tr'),
            del_id = $(this).attr('id');
            $.ajax({
                url: "/setting/institute/sms-price/delete/"+ del_id,
                type: 'GET',
                cache: false,
                success:function(result){
                    tr.fadeOut(1000, function(){
                        $(this).remove();
                    });
                }
            });
            }
        });

@endsection

