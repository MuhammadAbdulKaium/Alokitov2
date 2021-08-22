<style>
    .heading {
        text-align: center;
        margin: 0px;
        padding: 0px;
    }
    p {
        font-size: 12px;
        padding-top: 5px;
    }
    h2, h5 {
        margin: 0px;
        padding: 0px;
        line-height: 0px;
    }
    #waiverAssign {
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    #waiverAssign td, #waiverAssign th {
        border: 1px solid #ddd;
        padding: 2px;
        font-size: 10px;
    }

    /*#studentListTable tr:nth-child(even){background-color: #f2f2f2;}*/


    #waiverAssign th {
        text-align: left;
        background-color: #8a8a8a;
        color: white;
    }
</style>

<div class="heading">
    <h2>{{$instituteInfo->institute_name}}</h2>
    <p>{{$instituteInfo->address1}}</p>
    <h5>Waiver Assign Details Report </h5>

</div>
@if(!empty($waiverAssignList))
    <table id="waiverAssign" class="table table-striped table-bordered" style="margin-top: 20px">
   <thead>
    <tr>
        <th># NO</th>
        <th>STD ID</th>
        <th width="20%">Name</th>
        <th>Roll</th>
        <th>Class</th>
        <th>Section</th>
        <th>Fee Head</th>
        <th>Waiver Type</th>
        <th>Waiver Amount/Percent</th>
    </tr>
    </thead>
    <tbody>
    @php $i=1; @endphp
    @foreach($waiverAssignList as $waiver)

        <tr class="gradeX">
            <td>{{$i++}}</td>
            @php $studentProfile=$waiver->studentProfile(); @endphp
            <td>{{$studentProfile->username}}</td>
            <td>{{$studentProfile->first_name.' '.$studentProfile->middle_name.' '.$studentProfile->last_name}}</td>
            <td>{{$studentProfile->gr_no}}</td>
            <td>
                @if ($waiver->batch()->get_division())
                    {{$waiver->batch()->batch_name.' '.$waiver->batch()->get_division()->name}}
                @else
                    {{$waiver->batch()->batch_name}}
                @endif
            </td>
            <td>{{$waiver->section()->section_name}}</td>
            <td>{{$waiver->feehead()->name}}</td>
            <td>{{$waiver->waiver_type()->name}}</td>
            <td>{{$waiver->amount}}
                @if($waiver->amount_percentage==1) %
                @else
                    ৳
                @endif
            </td>
        </tr>
    @endforeach

    </tbody>
</table>
@else
    No Record Found
@endif
