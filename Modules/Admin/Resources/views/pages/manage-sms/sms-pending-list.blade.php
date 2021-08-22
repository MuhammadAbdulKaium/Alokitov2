@extends('admin::layouts.master')

@section('content')
    <section class="content">
        <h1><i class="fa fa-plus-square"></i>  Institution SMS</h1>
        <div class="box box-solid">
            <div class="et">

            </div>
            <form id="sms_search_form" method="POST">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="box-body">
                    <h3 class="box-title"><i class="fa fa-search"></i> Search SMS</h3>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label" for="academic_year">Institution (*)</label>
                                <select id="instituion" class="form-control instituion" name="instituion" required="">
                                    <option value="">--- Select Institution---</option>
                                    @foreach($instituteList as $institution)
                                        <option value="{{$institution->id}}">{{$institution->institute_name}}</option>
                                        @endforeach
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label" for="academic_level">Campus (*)</label>
                                <select id="campus" required class="form-control campus" name="campus">
                                    <option value="" selected="">--- Select Campus ---</option>
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label" for="start_date">Start Date</label>
                                <input type="text"  id="start_date" name="start_date" class="form-control" placeholder="Start Date"  value="">
                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group ">
                                <label class="control-label">End Date</label>
                                <input type="text" id="end_date" class="form-control" name="end_date" value="" placeholder="End Date">
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                    {{--<div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="section">Section</label>
                                <select id="section" class="form-control academicSection" name="section">
                                    <option value="" selected="">--- Select Section ---</option>

                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-4" style="margin-top: 25px;">
                            <div class="form-group">
                                <input id="gr_no" class="form-control" name="gr_no" placeholder="Enter Gr.No" type="text">
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-4" style="margin-top: 25px;">
                            <div class="form-group">
                                <input id="email" class="form-control" name="email" placeholder="Enter Student Email Id." type="text">
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>--}}
                </div>
                <!-- ./box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-info pull-right">Search</button>
                    <button type="reset" class="btn btn-default">Reset</button>
                </div>
            </form>
        </div>

        <div id="std_list_container_row" class="row">
        </div>
    </section>
    <section class="content">


      <div id="pending_response">

      </div>
    </section>
@endsection


{{-- Scripts --}}
@section('scripts')

    <script>
        $(document).ready(function(){
            $('#start_date').datepicker({format: 'dd-mm-yyyy'});
            $('#end_date').datepicker({format: 'dd-mm-yyyy'});

            // get campuses after selecting an institution id
            // request for batch list using level id
            jQuery(document).on('change','.instituion',function(){
                // console.log("hmm its change");

                // get academic year id
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
                        //console.log(data.length);
                        //create campus list
                        op+='<option value="0" selected>--- Select Campus ---</option>';
                        for(var i=0;i<data.length;i++){
                            // console.log(data[i].level_name);
                            op+='<option value="'+data[i].id+'">'+data[i].name+'</option>';

                            // set value to the campus
                            $('.campus').html("");
                            $('.campus').append(op);
                        }
                    },

                    error:function(){

                    }
                });
            });

            // SMS search form submit
            $('form#sms_search_form').on('submit', function (e) {
                e.preventDefault();
                // ajax request
                $.ajax({
                    url: "/communication/sms/pending",
                    type: 'POST',
                    cache: false,
                    data: $('form#sms_search_form').serialize(),
                    datatype: 'application/json',

                    beforeSend: function() {
                        // show waiting dialog
                        waitingDialog.show('Loading...');
                    },

                    success:function(data){
                        // hide waiting dialog
                        waitingDialog.hide();
                        // checking
                       $("#pending_response").html(data);
                    },

                    error:function(data){

                    }
                });
            });


        });
    </script>
@endsection
