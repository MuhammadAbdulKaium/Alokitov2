@extends('fee::layouts.feecollection')
<!-- page content -->
@section('page-content')
    <link rel="stylesheet" type="text/css" href="{{URL::to('css/datatable.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::to('css/datatables/dataTables.bootstrap.css')}}">

    <style>
        .userprofile{
            padding: 15px;
            border: 2px solid #efefef;
            border-radius: 10px;
        }
    </style>

    <div class="box-body">
        {{--fee head create--}}
        <form id="singleStudentSearchForm"  method="post">
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="control-label" for="feehead">Select Year</label>
                        <select class="form-control" id="year" name="year_id">
                            <option value="">Select Year</option>
                            @foreach($academicYearList as $year)
                                <option value="{{$year->id}}">{{$year->year_name}}</option>
                            @endforeach
                        </select>
                        <div class="help-block"></div>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="control-label" for="feehead">Student ID</label>
                        <input class="form-control" id="std_name" name="payer_name" type="text" value="@if(!empty($allInputs)) {{$allInputs->payer_name}} @endif " placeholder="Type Student Name">
                        <input id="std_id" name="std_id" type="hidden" value="@if(!empty($allInputs)) {{$allInputs->payer_id}}  @endif"/>
                        <div class="help-block"></div>
                    </div>
                </div>

            </div>
            <button type="submit"  class="btn btn-success">Search</button>
            <button type="reset" class="btn btn-primary" value="Reset">Reset</button>
        </form>
    </div>
    <div class="box-body singleStudentInvoiceList table-responsive">

    </div>

@endsection


@section('page-script')
    <script>


        //        $('#subheadFine').submit(validate);

        // get subhead by head id
            // console.log("hmm its change");
            $('#singleStudentSearchForm').submit(function(e) {
                e.preventDefault();
                // ajax request
                $.ajax({
                    url: '/fee/invoice/single/student',
                    type: 'POST',
                    cache: false,
                    data: $('form#singleStudentSearchForm').serialize(),
                    datatype: 'json/application',

                    beforeSend: function() {
                        {{--alert($('form#Partial_allowForm').serialize());--}}
                    },

                    success:function(data){
                        $('.singleStudentInvoiceList').html("");
                        $('.singleStudentInvoiceList').append(data);
                    },

                    error:function(data){
                        {{--alert(JSON.stringify(data));--}}
                    }
                });

            });


        // get student name and select auto complete

        $('#std_name').keypress(function() {
            $(this).autocomplete({
                source: loadFromAjax,
                minLength: 1,

                select: function(event, ui) {
                    // Prevent value from being put in the input:
                    this.value = ui.item.label;
                    // Set the next input's value to the "value" of the item.
                    $(this).next("input").val(ui.item.id);
                    event.preventDefault();
                }
            });

            /// load student name form
            function loadFromAjax(request, response) {
                var term = $("#std_name").val();
                $.ajax({
                    url: '/student/find/student',
                    dataType: 'json',
                    data: {
                        'term': term
                    },
                    success: function(data) {
                        // you can format data here if necessary
                        response($.map(data, function(el) {
                            return {
                                label: el.name,
                                value: el.name,
                                id: el.id
                            };
                        }));
                    }
                });
            }
        });



    </script>

@endsection