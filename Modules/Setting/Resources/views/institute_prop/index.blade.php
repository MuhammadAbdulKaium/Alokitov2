@extends('setting::layouts.master')

@section('section-title')

    <h1>
        <i class="fa fa-th-list"></i> Manage <small>Institute Property</small>
    </h1>
    <ul class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
        <li><a href="/academics/default/index">Setting</a></li>
        <li class="active">Configuration</li>
        <li class="active">Property Name</li>
    </ul>
@endsection

@section('page-content')
    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-plus-square"></i> Create Institute Property</h3>
        </div>
        @if(Session::has('message'))
            <p class="alert alert-success alert-auto-hide dism " style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('message') }}</p>
        @endif

        <form id="setting-Property-form" name="setting_Property_form" action="{{url('setting/institute/property/store')}}" method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <input type="hidden" id="property_id" name="property_id" @if(!empty($property)) value="{{$property->id}}" @endif>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group field-subjectmaster-sub_master_name ">
                            <label class="control-label" for="name">Institute Name</label>
                            <select id="institute_name" class="academicYear form-control" required name="institute_name" aria-required="true">
                                <option value="">--- Select Institute ---</option>
                                @if(!empty($institutes))
                                    @foreach($institutes as $institute )
                                        @if(!empty($property->institution_id))
                                            <option value="{{ $institute->id }}" {{ ($property->institution_id == $institute->id) ? 'selected' : '' }}>
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
                            <label class="control-label" for="name">Campus Name</label>
                            <select id="campus_name" class="form-control academicLevel" name="campus_name">
                                <option @if(!empty($property->campus_id)) value="{{$property->campus_id}}"  @endif>@if(!empty($property->campus_id)) {{$property->campus()->name}} @endif</option>
                            </select>
                        </div>
                    </div>


                    <div class="col-sm-3">
                        <div class="form-group field-subjectmaster-sub_master_name required">
                            <label class="control-label" for="name">Attribute Name</label>
                            <input type="text" class="form-control" @if(!empty($property->attribute_name)) value="{{$property->attribute_name}}"  @endif name="attribute_name">
                        </div>
                    </div>

                    @if(!empty($property->attribute_value)) @php $attribute_value_array=str_split($property->attribute_value) @endphp @endif


                    <div class="col-sm-3">
                        <div class="form-group field-subjectmaster-sub_master_name required">
                            <label class="control-label" for="name">Select Text or Color or Font-Family</label>
                            <select id="select_color_text" class="form-control academicLevel" name="select_color_text">
                                <option value="">Select One</option>
                                <option  class="select_color_text_id" @if(!empty($attribute_value_array[0]) && $attribute_value_array[0]=="#") selected="selected" @endif value="2">Color</option>
                                <option  class="select_color_text_id" @if(!empty($attribute_value_array[0]) && $attribute_value_array[0]!="#") selected="selected" @endif value="1">Text</option>
                                <option  class="select_color_text_id" @if(!empty($property) && $property->font_family_id>0) selected="selected" @endif value="3">Font-Family</option>
                                {{--<option  class="select_color_text_id" @if(!empty($attribute_value_array[0])!="#") selected="selected"  @endif value="1">Text</option>--}}
                            </select>
                        </div>
                    </div>


                    <div class="col-sm-3">
                        <input type="hidden"  @if(!empty($property->attribute_value))  value="{{$attribute_value_array[0]}}" @endif id="attribute_value_id">
                        <input type="hidden"  @if(!empty($property) && $property->font_family_id>0)  value="1" @endif id="font_family_id">
                        {{--@if(($attribute_value_array[0])=="#")--}}
                        <div class="attribute_color" style="display: none">
                            <div class="form-group field-subjectmaster-sub_master_name required">
                                <label class="control-label" for="name">Attribute Color</label>
                                <input id="attribute_colorcode" @if(!empty($property->attribute_name)) value="{{$property->attribute_value}}" @else value="#3C8DBC" @endif maxlength="35"   class="form-control attribute_value" type="color">
                            </div>
                        </div>
                        {{--@else--}}
                        <div class="attribute_text" style="display: none">
                            <div class="form-group field-subjectmaster-sub_master_name required">
                                <label class="control-label" for="name">Attribute Text</label>
                                <textarea id="attribute_textarea" class="form-control attribute_value"> @if(!empty($property->attribute_value)) {{$property->attribute_value}} @endif</textarea>
                            </div>
                        </div>

                        <div class="attribute_font_select" style="display: none">
                            <div class="form-group field-subjectmaster-sub_master_name required">
                                <label class="control-label" for="name">Font Web Link</label>
                                <select class="form-control"  name="font_family_id">
                                    <option value="">Select One</option>
                                         @foreach($fontFmailyList as $font)
                                        @if(!empty($property) && $property->font_family_id>0)
                                            <option @if($property->font_family_id==$font->id) selected @endif  value="{{$font->id}}">{{$font->font_name}}</option>
                                                 @else
                                             <option value="{{$font->id}}">{{$font->font_name}}</option>
                                        @endif
                                        @endforeach

                                </select>

                            </div>
                        </div>
                        {{--@endif--}}
                    </div>




                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                @if(empty($property))
                    <button type="submit" class="btn btn-primary btn-create">Create</button>
                @else
                    <button type="submit" class="btn btn-primary btn-create">Update</button>
                @endif
                <button type="reset" class="btn btn-default btn-create">Reset</button>
            </div>
            <!-- /.box-footer-->
        </form>

    </div>
    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-search"></i> View Institute Property List</h3>
        </div>
        <div class="box-body table-responsive">
            <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                <div id="w1" class="grid-view">

                    <table id="myTable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th><a  data-sort="sub_master_name">Property Name</a></th>
                            <th><a>Campus Name</a></th>
                            <th><a>Attribute</a></th>
                            <th><a>Color/Text/Font Fmaily</a></th>
                            <th>Action</th>
                        </tr>

                        </thead>
                        <tbody>

                        @if(isset($institute_propertys))
                            @php
                                $i = 1
                            @endphp
                            @foreach($institute_propertys as $institute_property)
                                <tr class="gradeX">
                                    <td>{{$i++}}</td>
                                    <td>{{$institute_property->institute()->institute_name}}</td>
                                    <td>{{$institute_property->campus()->name}}</td>
                                    <td>{{$institute_property->attribute_name}}</td>
                                    <td>
                                        @if($institute_property->font_family_id==NULL)
                                        <input name="color" maxlength="35" disabled="" value="{{$institute_property->attribute_value}}" class="form-control" type="color">
                                        @else
                                            {{$institute_property->font_family()->font_name}}
                                        @endif
                                    </td>
                                    <td>
                                        {{--<a href="{{ route('--}}
                                        {{---currency', $values->id) }}" class="btn btn-info btn-xs" data-toggle="modal" data-target="#etsbModal" data-placement="top" data-content="view"><i class="fa fa-eye"></i></a>--}}

                                        <a href="{{ url('setting/institute/property/edit', $institute_property->id) }}" class="btn btn-primary btn-xs" ><i class="fa fa-edit"></i></a>
                                        <a  id="{{$institute_property->id}}" class="btn btn-danger btn-xs delete_class" onclick="return confirm('Are you sure to Delete?')" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
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
@endsection

@section('page-script')
    // request institute id and get campus id
    $("#institute_name").on('change',function(){
    // get institute id
    var institute_id = $(this).val();
    var div = $(this).parent();
    var op="";

    $.ajax({
    url: "{{ url('/setting/find/campus') }}",
    type: 'GET',
    cache: false,
    data: {'id': institute_id }, //see the $_token
    datatype: 'application/json',
    beforeSend: function() {
    },

    success:function(data){

    op+='<option value="0" selected disabled>--- Select Campus ---</option>';
    for(var i=0;i<data.length;i++){
    // console.log(data[i].level_name);
    op+='<option value="'+data[i].id+'">'+data[i].name+'</option>';
    }

    // set value to the academic batch
    $('#campus_name').html("");
    $('#campus_name').append(op);
    },
    error:function(){

    }
    });

    });


    var property_id= $("#property_id").val();
    var font_family_id= $("#font_family_id").val();

    {{--alert(font_family_id);--}}

    if(property_id!=""){
    var attribute_value_id=$("#attribute_value_id").val();
    if(font_family_id==1){
      $(".attribute_font_select").show();
    }
    else if(attribute_value_id=="#") {
    $(".attribute_color").show();
    $("#attribute_colorcode").attr('name', 'attribute_value');
    } else {
    $(".attribute_text").show();
    $("#attribute_textarea").attr('name', 'attribute_value');
    }

    }

    {{--<script>--}}
    // select text or color then action

    $("#select_color_text").change(function () {
    var select_color_text_id= $(this).val();

    if(select_color_text_id==1){

    $(".attribute_color").hide();
    $("#attribute_colorcode").removeAttr('name');
    $(".attribute_text").show();
    $("#attribute_textarea").attr('name', 'attribute_value');

    } else if(select_color_text_id==2) {
    $(".attribute_text").hide();
    $("#attribute_textarea").removeAttr('name');
    $(".attribute_color").show();
    $("#attribute_colorcode").attr('name', 'attribute_value');
    } else if(select_color_text_id==3)  {
          $(".attribute_font_select").show();
            $(".attribute_text").hide();
            $("#attribute_textarea").removeAttr('name');
            $(".attribute_color").hide();
            $("#attribute_colorcode").removeAttr('name');
    }


    })



    // institute property delete

    $('.delete_class').click(function(){
    var tr = $(this).closest('tr'),
    del_id = $(this).attr('id');

    $.ajax({
    url: "/setting/institute/property/delete/"+ del_id,
    type: 'GET',
    cache: false,
    success:function(result){
    tr.fadeOut(1000, function(){
    $(this).remove();
    });
    }
    });
    });




@endsection

