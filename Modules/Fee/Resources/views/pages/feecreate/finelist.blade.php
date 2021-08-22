@extends('fee::layouts.feecreate')
<!-- page content -->
@section('page-content')

    @if(!empty($finelist) && ($finelist->count()>0))

    {{--fine head list--}}
    <table id="finehead" class="table table-striped table-bordered" style="margin-top: 20px">
        <thead>
        <tr>
            <th width="5%"># NO</th>
            <th width="20%">Head Name</th>
            <th>Sub Head Name</th>
            <th>Class</th>
            <th>Amount In</th>
            <th>Fine Amount</th>
            <th>Fine Type</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
                @php $i=1; @endphp
        @foreach($finelist as $fine)
            <tr class="gradeX">
                <td>1</td>
                <td>{{$fine->feehead()->name}}</td>
                <td>{{$fine->subhead()->name}}</td>
                <td>@if(!empty($fine->batch()->get_division()))
                        {{$fine->batch()->batch_name.' '.$fine->batch()->get_division()->name}}
                    @else
                        {{$fine->batch()->batch_name}}
                    @endif</td>
                <td>
                    @if($fine->amount_percentage==1)
                        Amount
                        @else
                        Percentage
                    @endif

                </td>
                <td>{{$fine->fine_amount}}</td>
                <td>@if($fine->fine_type==1)
                        Fixed
                    @else
                        Percentage
                    @endif</td>
                <td>
                <a href="/fines/finetype/edit/1" class=" btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @else
            <div class="alert alert-warning">
                Fine List not found! create a new fine
            </div>
    @endif

@endsection


