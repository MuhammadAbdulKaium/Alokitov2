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
        <form  action="{{URL::to('/fee/feehead/store')}}" method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <input type="hidden" name="feehead_id" @if(!empty($feeheadProfile)) value="{{$feeheadProfile->id}}" @endif>
            <div class="row">
                <div class="col-sm-3">
                 <div class="form-group">
                        <label class="control-label" for="feehead">Fee Head Name</label>
                        <input class="form-control" type="text"  @if(!empty($feeheadProfile)) value="{{$feeheadProfile->name}}" @endif name="name"/>
                        <div class="help-block"></div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="control-label" for="feehead">Select Credit Ledger</label>
                        <select name="ledger_id" class="form-control" id="ledger_id">
                            <option value="">Select Ledger</option>
                            @foreach($lagers as $lager)
                                <option value="{{$lager->id}}">{{$lager->name}}</option>
                            @endforeach
                        </select>
                        <div class="help-block"></div>
                    </div>
                </div>
            </div>
           <button type="submit" class="btn btn-success">Submit</button>
        </form>
        </div>
        {{--end free head --}}

        {{--fee head list--}}
        @if(!empty($feeHeads) && ($feeHeads->count()>0))
              <table id="feehead" class="table table-striped table-bordered" style="margin-top: 20px">
                        <thead>
                        <tr>
                            <th>SL NO</th>
                            <th>Fees Head</th>
                            <th>Ledger Name</th>
                            <th>Action</th>
                        </tr>

                        </thead>
                        <tbody>
                    @php $i=1; @endphp
                    @foreach($feeHeads as $head)
                        <tr class="gradeX">
                            <td>{{$i++}}</td>
                            <td>{{$head->name}}</td>
                            <td>@if(!empty($head->ledger()))
                                {{$head->ledger()->name}}
                                @else
                                    Ledger not assign
                                @endif
                            </td>
                            <td>
                                <a href="/fee/feehead/edit/{{$head->id}}" class=" btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
                                {{--<a id="1" class="feetype_delete_class btn btn-danger btn-xs" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>--}}
                            </td>
                        </tr>
                        @endforeach


                        </tbody>
                    </table>
    @else
            <div class="alert alert-warning">
                Fee head not found! create a new fee head
            </div>

    @endif

 @endsection


