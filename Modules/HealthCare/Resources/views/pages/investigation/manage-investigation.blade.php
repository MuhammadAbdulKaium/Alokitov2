@extends('layouts.master')

@section('content')
<div class="content-wrapper">
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> Manage |<small>Investigation</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/academics/default/index">Health Care</a></li>
            <li>SOP Setup</li>
            <li class="active">Investigation</li>
        </ul>
    </section>
    <section class="content">
        @if(Session::has('message'))
            <p class="alert alert-success alert-auto-hide" style="text-align: center"> <a href="#" class="close"
                style="text-decoration:none" data-dismiss="alert"
                aria-label="close">&times;</a>{{ Session::get('message') }}</p>
        @elseif(Session::has('alert'))
            <p class="alert alert-warning alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('alert') }}</p>
        @elseif(Session::has('errorMessage'))
            <p class="alert alert-danger alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('errorMessage') }}</p>
        @endif

        @php
            $bladeReportPattern = null;
            if($healthInvestigation){
                $bladeReportPattern = json_decode($healthInvestigation->report_pattern);
            }
        @endphp

        <div class="row">
            <div class="col-sm-12">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="line-height: 40px"><i class="fa fa-plus-square"></i> {{ ($healthInvestigation)?'Update':'Create' }} Investigation</h3>
                    </div>
                    <div class="box-body table-responsive">
                        <form action="{{ url(($healthInvestigation)?'healthcare/update/investigation/'.$healthInvestigation->id:'healthcare/store/investigation') }}" method="post" id="investigationForm">
                            @csrf

                            <input type="hidden" name="reportPattern" class="report-pattern-json-field">

                            <div class="row">
                                <div class="col-sm-3">
                                    <label for="">Report Type:</label>
                                    <input type="text" name="reportType" class="form-control" value="{{ ($healthInvestigation)?$healthInvestigation->report_type:'' }}" required>
                                </div>
                                <div class="col-sm-3">
                                    <label for="">Title:</label>
                                    <input type="text" name="title" class="form-control" value="{{ ($healthInvestigation)?$healthInvestigation->title:'' }}" required>
                                </div>
                                <div class="col-sm-2">
                                    <label for="">Sample:</label>
                                    <input type="text" name="sample" class="form-control" value="{{ ($healthInvestigation)?$healthInvestigation->sample:'' }}" required>
                                </div>
                                <div class="col-sm-2">
                                    <label for="">Lab ID:</label>
                                    <input type="text" name="labId" class="form-control" value="{{ ($healthInvestigation)?$healthInvestigation->lab_id:'' }}" required>
                                </div>
                            </div>

                            <button class="investigation-form-btn" style="display: none"></button>
                        </form>

                        <div class="row investigation-template-holer" style="margin-top: 20px">
                            @if ($bladeReportPattern)
                                @foreach ($bladeReportPattern as $table)
                                    <div class="col-sm-12 report-table-holder" data-table-num="{{ $loop->index }}">
                                        {!! $investigationTemplate !!}
                                    </div>
                                @endforeach
                            @else
                                <div class="col-sm-12 report-table-holder" data-table-num="0">
                                    {!! $investigationTemplate !!}
                                </div>
                            @endif
                        </div>

                        <button class="btn btn-success save-investigation-btn" style="float: right">Save</button>
                        <button class="btn btn-primary add-table-btn" style="float: right; margin-right: 10px">Add Table</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
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
    </section>
</div>
@endsection



{{-- Scripts --}}

@section('scripts')
<script>
    $(document).ready(function () {
        $('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });

        var reportPattern = [{
            title: null,
            tests: []
        }];
        var investigationTemplate = {!! json_encode($investigationTemplate) !!};
        var i = 1;
        var bladeReportPattern = {!! json_encode($bladeReportPattern) !!};

        $(document).on('click', '.add-report-view-btn', function () {
            var addReportFields = $(this).parent().parent().parent().parent().parent().children(":first");
            addReportFields.css('display', 'block');
            addReportFields.find('.add-report-btn').css('display', 'inline-block');
            addReportFields.find('.update-report-btn').css('display', 'none');
            $(this).css('display', 'none');
        });

        $('.range-type').change(function () {
            var parent = $(this).parent().parent();

            // Range type wise range field showing
            if ($(this).val() == 1) {
                parent.find('.from-range-holder').css('display', 'inline-block');
                parent.find('.to-range-holder').css('display', 'inline-block');
                parent.find('.gender-ranges').css('display', 'none');
            } else if($(this).val() == 2){
                parent.find('.from-range-holder').css('display', 'none');
                parent.find('.to-range-holder').css('display', 'none');
                parent.find('.gender-ranges').css('display', 'block');
            }
        });

        // Function for generating the table every time
        function generateTable(parent) {
            var testListHolder = parent.find('.test-list-holder');
            var tableNum = parent.data('table-num');
            var txt = '';

            reportPattern[tableNum].tests.forEach((element, index) => {
                // Range column generating for the table
                var range = '<td>'+element.fromRange+' - '+element.toRange+'</td>';
                if (element.genderRange) {
                    range = '<td>M: '+element.genderRange.fromRangeMale+' - '+element.genderRange.toRangeMale+
                        ' | F: '+element.genderRange.fromRangeFemale+' - '+element.genderRange.toRangeFemale+'</td>'
                }

                // Css style generating
                var style = 'font-size: '+element.style.fontSize+'px; font-weight: '+element.style.fontWeight+'; color: '+element.style.fontColor+'';

                // Table's all row generation
                txt += '<tr style="'+style+'"><td style="'+style+'">'+element.testName+'</td><td>'+element.unit+'</td>'+range+
                    '<td><button class="btn btn-primary btn-xs edit-test-btn" style="margin-right: 10px" data-table-no="'+tableNum+'" data-index-no="'+index+
                        '"><i class="fa fa-edit"></i></button><button class="btn btn-danger btn-xs delete-test-btn" data-table-no="'+tableNum+
                        '" data-index-no="'+index+'"><i class="fa fa-trash"></i></button></td></tr>';
            });

            testListHolder.html(txt);
        }

        // Function for generating Add/Update test field datas
        function datasFromTestField(parent) {
            // Setting the basic datas
            var datas = {
                testName: parent.find('.test-name').val(),
                unit: parent.find('.unit').val(),
                rangeType: parent.find('.range-type').val(),
                style: {
                    fontSize: parent.find('.font-size').val(),
                    fontWeight: parent.find('.font-weight').val(),
                    fontColor: parent.find('.font-color').val(),
                }
            };

            // Setting range data for different range type
            if (datas.rangeType == 1) {
                datas.fromRange = parent.find('.from-range').val();
                datas.toRange = parent.find('.to-range').val();
            }else if (datas.rangeType == 2) {
                datas.genderRange = {};
                datas.genderRange.fromRangeMale = parent.find('.from-range-male').val();
                datas.genderRange.toRangeMale = parent.find('.to-range-male').val();
                datas.genderRange.fromRangeFemale = parent.find('.from-range-female').val();
                datas.genderRange.toRangeFemale = parent.find('.to-range-female').val();
            }

            return datas;
        }

        // To generate table when edit
        if (bladeReportPattern) {
            reportPattern = bladeReportPattern;
            i = reportPattern.length;
            var reportTableHolders = $('.report-table-holder');
            var reportTableTitle = $('.report-table-title');

            reportTableHolders.each((index, value) => {
                generateTable($(value));
            });

            reportTableTitle.each((index, value) => {
                $(value).val(reportPattern[index].title);
            });
        }

        $(document).on('click', '.add-report-btn', function () {
            var parent = $(this).parent().parent().parent();
            var tableNum = parent.data('table-num');
            var datas = datasFromTestField(parent);

            if(datas.testName && datas.unit && datas.rangeType && ((datas.fromRange && datas.toRange) || datas.genderRange)){
                // Storing the data ro reportPattern variable and show in table
                if (!reportPattern[tableNum]) {
                    reportPattern[tableNum] = {
                        title: null,
                        tests: []
                    };
                }
                reportPattern[tableNum].tests.push(datas);
                generateTable(parent);

                // Hiding the form
                parent.children(":first").find('input').val('');
                parent.children(":first").css('display', 'none');
                parent.find('.add-report-view-btn').css('display', 'inline-block');
            }else{
                swal('Error!', 'Fill up all the fields first.', 'error');
            }            
        });

        $('.add-table-btn').click(function () {
            var txt = '<div class="col-sm-12" data-table-num="'+(i++)+'">'+investigationTemplate+'</div>';
            $('.investigation-template-holer').append(txt);
        });

        $(document).on('click', '.edit-test-btn', function () {
           var parent = $(this).parent().parent().parent().parent().parent();
           var tableNum = $(this).data('table-no'); 
           var indexNum = $(this).data('index-no'); 
           var test = reportPattern[tableNum].tests[indexNum];

           parent.children(":first").css('display', 'block');
           parent.children(":first").find('.add-report-btn').css('display', 'none');
           parent.children(":first").find('.update-report-btn').css('display', 'inline-block');
           parent.children(":first").find('.update-report-btn').data('index-no', indexNum);

           parent.find('.test-name').val(test.testName);
           parent.find('.unit').val(test.unit);
           parent.find('.range-type').val(test.rangeType);
           parent.find('.font-size').val(test.style.fontSize);
           parent.find('.font-weight').val(test.style.fontWeight);
           parent.find('.font-color').val(test.style.fontColor);

           if (test.rangeType == 1) {
               parent.find('.from-range').val(test.fromRange);
               parent.find('.to-range').val(test.toRange);
           } else if(test.rangeType == 2) {
               parent.find('.from-range-male').val(test.genderRange.fromRangeMale);
               parent.find('.from-range-female').val(test.genderRange.fromRangeFemale);
               parent.find('.to-range-male').val(test.genderRange.toRangeMale);
               parent.find('.to-range-female').val(test.genderRange.toRangeFemale);
           }
        });

        $(document).on('click', '.delete-test-btn', function () {
           var parent = $(this).parent().parent().parent().parent().parent();
           var tableNum = $(this).data('table-no'); 
           var indexNum = $(this).data('index-no'); 

           reportPattern[tableNum].tests.splice(indexNum, 1);

           generateTable(parent);
        });

        $(document).on('click', '.update-report-btn', function () {
            var parent = $(this).parent().parent().parent();
            var tableNum = parent.data('table-num');
            var indexNum = $(this).data('index-no');
            var datas = datasFromTestField(parent);

            if(datas.testName && datas.unit && datas.rangeType && ((datas.fromRange && datas.toRange) || datas.genderRange)){
                // Storing the data to reportPattern variable and show in table
                reportPattern[tableNum].tests[indexNum] = datas;
                generateTable(parent);

                // Hiding the form
                parent.children(":first").find('input').val('');
                parent.children(":first").css('display', 'none');
                parent.find('.add-report-view-btn').css('display', 'inline-block');
            }else{
                swal('Error!', 'Fill up all the fields first.', 'error');
            }
        });

        $('.save-investigation-btn').click(function () {
            var reportTableTitle = $('.report-table-title');
            var titleEmpty = false;
            var tableEmpty = false;

            reportTableTitle.each((index, value) => {
                var i = $(value).parent().parent().parent().parent().parent().data('table-num');
                if (reportPattern[i]) {
                    reportPattern[i].title = $(value).val();
                    if (!$(value).val()) {
                        titleEmpty = true;
                    }
                }else{
                    tableEmpty = true;
                }                
            });

            if (tableEmpty) {
                swal('Error!', 'Can not save empty table..', 'error');
            }else if (titleEmpty) {
                swal('Error!', 'Fill up all the table title fields first.', 'error');
            }else{
                $('.report-pattern-json-field').val(JSON.stringify(reportPattern));
                $('.investigation-form-btn').click(); 
            }            
        });
    });
</script>
@stop