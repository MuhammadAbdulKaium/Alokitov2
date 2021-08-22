<table id="myTable" class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>#</th>
        <th><a  data-sort="sub_master_name">Date</a></th>
        <th><a  data-sort="sub_master_name">Serial</a></th>
        <th><a  data-sort="sub_master_alias">Details</a></th>
        <th><a>Amount</a></th>
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
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th></th>
        <th></th>
        <th></th>
        <th style="text-align: right" >Total: </th>
        <th style="text-align: right" >
            @php
                $arrayData = json_decode($accVoucherEntrys);
                echo array_sum(array_column($arrayData, 'tran_amt_dr'));
            @endphp
        </th>
    </tr>
    </tfoot>
</table>