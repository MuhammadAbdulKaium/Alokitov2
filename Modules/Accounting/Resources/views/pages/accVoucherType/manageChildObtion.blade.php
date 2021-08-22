<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 3/27/17
 * Time: 11:37 AM
 */?>
@foreach($childs as $child)
    <option value="{{$child->id}}"
            @if($child->chart_type != 'L')
                disabled
            @elseif($child->chart_type == 'L')
                style="font-size: large"
            @endif
    >{{$child->chart_name}}</option>
    @if(count($child->childs))
        @include('accounting::pages.accSubHead.manageChildObtion',['childs' => $child->childs])
    @endif
@endforeach