<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 9/11/17
 * Time: 6:17 PM
 */
?>
<div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
            <h3 class="box-title"><i class="fa fa-eye"></i> Show Salary Structure </h3>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="errorTxt"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label" for="salaryStructureName">Salary Structure Name: </label>
                        <p>{{$SalaryStructures->name}}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="control-label" for="details">Details: </label>
                        <p>{{$SalaryStructures->details}}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-5">
                    <label class="control-label" >Salary Component: </label>
                </div>
                <div class="col-sm-5">
                    <label class="control-label" >Percent / Amount</label>
                </div>
                <div class="col-sm-2">
                    <label class="control-label" >&nbsp;</label>
                </div>
            </div>
            @foreach($SalaryStructuresBasic as $data)
            <div class="row">
                <div class="col-sm-1"></div>
                <div class="col-sm-5">{{$data->name}}</div>
                <div class="col-sm-5"></div>
{{--                <div class="col-sm-5">{{$data->amount}}@if($data->percent == 'P'){{' %'}}@endif</div>--}}

            </div>
            @endforeach
            <br><strong>Allowance</strong>
            @php $amountAlo = $amountDed = 0 @endphp
            @foreach($SalaryStructuresAlo as $data)
                <div class="row">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-5">{{$data->name}}</div>
                    <div class="col-sm-5">{{$data->amount}}@if($data->amount && $data->percent == 'P'){{' %'}}
                        @php $amountAlo = $amountAlo + $data->amount @endphp @endif
                    </div>
                </div>
            @endforeach
            <br><strong>Deduction</strong>
            @foreach($SalaryStructuresDed as $data)
                <div class="row">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-5">{{$data->name}}</div>
                    <div class="col-sm-5">{{$data->amount}}@if($data->amount && $data->percent == 'P'){{' %'}}
                        @php $amountDed = $amountDed + $data->amount @endphp @endif
                    </div>
                </div>
            @endforeach
            <div class="row">
                <div class="col-sm-5">
                    <label class="control-label" ></label>
                </div>
                <div class="col-sm-5">
                    Total Percent:
                    <label class="control-label" id="totalPercent">
                        @php echo $amountAlo - $amountDed @endphp %
                    </label>
                </div>
            </div>
            <!--./modal-body-->
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
            </div>
        </div>
    </div>
</div>