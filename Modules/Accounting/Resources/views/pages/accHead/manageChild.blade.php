<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 3/27/17
 * Time: 11:37 AM
 */?>
@foreach($childs as $child)
    @if($child->status ==1)
    <tr @if($child->chart_type == 'G') style="font-weight: 600" @endif >
        {{--<td>{{ $i++}}</td>--}}
        <td>@if($child->chart_type == 'L') &nbsp; &nbsp; &nbsp; &nbsp; @endif {{ $child->chart_code }} </td>
        <td>@if($child->chart_type == 'L') &nbsp; &nbsp; &nbsp; &nbsp; @endif {{ $child->chart_name }}</td>
        <td>@if($child->chart_type == 'G') {{'Group'}}
            @else {{'Ledger'}}
            @endif
        </td>
        <td>
            {{$child->parent()->chart_name}}
        </td>

        <td>@if($child->status==1) <p>Active</p>
            @elseif($child->status==0) <p>Inactive</p>@endif
        </td>
        <td>
            {{--<a href="" class="btn btn-primary btn-xs" id="section_view_{{$accHead->id}}" onclick="modalLoad(this.id)" data-target="#globalModal"  data-toggle="modal" data-placement="top" data-content="view"><i class="fa fa-eye"></i></a>
            <a href="" id="section_edit_{{$accHead->id}}" onclick="modalLoadEdit(this.id)" class="btn btn-primary btn-xs" data-target="#globalModal"  data-toggle="modal" data-placement="top" data-content="update"><i class="fa fa-edit"></i></a>
            <a href="{{ url('accounting/delete-section', $accHead->id) }}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure to Delete?')" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>--}}
            <a class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal" onclick="modalLoad({{$child->id}})"><i class="fa fa-eye"></i> Edit</a>
        </td>
        @if(count($child->childs))
            @include('accounting::pages.accHead.manageChild',['childs' => $child->childs])
        @endif
    </tr>
    @endif
@endforeach