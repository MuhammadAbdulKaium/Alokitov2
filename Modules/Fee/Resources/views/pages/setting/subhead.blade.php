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
        <form  action="{{URL::to('/fee/feesubhead/store')}}" method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <input type="hidden" name="feesubhead_id" @if(!empty($feesubheadProfile)) value="{{$feesubheadProfile->id}}" @endif>
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="control-label" for="feesubhead">Fee SubHead Name</label>
                        <input class="form-control" type="text"  @if(!empty($feesubheadProfile)) value="{{$feesubheadProfile->name}}" @endif name="name"/>
                        <div class="help-block"></div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-success">Submit</button>
        </form>
    </div>
    {{--end free head --}}

    {{--fee head list--}}
    @if(!empty($feesubHeads) && ($feesubHeads->count()>0))
        <table id="feehead" class="table table-striped table-bordered" style="margin-top: 20px">
            <thead>
            <tr>
                <th>SL NO</th>
                <th>Fees Head</th>
                <th>Action</th>
            </tr>

            </thead>
            <tbody>
            @php $i=1; @endphp
            @foreach($feesubHeads as $subhead)
                <tr class="gradeX">
                    <td>{{$i++}}</td>
                    <td>{{$subhead->name}}</td>
                    <td>
                        <a href="/fee/feesubhead/edit/{{$subhead->id}}/" class=" btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
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


