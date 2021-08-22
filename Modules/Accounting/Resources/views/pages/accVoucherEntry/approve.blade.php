<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 3/28/17
 * Time: 3:27 PM
 */
use Modules\Accounting\Entities\AccCharts;
use Modules\Accounting\Entities\AccVoucherType;
?>
<div class="modal-dialog modal-lg">

    @php
        $info = $accVoucherEntrys->first();
        $accVoucherType = AccVoucherType::where('id',$info->acc_voucher_type_id)->get()->first();
    @endphp

    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            @if($info->status !=1)
                <h4 class="modal-title">Voucher Approve </h4>
            @else
                <h4 class="modal-title">Voucher Detail View </h4>
            @endif
        </div>
        <div class="modal-body">
            <form id="accVoucherEntry" method="post" action="{{ url('accounting/accvoucherentry/approved')}}">
                {{csrf_field()}}
                <div class="box-body">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="chart_parent">Voucher Type</label>
                            <input readonly autocomplete="off" value="{{$accVoucherType->voucher_code}} ----- {{$accVoucherType->voucher_name}}" list="voucherType" type="text" class="form-control" name="voucher_type" id="voucher_type" placeholder="Enter Voucher Type">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="chart_code">Voucher No</label>
                            <input  readonly value="{{$info->tran_serial}}"  type="text" class="form-control"  name="voucherNo" id="voucherNo" placeholder="Voucher No">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="voucherData">Voucher Data</label>
                            <input readonly value="{{date('d-m-Y',strtotime($info->tran_date))}}" type="text" class="form-control"  name="voucherData" id="voucherData">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="notes">Narration / Particulars</label>
                            <textarea readonly class="form-control" cols="30" name="notes" id="notes">{{$info->tran_details}}</textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <br>
                        <table width="100%" id="tran_tbl">
                            <thead>
                            <tr>
                                <th width="65%">Head of Account</th>
                                <th width="15%">DR</th>
                                <th width="15%">CR</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($accVoucherEntrys as $accVoucherEntry)
                                @php
                                $accHead = AccCharts::where('id', $accVoucherEntry->acc_charts_id)->get()->first();
                                @endphp
                            <tr>
                                <td><input readonly value="{{$accHead->chart_code}} ----- {{$accHead->chart_name}}"  type="text" class="form-control" name="chart_name[]" id="chart_name" placeholder="Enter Head of Account"></td>
                                <td><input style="text-align: right" readonly value="{{$accVoucherEntry->tran_amt_dr}}" class="form-control"></td>
                                <td><input style="text-align: right" readonly value="{{$accVoucherEntry->tran_amt_cr}}" class="form-control"></td>
                            </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr style="text-align: right">
                                <td>Total: </td>
                                <td><div id="totalDr">{{$accVoucherSummery->total_dr}}</div></td>
                                <td><div id="totalCr">{{$accVoucherSummery->total_cr}}</div></td>
                            </tr>
                            </tfoot>
                        </table>
                        <input type="hidden" name="snCount" id="snCount" value="1">
                    </div>
                </div>
                <div class="box-footer">
                    @if($info->status !=1)
                    <button type="submit" id="submitBtn"  class="btn btn-primary pull-right">Approved</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>