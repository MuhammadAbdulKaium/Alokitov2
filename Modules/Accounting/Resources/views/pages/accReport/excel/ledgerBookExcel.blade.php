<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 7/6/17
 * Time: 11:46 AM
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
    </tr>
    @endforeach
    </tbody>
    </table>