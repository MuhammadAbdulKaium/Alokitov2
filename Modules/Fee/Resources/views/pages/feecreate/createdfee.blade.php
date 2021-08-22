@extends('fee::layouts.feecreate')
<!-- page content -->
@section('page-content')

    @if(!empty($feelist) && ($feelist->count()>0))

    {{--fee head list--}}
    <table id="feehead" class="table table-striped table-bordered" style="margin-top: 20px">
        <thead>
        <tr>
            <th width="5%"># NO</th>
            <th width="20%">Fee Name</th>
            <th>Sub Head Name</th>
            <th>Class</th>
            <th>Amount</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($feelist as $index=>$fee)
            <tr class="gradeX">
                <td>{{ $index + $feelist->firstItem() }}</td>
                <td>{{$fee->feeHead()->name}}</td>
                <td>{{$fee->name}}</td>
                    <td>
                        @if($fee->batch())

                            @if(!empty($fee->batch()->division()))
                                {{$fee->batch()->batch_name.' '.$fee->batch()->division()->name}}}}
                            @else
                                {{$fee->batch()->batch_name}}
                                @endif
                        @endif
                   </td>
                <td>{{$fee->amount}}</td>
                <td>
                <a href="/fees/feetype/edit/1" class=" btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
                    <a id="1" class="feetype_delete_class btn btn-danger btn-xs" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{$feelist->links()}}
    @else
            <div class="alert alert-warning">
                Created Fee not found! create a new fee
            </div>

    @endif

@endsection


