@extends('layouts.master')

@section('styles')
    <style>
        .prescription-rows{
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .prescription-topics{
            margin-bottom: 30px;
            min-height: 80px;
        }
        .my-i-button{
            cursor: pointer;
            margin-left: 5px;
        }
        .my-i-button:hover{
            transform: scale(1.1, 1.1);
        }
        .prescription-item-add-field{
            display: none;
        }
        .select2-container--default{
            width: 100% !important;
        }
        .select2-selection--single{
            height: 33px !important;
        }
    </style>
@endsection

@section('content')
<div class="content-wrapper">
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> Manage |<small>Prescription</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/academics/default/index">Health Care</a></li>
            <li>SOP Setup</li>
            <li class="active">Manage Prescription</li>
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

        <div class="row">
            <div class="col-sm-12">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="line-height: 40px"><i class="fa fa-plus-square"></i> {{ ($prescription)?"Edit":"Create" }} Prescription </h3>
                        <div class="box-tools">
                            <a class="btn btn-primary btn-sm" href="{{url('healthcare/prescription')}}"> Back to prescription list</a></div>
                    </div>
                    <div class="box-body table-responsive">
                        <div class="row prescription-rows">
                            <div class="col-sm-12" style="text-align: center">
                                <h3>Prescription</h3>
                            </div>
                            <div class="col-sm-1">
                                <img src="{{ asset('/assets/users/images/'.$institute->logo) }}" alt="" style="width: 100%">
                            </div>
                            <div class="col-sm-6">
                                <h4><b>{{ $institute->institute_name }}</b></h4>
                                <h5>{{ $institute->address2 }}</h5>
                            </div>
                            <div class="col-sm-5" style="text-align: right">
                                <h4><b>{{ $medicalOfficer->name }}</b></h4>
                                <h5>Medical Officer</h5>
                                <h5>{{ $institute->institute_name }}</h5>
                                <h5>{{ $institute->address2 }}</h5>
                            </div>
                        </div>
                        <div class="row prescription-rows">
                            <div class="col-sm-12">
                                <div style="border-bottom: 1px solid #f1f1f1"></div>
                            </div>
                        </div>
                        <div class="card text-white bg-info mb-3" style="padding: 0 10px; margin: 10px 0">
                            <div class="row">
                                <div class="col-sm-4">
                                    <h5><b>Patient Name:</b> {{ $patient->first_name }} {{ $patient->last_name }}</h5>
                                    <h5><b>Age:</b> {{ $patientAge }}</h5>
                                    <h5><b>Sex:</b> {{ $patient->gender }}</h5>
                                </div>
                                <div class="col-sm-4">
                                    <h5><b>ID:</b> {{ $patient->id }}</h5>
                                    @if ($userType == 1)
                                        <h5><b>Title:</b> {{ $patient->title }}</h5>
                                    @elseif($userType == 2)
                                        <h5><b>Designation:</b> {{ $patient->designation()->name }}</h5>
                                    @endif
                                </div>
                                <div class="col-sm-4">
                                    <h5><b>Prescription ID:</b> {{ ($prescription)?$prescription->id:"-----" }}</h5>
                                    <h5><b>Date:</b> {{ $todayDate->format('d M, Y') }}</h5>
                                    @if ($followUpPrescription)
                                    <h5><b>Follow Up Prescription ID:</b> {{ $followUpPrescription->id }}</h5>
                                    @endif
                                    @if ($prescription)    
                                        @if ($prescription->follow_up)
                                            <h5><b>Follow Up Prescription ID:</b> {{ $prescription->follow_up }}</h5>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row prescription-rows">
                            <div class="col-sm-4">
                                <div class="prescription-topics" data-topic="clinicalHistory">
                                    <h4><b>Clinical History: </b><i class="fa fa-plus-circle text-primary my-i-button show-field-btn"></i></h4> 
                                    
                                    <ul class="topic-list">
                                    </ul>
                                    <div class="row prescription-item-add-field">
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control input-field">
                                        </div>
                                        <div class="col-sm-4">
                                            <button class="btn btn-success add-btn">Add</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="prescription-topics" data-topic="physicalExamination">
                                    <h4><b>Physical Examination: </b><i class="fa fa-plus-circle text-primary my-i-button show-field-btn"></i></h4> 
                                    
                                    <ul class="topic-list">
                                    </ul>
                                    <div class="row prescription-item-add-field">
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control input-field">
                                        </div>
                                        <div class="col-sm-4">
                                            <button class="btn btn-success add-btn">Add</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="prescription-topics" data-topic="investigation">
                                    <h4><b>Investigation: </b><i class="fa fa-plus-circle text-primary my-i-button show-field-btn"></i></h4> 
                                    
                                    <ul class="topic-list">
                                    </ul>
                                    <div class="row prescription-item-add-field">
                                        <div class="col-sm-8">
                                            <select name="" id="" class="form-control input-field">
                                                <option value="">--Choose Investigation--</option>
                                                @foreach ($investigations as $investigation)
                                                    <option value="{{$investigation->id}}">{{$investigation->title}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <button class="btn btn-success add-btn">Add</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-8" style="border-left: 1px solid #f1f1f1">
                                <div class="prescription-topics" data-topic="diagnosis">
                                    <h4><b>Diagnosis: </b><i class="fa fa-plus-circle text-primary my-i-button show-field-btn"></i></h4> 
                                    
                                    <ul class="topic-list">
                                    </ul>
                                    <div class="row prescription-item-add-field">
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control input-field">
                                        </div>
                                        <div class="col-sm-4">
                                            <button class="btn btn-success add-btn">Add</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="prescription-topics" data-topic="treatment">
                                    <h4><b>Treatment: </b><i class="fa fa-plus-circle text-primary my-i-button show-field-btn"></i></h4> 
                                    
                                    <ul class="topic-list">
                                    </ul>
                                    <div class="row prescription-item-add-field">
                                        <div class="col-sm-4">
                                            <select id="medicine-field" class="form-control input-field">
                                                <option value="">--Choose Medicine--</option>
                                                @foreach ($drugs as $drug)
                                                    <option value="{{ $drug->id }}">{{ $drug->product_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control interval-field" placeholder="x+x+x">
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="number" class="form-control days-field" placeholder="Days">
                                        </div>
                                        <div class="col-sm-8" style="margin-top: 15px">
                                            <input type="text" class="form-control comment-field" placeholder="Comments">
                                        </div>
                                        <div class="col-sm-4" style="margin-top: 15px">
                                            <button class="btn btn-success add-btn">Add</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="prescription-topics" data-topic="advice">
                                    <h4><b>Advice: </b><i class="fa fa-plus-circle text-primary my-i-button show-field-btn"></i></h4> 
                                    
                                    <ul class="topic-list">
                                    </ul>
                                    <div class="row prescription-item-add-field">
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control input-field">
                                        </div>
                                        <div class="col-sm-4">
                                            <button class="btn btn-success add-btn">Add</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="prescription-topics" data-topic="excuse">
                                    <h4><b>Excuse: </b><i class="fa fa-plus-circle text-primary my-i-button show-field-btn"></i></h4> 
                                    
                                    <ul class="topic-list">
                                    </ul>
                                    <div class="row prescription-item-add-field">
                                        <div class="col-sm-3">
                                            <label for="">End Date:</label>
                                            <input type="date" class="form-control date-field">
                                        </div>
                                        <div class="col-sm-5">
                                            <label for="">Comment: </label>
                                            <input type="text" class="form-control input-field">
                                        </div>
                                        <div class="col-sm-4">
                                            <button class="btn btn-success add-btn" style="margin-top: 23px">Add</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if (!$prescription)    
                            <div class="row prescription-rows" style="text-align: right">
                                <div class="col-sm-12"><input type="checkbox" class="admit-check"> Admit</div>
                            </div>
                        @endif
                        <div class="row prescription-rows" style="text-align: right">
                            <div class="col-sm-12"><b>Current Status: </b> 
                                @if ($prescription)
                                    @if ($prescription->status == 1)
                                        Pending
                                    @elseif ($prescription->status == 2)
                                        Admitted
                                    @elseif ($prescription->status == 3)
                                        Closed
                                    @endif
                                @else
                                    Pending
                                @endif
                            </div>
                        </div>
                        <div class="row prescription-rows" style="text-align: right">
                            <div class="col-sm-12">
                                @if ($prescription)
                                    <a href="{{ url('/healthcare/print/prescription/'.$prescription->id) }}" class="btn btn-primary"><i class="fa fa-print"></i> Print</a>
                                    @if ($prescription->status == 1)
                                        <a href="{{ url('/healthcare/prescription/status/change/'.$prescription->id.'/2') }}" class="btn btn-info">Admit</a>
                                        <a href="{{ url('/healthcare/prescription/status/change/'.$prescription->id.'/3') }}" class="btn btn-danger">Close</a>
                                        <button class="btn btn-success" id="save-prescription">Update</button>
                                    @elseif ($prescription->status == 2)
                                        <a href="{{ url('/healthcare/prescription/status/change/'.$prescription->id.'/3') }}" class="btn btn-danger">Close</a>
                                        <button class="btn btn-success" id="save-prescription">Update</button>
                                    @elseif ($prescription->status == 3)
                                        <a href="{{ url('/healthcare/prescription/status/change/'.$prescription->id.'/1') }}" class="btn btn-success">Re Open</a>
                                    @endif
                                @else
                                    <button class="btn btn-success" id="save-prescription">Save As Completed</button>
                                @endif
                            </div>
                        </div>
                        <div class="row prescription-rows">
                            <div class="col-sm-12">
                                <div style="border-bottom: 1px solid #f1f1f1"></div>
                            </div>
                        </div>
                        <div class="row prescription-rows">
                            <div class="col-sm-12" style="text-align: center">
                                <div>{{ $institute->institute_name }}</div>
                                <div>{{ $institute->address1 }}</div>
                                <div><b>Website: </b>{{ $institute->website }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <form action="{{ url(($prescription)?'/healthcare/update/prescription/'.$prescription->id:'/healthcare/store/prescription') }}" method="POST">
            @csrf

            <input type="hidden" name="patientType" value="{{ $userType }}">
            <input type="hidden" name="patientId" value="{{ $patient->id }}">
            <input type="hidden" name="followUp" value="{{ ($followUpPrescription)?$followUpPrescription->id:'' }}">
            <input type="hidden" name="content" class="prescription-content-field">
            <input type="hidden" class="prescription-status-field" name="status" value="1">

            <button id="prescription-submit-btn" style="display: none"></button>
        </form>

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
    @php
        $phpPreviousPrescriptionDatas = null;
        if($prescription){
            $phpPreviousPrescriptionDatas = json_decode($prescription->content);
        } elseif ($followUpPrescription) {
            $phpPreviousPrescriptionDatas = json_decode($followUpPrescription->content);
        }
    @endphp
</div>
@endsection



{{-- Scripts --}}

@section('scripts')
<script>
    $(document).ready(function () {
        $('#prescriptionTable').DataTable();

        $('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });

        $('#medicine-field').select2();

        var prescriptionDatas = {
            clinicalHistories: [],
            diagnosis: [],
            physicalExaminations: [],
            treatments: [],
            investigations: [],
            advices: [],
            excuses: []
        };

        var previousPrescriptionDatas = {!! json_encode($phpPreviousPrescriptionDatas) !!};

        $('.show-field-btn').click(function () {
            var parent = $(this).parent().parent();
            parent.find('.prescription-item-add-field').css('display', 'block');
            $(this).css('display', 'none');
        });

        function generateLists(parent, datas) {
            var txt = '';
            datas.forEach((element, index) => {
                txt += '<li>'+element+' <i class="fa fa-minus-circle text-danger my-i-button remove-btn" data-index="'+index+'"></i></li>';
            });
            parent.find('.topic-list').html(txt);
        }

        function generateAllLists() {
            var prescriptionTopics = $('.prescription-topics');

            prescriptionTopics.each((index, element) => {
                var topic = $(element).data('topic');

                if (topic == "clinicalHistory") {
                    generateLists($(element), prescriptionDatas.clinicalHistories);
                } else if(topic == "physicalExamination"){
                    generateLists($(element), prescriptionDatas.physicalExaminations);
                } else if(topic == "diagnosis"){
                    generateLists($(element), prescriptionDatas.diagnosis);
                } else if(topic == "advice"){
                    generateLists($(element), prescriptionDatas.advices);
                } else if(topic == "excuse"){
                    var myArray = [];
                    var txt = '';

                    prescriptionDatas.excuses.forEach(element => {
                        var startDate = new Date(element.startDate);
                        var endDate = new Date(element.endDate);

                        txt = startDate.getDate()+'/'+startDate.getMonth()+'/'+startDate.getFullYear()+
                        ' - '+endDate.getDate()+'/'+endDate.getMonth()+'/'+endDate.getFullYear()+
                        ' ('+element.days+' days): '+element.comment;
                        myArray.push(txt);
                    });
                    generateLists($(element), myArray);
                } else if(topic == "investigation"){
                    var myArray = [];
                    prescriptionDatas.investigations.forEach(element => {
                        myArray.push(element.title);
                    });
                    generateLists($(element), myArray);
                } else if(topic == "treatment"){
                    var myArray = [];
                    var txt = '';
                    prescriptionDatas.treatments.forEach(element => {
                        var endDate = new Date(element.endDate);

                        txt = '<div>'+element.drugName+' (Qty: '+element.quantity+')</div>'+
                        element.interval+' - '+element.comment+' - '+element.days+'days (Till '+
                        endDate.getDate()+'/'+endDate.getMonth()+'/'+endDate.getFullYear()+')';
                        myArray.push(txt);
                    });
                    generateLists($(element), myArray);
                }
            });
        }

        if (previousPrescriptionDatas) {
            prescriptionDatas = previousPrescriptionDatas;
            generateAllLists();
        }

        $('.add-btn').click(function () {
            var parent = $(this).parent().parent().parent();
            var topic = parent.data('topic');
            var value = parent.find('.input-field').val();

            if (value) {
                if (topic == "clinicalHistory") {
                    prescriptionDatas.clinicalHistories.push(value);
                } else if(topic == "physicalExamination"){
                    prescriptionDatas.physicalExaminations.push(value);
                } else if(topic == "diagnosis"){
                    prescriptionDatas.diagnosis.push(value);
                } else if(topic == "advice"){
                    prescriptionDatas.advices.push(value);
                } else if(topic == "excuse"){
                    var startDate = new Date();
                    var endDate = new Date(parent.find('.date-field').val());
                    var myObj = {
                        startDate: startDate.getFullYear()+'-'+startDate.getMonth()+'-'+startDate.getDate(),
                        endDate: parent.find('.date-field').val(),
                        days: Math.round((endDate - startDate)/(1000*60*60*24)),
                        comment: value
                    }
                    prescriptionDatas.excuses.push(myObj);
                } else if(topic == "investigation"){
                    var investigationTitle = parent.find('.input-field option:selected').text();
                    var myObj = {id: value, title: investigationTitle};
                    prescriptionDatas.investigations.push(myObj);
                } else if(topic == "treatment"){
                    var drugName = parent.find('.input-field option:selected').text();
                    var interval = parent.find('.interval-field').val();
                    var days = parent.find('.days-field').val();
                    var comment = parent.find('.comment-field').val();
                    var startDate = new Date();
                    var endDate = new Date();
                    endDate.setDate(startDate.getDate()+parseInt(days));
                    perDayMedicine = 0;
                    perDayArr = interval.split("+");
                    perDayArr.forEach(element => {
                        perDayMedicine += parseInt(element);
                    });

                    var myObj = {
                        drugId: value,
                        drugName: drugName,
                        interval: interval,
                        days: days,
                        comment: comment,
                        startDate: startDate.getFullYear()+'-'+startDate.getMonth()+'-'+startDate.getDate(),
                        endDate: endDate.getFullYear()+'-'+endDate.getMonth()+'-'+endDate.getDate(),
                        quantity: perDayMedicine * parseInt(days)
                    }

                    prescriptionDatas.treatments.push(myObj);
                    parent.find('.interval-field').val('');
                    parent.find('.days-field').val('');
                    parent.find('.comment-field').val('');
                }

                generateAllLists();
                parent.find('.input-field').val('');
                parent.find('.prescription-item-add-field').css('display', 'none');
                parent.find('.show-field-btn').css('display', 'inline-block');
            }else{
                swal('Error!', 'Please input valid data first.', 'error');
            }
        });

        $(document).on('click', '.remove-btn', function () {
            var parent = $(this).parent().parent().parent();
            var topic = parent.data('topic');
            var index = $(this).data('index');
            
            if (topic == "clinicalHistory") {
                prescriptionDatas.clinicalHistories.splice(index, 1);
            } else if(topic == "physicalExamination"){
                prescriptionDatas.physicalExaminations.splice(index, 1);
            } else if(topic == "diagnosis"){
                prescriptionDatas.diagnosis.splice(index, 1);
            } else if(topic == "advice"){
                prescriptionDatas.advices.splice(index, 1);
            } else if(topic == "excuse"){
                prescriptionDatas.excuses.splice(index, 1);
            } else if(topic == "investigation"){
                prescriptionDatas.investigations.splice(index, 1);
            } else if(topic == "treatment"){
                prescriptionDatas.treatments.splice(index, 1);
            }
            generateAllLists();
        });

        $('#save-prescription').click(function () {
            $('.prescription-content-field').val(JSON.stringify(prescriptionDatas));  
            $('#prescription-submit-btn').click();
        });

        $('.admit-check').click(function () {
            if ($(this).prop('checked') == true) {
                $('.prescription-status-field').val(2);   
            }else {
                $('.prescription-status-field').val(1);     
            }
        });
    });
</script>
@stop