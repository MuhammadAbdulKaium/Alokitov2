<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 8/13/17
 * Time: 1:28 PM
 */
?>
<strong>{{$maxI=count($allEmployee)}} @if($maxI>1) Employees @else Employee @endif </strong><br>
<button class="btn btn-danger btn-xs" type="button" id="selectAll">All</button>
<button class="btn btn-danger btn-xs" type="button" id="selectNone">None</button>
{{--<button class="btn btn-danger btn-xs" type="button" id="selectInverse">Inverse</button>--}}
<input type="hidden" id="maxVal" value="{{$maxI}}">

<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Department</th>
        <th>Designation</th>
        <th>Category</th>

    </tr>
    </thead>
    <tbody>
    <?php $i=0;?>
    @foreach($allEmployee as $data)
        <tr>
            <td><label><input type="checkbox" id="attenAloChkBox_{{$i}}" class="attenAloChkBox" name="attenAloChkBox[]" value="{{$data->id}}">{{$data->title}} {{$data->first_name}} {{$data->middle_name}} {{$data->last_name}}</label></td>
            <td>{{$data->department()->name}}</td>
            <td>{{$data->designation()->name}}</td>
            <td>@if($data->category==1) Teaching @else Non-Teaching @endif</td>
        </tr>
    <?php $i++;?>
    @endforeach
    </tbody>
</table>

<script>
    /*$('.attenAloChkBox').click(function (){
        //alert('azad');
        //alert($('#attenAloChkBox_1:checked').val());
        var maxI = $('#maxVal').val();
        var myList = [];
        for(var i=0;i<=maxI;i++){
            /!*if($('#attenAloChkBox_'+maxI+':checked').val() != '')
                myList.push($('#attenAloChkBox_'+maxI+':checked').val());*!/
            alert($('#attenAloChkBox_'+maxI+':checked').val());
        }
        //alert($('.attenAloChkBox:checked').length);
    });*/

    $('#selectNone').click(function (){
        $('.attenAloChkBox').removeAttr('checked');
    });
    $('#selectAll').click(function (){
//        $('.attenAloChkBox').attr('checked','checked');
        $('.attenAloChkBox').prop('checked','checked');

    });
</script>