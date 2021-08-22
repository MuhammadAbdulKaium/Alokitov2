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
        <form  action="{{URL::to('/fee/waivertype/store')}}" method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <input type="hidden" name="feewaiverType_id" @if(!empty($feewaiverTypeProfile)) value="{{$feewaiverTypeProfile->id}}" @endif>
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="control-label" for="feesubhead">Waiver Type</label>
                        <input class="form-control" type="text"  @if(!empty($feewaiverTypeProfile)) value="{{$feewaiverTypeProfile->name}}" @endif name="name"/>
                        <div class="help-block"></div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-success">Submit</button>
        </form>
    </div>
    {{--end free head --}}

    {{--fee head list--}}
    @if(!empty($feewaiverTypes) && ($feewaiverTypes->count()>0))
        <table id="feehead" class="table table-striped table-bordered" style="margin-top: 20px">
            <thead>
            <tr>
                <th>SL NO</th>
                <th>Name</th>
                <th>Action</th>
            </tr>

            </thead>
            <tbody>
            @php $i=1; @endphp
            @foreach($feewaiverTypes as $waiver)
                <tr class="gradeX">
                    <td>{{$i++}}</td>
                    <td>{{$waiver->name}}</td>
                    <td>
                        <a href="/fee/waivertype/edit/{{$waiver->id}}/" class=" btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
                        {{--<a id="1" class="feetype_delete_class btn btn-danger btn-xs" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>--}}
                    </td>
                </tr>
            @endforeach


            </tbody>
        </table>
    @else
        <div class="alert alert-warning">
            Waiver not found! create a new fee head
        </div>

    @endif

@endsection


