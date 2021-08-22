@extends('fees::layouts.fees_report_master')
@section('section-title')
    <h1><i class="fa fa-plus-square"></i>Fees Setting View</h1>

@endsection
<!-- page content -->
@section('page-content')
    <!-- grading scale -->
    <div class="box box-solid">
        <div class="box-header with-border">

            <ul class="nav nav-tabs">
                <li @if($page == "fine") class="active" @endif  id="#">
                    <a href="{{url('/fees/setting/view/fine')}}">Fine Fees</a>
                </li>
                <li @if($page == "tuitionfee") class="active" @endif  id="#">
                    <a href="{{url('/fees/setting/view/tuitionfee')}}">Tution Fees</a>
                </li>
            </ul>

            <div class="tab-content">
                <div id="fine" class="tab-pane fade in active">
                        <div class="box-body table-responsive">
                            <div id="p0" data-pjax-container="" data-pjax-push-state="" data-pjax-timeout="10000">
                                <div id="w1" class="grid-view">

                                    <table id="myTable" class="table table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th><a data-sort="sub_master_code">Attribute Name</a></th>
                                            <th><a data-sort="sub_master_alias">Value</a></th>
                                            <th><a data-sort="sub_master_alias">Created At</a></th>
                                        </tr>

                                        </thead>
                                        <tbody>
                                        @php $i=1; @endphp
                                        @foreach($tuitionSettings as $tuition)
                                        <tr class="gradeX">
                                            <td>{{$i++}}</td>
                                            <td>{{$tuition->attribute}}</td>
                                            <td>{{$tuition->value}} BDT</td>
                                            <td>{{$tuition->created_at->diffForHumans()}}</td>
                                        </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>


                </div>
            </div>

        </div>
    </div>


     @endsection

 @section('page-script')



@endsection

