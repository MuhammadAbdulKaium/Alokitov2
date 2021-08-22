<style>
    .tr-group tr-root-group {
        color: orange !important;
    }
</style>

<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 3/27/17
 * Time: 11:37 AM
 */
$space="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
?>
@foreach($childs as $child)
    @if($child->status ==1)
    <tr @if($child->chart_type == 'G') style="font-weight: 600" @endif >
        <td>@if($child->chart_type == 'G') {{$space}}
            @else {{$space}}{{$space}}
            @endif
            {{$child->chart_name}}
        </td>
        <td> @if($child->chart_type == 'G') Group @else Ledger @endif</td>
        <td>{{ abs($child->subTotalOpeningBlc($child->id)) }}</td>
        <td>{{ abs($child->sumDrCalc($child->id)) }}
            @if($child->chart_type == 'G')
            @else
            @endif
        </td>
        <td>{{ abs($child->sumCrCalc($child->id)) }}
            @if($child->chart_type == 'G')
            @else
            @endif
        </td>
        <td>
            {{ abs($child->groupCloseingBlance($child->id)) }}
        </td>
        @if(count($child->childs))

            {{--Nested Loop For Ledger --}}
            @php $childs=$child->childs; @endphp

            @foreach($childs as $child)
                @if($child->status ==1)
                    <tr @if($child->chart_type == 'G') style="font-weight: 600" @endif >
                        <td>@if($child->chart_type == 'G') {{$space}}
                            @else {{$space}}{{$space}}
                            @endif
                            {{$child->chart_name}}
                        </td>
                        <td> @if($child->chart_type == 'G') Group @else Ledger @endif</td>
                        <td>{{ abs($child->singleOpeningBlc($child->id)) }}</td>
                        <td>{{ abs($child->sumDrCalc($child->id)) }}
                            @if($child->chart_type == 'G')
                            @else
                            @endif
                        </td>
                        <td>{{ abs($child->sumCrCalc($child->id)) }}
                            @if($child->chart_type == 'G')
                            @else
                            @endif
                        </td>
                        <td>
                            {{ abs($child->headCloseingBlance($child->id)) }}
                        </td>
                    </tr>
                    @endif
               @endforeach


            {{--End Nested Loop For LEdger --}}

        @endif
    </tr>
    @endif
@endforeach