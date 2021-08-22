<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 3/27/17
 * Time: 11:37 AM
 */
$space="&nbsp;&nbsp;";
?>
@foreach($childs as $child)
    @if($child->status ==1)
    <tr @if($child->chart_type == 'G') style="font-weight: 600" @endif >
        <td> @if($child->chart_type == 'G') {{$space}}
            @else {{$space}}{{$space}}
            @endif
            {{$child->chart_name}}</td>
        <td style="text-align: right">{{ abs($child->sumCalc($child->id)) }}
            @if($child->chart_type == 'G')
            @else
            @endif
        </td>

        @if(count($child->childs))
            @php $childs=$child->childs  @endphp
            {{--Head Income and expense heare --}}
            @foreach($childs as $child)
                @if($child->status ==1)
                    <tr @if($child->chart_type == 'G') style="font-weight: 600" @endif >
                        <td> @if($child->chart_type == 'G') {{$space}}
                            @else {{$space}}{{$space}}
                            @endif
                            {{$child->chart_name}}</td>
                        <td style="text-align: right">{{ abs($child->sumCalc($child->id)) }}
                            @if($child->chart_type == 'G') {{$space}}
                            @else {{$space}}
                            @endif
                        </td>

                    </tr>
                    @endif
                    @endforeach

            {{--End Head Income and expense heare --}}
        @endif
    </tr>
    @endif
@endforeach