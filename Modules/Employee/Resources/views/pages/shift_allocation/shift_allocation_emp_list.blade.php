<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 8/13/17
 * Time: 1:28 PM
 */
?>

<button class="btn btn-danger btn-xs" type="button" id="selectAll">All</button>
<button class="btn btn-danger btn-xs" type="button" id="selectNone">None</button>
{{--<button class="btn btn-danger btn-xs" type="button" id="selectInverse">Inverse</button>--}}

<input type="hidden" id="maxVal" value="{{$maxI=count($allEmployee)}}">

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
            <td><label><input type="checkbox" id="shiftAloChkBox_{{$i}}" class="shiftAloChkBox" name="shiftAloChkBox[]" value="{{$data->user_id}}">{{$data->title}} {{$data->first_name}} {{$data->middle_name}} {{$data->last_name}}</label></td>
            <td>{{$data->department()->name}}</td>
            <td>{{$data->designation()->name}}</td>
            <td>@if($data->category==1) Teaching @else Non-Teaching @endif</td>
        </tr>
    <?php $i++;?>
    @endforeach
    </tbody>
</table>

<script>
    /*$('.shiftAloChkBox').click(function (){
        //alert('azad');
        //alert($('#shiftAloChkBox_1:checked').val());
        var maxI = $('#maxVal').val();
        var myList = [];
        for(var i=0;i<=maxI;i++){
            /!*if($('#shiftAloChkBox_'+maxI+':checked').val() != '')
                myList.push($('#shiftAloChkBox_'+maxI+':checked').val());*!/
            alert($('#shiftAloChkBox_'+maxI+':checked').val());
        }
        //alert($('.shiftAloChkBox:checked').length);
    });*/


    $('#selectNone').click(function (){
        $('.shiftAloChkBox').removeAttr('checked');
    });
    $('#selectAll').click(function (){
//        $('.shiftAloChkBox').attr('checked','checked');
        $('.shiftAloChkBox').prop('checked','checked');

    });
</script>