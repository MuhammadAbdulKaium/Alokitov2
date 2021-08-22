<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 6/6/17
 * Time: 2:54 PM
 */
?>
<table id="myTable" class="table table-striped table-bordered">
    <thead>
    <tr>
        <th style="text-align: center">#</th>
        <th style="text-align: center"><a  data-sort="sub_master_name">Date</a></th>
        <th style="text-align: center"><a  data-sort="sub_master_name">Serial</a></th>
        <th style="text-align: center"><a  data-sort="sub_master_alias">Details</a></th>
        <th style="text-align: center"><a>Dr</a></th>
        <th style="text-align: center"><a>Cr</a></th>
        <th style="text-align: center">Balance</th>
        <th style="text-align: center"><a></a></th>
    </tr>
    </thead>
    <tbody>
    @php $i = 1;
    $closingBalance = 0;
    @endphp
    @foreach($accVoucherEntrys as $accVoucherEntry)
        <tr>
            <td>{{ $i++ }}</td>
            <td>{{ date('d-m-Y',strtotime($accVoucherEntry->tran_date)) }}</td>
            <td>{{ $accVoucherEntry->tran_serial }}</td>
            <td>{{ $accVoucherEntry->tran_details }}</td>
            <td style="text-align: right" >{{ $accVoucherEntry->tran_amt_dr }}</td>
            <td style="text-align: right" >{{ $accVoucherEntry->tran_amt_cr }}</td>
            <td style="text-align: right" >
                @php
                    $balence = ($accVoucherEntry->tran_amt_dr - $accVoucherEntry->tran_amt_cr);
                    $closingBalance += $balence;
                if($closingBalance < 0){echo '('.abs($closingBalance).')';}else{echo $closingBalance;}
                @endphp
            </td>
            <td style="text-align: center" ><a class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal" onclick="modalLoad({{$accVoucherEntry->tran_serial}})"><i class="fa fa-eye"></i>View</a></td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th style="text-align: center">#</th>
        <th style="text-align: center"><a  data-sort="sub_master_name">Date</a></th>
        <th style="text-align: center"><a  data-sort="sub_master_name">Serial</a></th>
        <th style="text-align: center"><a  data-sort="sub_master_alias">Details</a></th>
        <th style="text-align: center"><a>Dr</a></th>
        <th style="text-align: center"><a>Cr</a></th>
        <th style="text-align: center">Balance</th>
        <th style="text-align: center"><a></a></th>
    </tr>
    </tfoot>
</table>
