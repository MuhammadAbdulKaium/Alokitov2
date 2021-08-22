<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 3/27/17
 * Time: 11:37 AM
 */?>
@foreach($childs as $child)
    @if($child->status == 1)
        <option value="{{$child->id}}" @if($child->chart_type == 'L') disabled @endif >{{$child->chart_name}}</option>
        @if(count($child->childs))
            @include('accounting::pages.accHead.manageChildObtion',['childs' => $child->childs])
        @endif
    @endif
@endforeach