@extends('fees::layouts.fees_report_master')
@section('section-title')
    <h1><i class="fa fa-plus-square"></i>Fees Collections</h1>

@endsection
<!-- page content -->
@section('page-content')
    <!-- grading scale -->
    <div class="box-solid box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-search"></i> Find</h3>
        </div>
        <div class="box-body">
            <form id="fees_report">		<div class="row">
                    <div class="col-sm-6">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <div class="form-group field-batches-batch_academic_year_id required">
                            <label class="control-label" for="batches-batch_academic_year_id">Academic Year</label>
                            <select id="academic_year" class="academicYear form-control" required name="academic_year" aria-required="true">
                                <option value="">--- Select Academic Year ---</option>
                                @if(!empty($academicsYears))
                                    @foreach($academicsYears as $year )
                                        <option class="YearId" value="{{$year->id}}">{{$year->year_name}}</option>
                                    @endforeach;
                                  @endif
                            </select>

                            <div class="help-block"></div>
                        </div>			</div>
                    <div class="col-sm-6">
                        <div class="form-group field-batches-batch_course_id required">
                            <label class="control-label" for="batches-batch_course_id">Course</label>
                            <select id="academic_level" class="form-control academicLevel" name="academic_level" required>
                                <option value="" disabled="true" selected="true">--- Select Level ---</option>
                            </select>

                            <div class="help-block"></div>
                        </div>			</div>
                </div><!-- /row-->
                <div class="box-footer">
                    <button type="submit"   class="btn btn-primary btn-create">Search</button>	</div>
            </form>    </div><!-- /box-body-->


    </div>


        <div id="fees_report_table_container" class="box-body table-responsive"></div>


@endsection

@section('page-script')

    // request for batch list using level id
    $(".academicYear").on('change',function(){

    // get Year Id and find academic Level

    // get academic year id
    var year_id = $(this).val();
    var div = $(this).parent();
    var op="";

    $.ajax({
        url: "{{ url('/academics/find/level') }}",
        type: 'GET',
        cache: false,
        data: {'id': year_id }, //see the $_token
        datatype: 'application/json',
        beforeSend: function() {
    },

     success:function(data){

    op+='<option value="0" selected disabled>--- Select Level ---</option>';
        for(var i=0;i<data.length;i++){
        // console.log(data[i].level_name);
        op+='<option value="'+data[i].id+'">'+data[i].level_name+'</option>';
      }

        // set value to the academic batch
        $('.academicLevel').html("");
        $('.academicLevel').append(op);
        },
          error:function(){

            }
        });

    });

        // ajax search here
        $('form#fees_report').on('submit', function (e) {
            e.preventDefault();
        $.ajax({

            url: '/fees/report/search',
            type: 'GET',
            cache: false,
            data: $('form#fees_report').serialize(),
            datatype: 'json/application',

            beforeSend: function() {
                {{--alert($('form#fees_and_fees_item_form').serialize());--}}
            },

            success:function(data){
                $('#fees_report_table_container').html('');
                $('#fees_report_table_container').append(data);
                {{--alert(JSON.stringify(data));--}}
            },

            error:function(data){
                alert(JSON.stringify(data));
            }
        });

        });


    // ajax pagination

    $('body').on('click', '.pagination a', function(e) {
    e.preventDefault();
    var url = $(this).attr('href');
    getfeesCollections(url);
    {{--window.history.pushState("", "", url);--}}
    });

    function getfeesCollections(url) {
    $.ajax({
        url : url
    }).done(function (data) {

    $('#fees_report_table_container').html('');
    $('#fees_report_table_container').append(data);
    {{--$('#batch_payment_list_row').html('');--}}
    {{--$('#batch_payment_list_row').append(data);--}}
    {{--//                alert("sdfsad");--}}

    }).fail(function () {
    alert('Articles could not be loaded.');
    });
    }




@endsection

