<table id="myTable" class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>#</th>
        <th><a  data-sort="sub_master_name">Date</a></th>
        <th><a  data-sort="sub_master_name">Serial</a></th>
        <th><a  data-sort="sub_master_alias">Details</a></th>
        <th><a>Amount</a></th>
        {{--<th><a>Amount Cr</a></th>--}}
        <th><a></a></th>
    </tr>
    </thead>
    <tbody>
    @php $i=1 @endphp
    @foreach($accVoucherEntrys as $accVoucherEntry)
        <tr>
            <td>{{ $i++ }}</td>
            <td>{{ date('d-m-Y',strtotime($accVoucherEntry->tran_date)) }}</td>
            <td>{{ $accVoucherEntry->tran_serial }}</td>
            <td>{{ $accVoucherEntry->tran_details }}</td>
            <td style="text-align: right" >{{ $accVoucherEntry->tran_amt_dr }}</td>
            {{--<td style="text-align: right" >{{ $accVoucherEntry->tran_amt_cr }}</td>--}}
            <td style="text-align: center" ><a class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal" onclick="modalLoad({{$accVoucherEntry->tran_serial}})"><i class="fa fa-eye"></i>View</a></td>
        </tr>
    @endforeach
    </tbody>
    <tfoot class="bg-info">
    <th colspan="3"></th>
    <th style="text-align: right" >Total: </th>
    <th style="text-align: right" >
        @php
            $arrayData = json_decode($accVoucherEntrys);
            echo array_sum(array_column($arrayData, 'tran_amt_dr'));
        @endphp
    </th>
    <th></th>
    </tfoot>
</table>