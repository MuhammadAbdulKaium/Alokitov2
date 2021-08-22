@extends('fee::layouts.feesetting')
<!-- page content -->
@section('page-content')
    <!-- grading scale -->
    <div class="box-body">

        @if(Session::has('message'))
            <div class="alert alert-success" role="alert">
                {{ Session::get('message') }}</div>
        @endif
        {{--fee head create--}}
        <form  action="{{URL::to('fee/setting/absent-fine')}}" method="post">
            <div class="row">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="control-label" for="feehead">Select Class</label>
                        <select name="class_id" class="form-control academicBatch" id="class_id">
                            <option value="">Select Class</option>
                            @foreach($batchs as $batch)
                                @if($batch->get_division())
                                    <option value="{{$batch->id}}">{{$batch->batch_name.' '.$batch->get_division()->name}}</option>
                                @else
                                    <option value="{{$batch->id}}">{{$batch->batch_name}}</option>
                                @endif
                            @endforeach
                        </select>
                        <div class="help-block"></div>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="control-label" for="feehead">Select Period</label>
                        <select class="form-control" name="period" id="period">
                            <option value="">Select Period</option>
                            <option value="1">1st Period</option>
                            <option value="2">2nd Period</option>
                            <option value="3">3rd Period</option>
                            <option value="4">4th Period</option>
                            <option value="5">5th Period</option>
                        </select>
                        <div class="help-block"></div>
                    </div>
                </div>



                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="control-label" for="feehead">Fine Amount</label>
                        <input class="form-control" type="text" name="amount"/>
                        <div class="help-block"></div>
                    </div>
                </div>

            </div>
            <button type="submit" class="btn btn-success">Submit</button>
        </form>
    </div>
    {{--end free head --}}

    {{--fee head list--}}
    <table id="feehead" class="table table-striped table-bordered" style="margin-top: 20px">
        <thead>
        <tr>
            <th># NO</th>
            <th>Class</th>
            <th>Period</th>
            <th>Amount</th>
            <th>Action</th>
        </tr>

        </thead>
        <tbody>
@foreach($absentFineSettingList as $absentFine)
        <tr class="gradeX">
            <td>1</td>
            <td>{{$absentFine->class}}</td>
            <td>{{$absentFine->period}}</td>
            <td>{{$absentFine->amount}}</td>
            <td>
                <a href="/fees/feetype/edit/1" class=" btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
                <a id="1" class="feetype_delete_class btn btn-danger btn-xs" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
            </td>
        </tr>
@endforeach

        </tbody>
    </table>

@endsection


