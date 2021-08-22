@extends('fee::layouts.feesetting')
<!-- page content -->
@section('page-content')

    @if(!empty($feelist) && ($feelist->count()>0))

        {{--fee head list--}}
        <table id="feehead" class="table table-striped table-bordered" style="margin-top: 20px">
            <thead>
            <tr>
                <th width="5%"># NO</th>
                <th width="20%">Fee Name</th>
                <th>Fund Name</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @php $i=1; @endphp
            @foreach($feelist as $fee)
                <tr class="gradeX">
                    <td>1</td>
                    <td>{{$fee->feehead()->name}}</td>
                    <td>
                        @php $fundlistArray=json_decode($fee->fundlist,true); @endphp
                        @if(is_array($fundlistArray))
                   @php $fundValues=array_values($fundlistArray); @endphp

                        {{implode(', ', $fundValues)}}
                            @else
                            {{$fee->fundlist}}
                        @endif
                    </td>
                    <td>
                        <a href="/fees/feetype/edit/1" class=" btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
                        <a id="1" class="feetype_delete_class btn btn-danger btn-xs" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-warning">
            Created Fee not found! create a new fee
        </div>

    @endif

@endsection


