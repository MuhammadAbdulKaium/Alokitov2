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
        <form  action="{{URL::to('/fee/feeheadfund/store')}}" method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <div class="row">
                <div class="col-md-4">
                    <div class="row">
                        <h2 class="text-center">Student Fee Create</h2>
                            <div class="form-group">
                                <label class="control-label" for="feehead">Fee Head Name</label>
                                <select name="head_id" class="form-control" id="period">
                                    <option value="">Select Fee Head</option>
                                    @foreach($feeHeads as $head)
                                        <option value="{{$head->id}}">{{$head->name}}</option>
                                    @endforeach
                                </select>
                                <div class="help-block"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" style="margin-top: 80px">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h1 class="panel-title">School Fund List</h1>
                        </div>
                        @if(!empty($feefunds) && ($feefunds->count()>0))
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>Select</th>
                                    <th>Fund Name</th>
                                </tr>
                                </thead>

                                <tbody>
                                @php $i=1; @endphp
                                @foreach($feefunds as $fund)
                                    <tr>
                                        <td><input type="checkbox" name="fundlist[{{$fund->id}}]" value="{{$fund->name}}"></td>                                <td>{{$fund->name}}</td>
                                    </tr>
                                @endforeach


                                </tbody>
                            </table>
                        @else
                            <div class="alert alert-warning">
                                Fee Fund not found! create a new fee head
                            </div>

                        @endif

                    </div>

                </div>

            </div>

            <button type="submit" class="btn btn-success">Submit</button>
        </form>
    </div>
    {{--end free head --}}

    {{--fee head list--}}
    {{--<table id="feehead" class="table table-striped table-bordered" style="margin-top: 20px">--}}
    {{--<thead>--}}
    {{--<tr>--}}
    {{--<th># NO</th>--}}
    {{--<th>Class</th>--}}
    {{--<th>Period</th>--}}
    {{--<th>Amount</th>--}}
    {{--<th>Action</th>--}}
    {{--</tr>--}}

    {{--</thead>--}}
    {{--<tbody>--}}

    {{--<tr class="gradeX">--}}
    {{--<td>1</td>--}}
    {{--<td>One</td>--}}
    {{--<td>1st Period</td>--}}
    {{--<td>20</td>--}}
    {{--<td>--}}
    {{--<a href="/fees/feetype/edit/1" class=" btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>--}}
    {{--<a id="1" class="feetype_delete_class btn btn-danger btn-xs" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>--}}
    {{--</td>--}}
    {{--</tr>--}}


    {{--<tr class="gradeX">--}}
    {{--<td>2</td>--}}
    {{--<td>Two</td>--}}
    {{--<td>1st Period</td>--}}
    {{--<td>30</td>--}}
    {{--<td>--}}
    {{--<a href="/fees/feetype/edit/1" class=" btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>--}}
    {{--<a id="1" class="feetype_delete_class btn btn-danger btn-xs" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>--}}
    {{--</td>--}}
    {{--</tr>--}}


    {{--<tr class="gradeX">--}}
    {{--<td>3</td>--}}
    {{--<td>Four</td>--}}
    {{--<td>2nd Period</td>--}}
    {{--<td>40</td>--}}
    {{--<td>--}}
    {{--<a href="/fees/feetype/edit/1" class=" btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>--}}
    {{--<a id="1" class="feetype_delete_class btn btn-danger btn-xs" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>--}}
    {{--</td>--}}
    {{--</tr>--}}

    {{--<tr class="gradeX">--}}
    {{--<td>4</td>--}}
    {{--<td>Five</td>--}}
    {{--<td>1st Period</td>--}}
    {{--<td>50</td>--}}
    {{--<td>--}}
    {{--<a href="/fees/feetype/edit/1" class=" btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>--}}
    {{--<a id="1" class="feetype_delete_class btn btn-danger btn-xs" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>--}}
    {{--</td>--}}
    {{--</tr>--}}

    {{--</tbody>--}}
    {{--</table>--}}

@endsection


