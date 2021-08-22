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
        <form  action="{{URL::to('/fee/fund/store')}}" method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <input type="hidden" name="feefund_id" @if(!empty($feefundProfile)) value="{{$feefundProfile->id}}" @endif>
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="control-label" for="feefund">Fund Name</label>
                        <input class="form-control" type="text"  @if(!empty($feefundProfile)) value="{{$feefundProfile->name}}" @endif name="name"/>
                        <div class="help-block"></div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-success">Submit</button>
        </form>
    </div>
    {{--end free head --}}

    {{--fee head list--}}
    @if(!empty($feefunds) && ($feefunds->count()>0))
        <table id="feefund" class="table table-striped table-bordered" style="margin-top: 20px">
            <thead>
            <tr>
                <th>SL NO</th>
                <th>Fees Head</th>
                <th>Action</th>
            </tr>

            </thead>
            <tbody>
            @php $i=1; @endphp
            @foreach($feefunds as $fund)
                <tr class="gradeX">
                    <td>{{$i++}}</td>
                    <td>{{$fund->name}}</td>
                    <td>
                        <a href="/fee/fund/edit/{{$fund->id}}" class=" btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
                        {{--<a id="1" class="feetype_delete_class btn btn-danger btn-xs" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>--}}
                    </td>
                </tr>
            @endforeach


            </tbody>
        </table>
    @else
        <div class="alert alert-warning">
            Fee Fund not found! create a new fee head
        </div>

    @endif

@endsection


