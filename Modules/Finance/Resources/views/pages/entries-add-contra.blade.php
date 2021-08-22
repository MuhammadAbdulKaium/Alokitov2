@extends('finance::layouts.master')
@section('section-title')
    <h1>
        <i class="fa fa-search"></i>Finance
    </h1>
    <ul class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
        <li><a href="/library/default/index">Finacne</a></li>
        <li class="active">Manage Account</li>
    </ul>
@endsection
<!-- page content -->
@section('page-content')

    <style>
        .select2-container--default.select2-container--focus,
        .select2-selection.select2-container--focus,
        .select2-container--default:focus,
        .select2-selection:focus,
        .select2-container--default:active,
        .select2-selection:active {
            outline: none
        }

        .select2-container--default .select2-selection--single,
        .select2-selection .select2-selection--single {
            border: 1px solid #d2d6de;
            border-radius: 0;
            padding: 6px 12px;
            height: 34px
        }

        .select2-container--default.select2-container--open {
            border-color: #3c8dbc
        }

        .select2-dropdown {
            border: 1px solid #d2d6de;
            border-radius: 0
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #3c8dbc;
            color: white
        }

        .select2-results__option {
            padding: 6px 12px;
            user-select: none;
            -webkit-user-select: none
        }

        .select2-container .select2-selection--single .select2-selection__rendered {
            padding-left: 0;
            padding-right: 0;
            height: auto;
            margin-top: -4px
        }

        .select2-container[dir="rtl"] .select2-selection--single .select2-selection__rendered {
            padding-right: 6px;
            padding-left: 20px
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 28px;
            right: 3px
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            margin-top: 0
        }

        .select2-dropdown .select2-search__field,
        .select2-search--inline .select2-search__field {
            border: 1px solid #d2d6de
        }

        .select2-dropdown .select2-search__field:focus,
        .select2-search--inline .select2-search__field:focus {
            outline: none
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple,
        .select2-container--default .select2-search--dropdown .select2-search__field {
            border-color: #3c8dbc !important
        }

        .select2-container--default .select2-results__option[aria-disabled=true] {
            color: #999
        }

        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #ddd
        }

        .select2-container--default .select2-results__option[aria-selected=true],
        .select2-container--default .select2-results__option[aria-selected=true]:hover {
            color: #444
        }

        .select2-container--default .select2-selection--multiple {
            border: 1px solid #d2d6de;
            border-radius: 0
        }

        .select2-container--default .select2-selection--multiple:focus {
            border-color: #3c8dbc
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #d2d6de
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #3c8dbc;
            border-color: #367fa9;
            padding: 1px 10px;
            color: #fff
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            margin-right: 5px;
            color: rgba(255, 255, 255, 0.7)
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #fff
        }

        .select2-container .select2-selection--single .select2-selection__rendered {
            padding-right: 10px
        }
    </style>

    <div class="box box-body">
        <div style="padding:25px;">
            <div class="entry add form">
                <form autocomplete="off" action="/finance/entries/store" id="EntryAddForm" method="post" accept-charset="utf-8">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <input type="hidden" name="voucher_type_id" value="4">
                    <div class="form-group">
                        <label for="EntryNumber">Number</label>
                        <div class="input-group">
                            <span class="input-group-addon">CDL</span>
                            <input name="data[Entry][number]" class="form-control" style="width:450px;" type="number" id="EntryNumber">
                        </div>
                    </div>
                    <div class="form-group required">
                        <label for="student_dob">Date</label>
                        <input name="data[Entry][date]" class="form-control" id="datePick" style="width:450px;" type="text" required="required">
                    </div>
                    <table class="table table-hover stripped extra">
                        <tbody>
                        <tr>
                            <th>Dr/Cr</th>
                            <th>Ledger</th>
                            <th>Dr Amount</th>
                            <th>Cr Amount</th>
                            <th>Actions</th>
                            <th>Cur Balance</th>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group-entryitem required">
                                    <select name="data[Entryitem][0][dc]" class="dc-dropdown form-control" id="Entryitem0Dc">
                                        <option value="D" selected="selected">Dr</option>
                                        <option value="C">Cr</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group-entryitem required">
                                    <select name="data[Entryitem][0][ledger_id]" class="ledger-dropdown form-control selectLedger" id="Entryitem0LedgerId" style="width: 100%;">
                                        <option value="0">(Please select..)</option>
                                        @foreach($parentGroupList as $key=>$parent)
                                            <optgroup label="{{$parent->name}}">
                                                @php $childGroupList=$parent->getChildGroup($parent->id); @endphp
                                                @foreach($childGroupList as $key=>$childGorup)
                                                    <optgroup label=" {{$childGorup->name}}">
                                                        @php $ledgerList=$childGorup->getLedger($childGorup->id); @endphp
                                                        @foreach($ledgerList as $key=>$ledger)
                                                            <option value="">{{$ledger->name}}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endforeach
                                            </optgroup>

                                        @endforeach


                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group-entryitem">
                                    <input name="data[Entryitem][0][dr_amount]" class="dr-item form-control" type="text" id="Entryitem0DrAmount" disabled="">
                                </div>
                            </td>
                            <td>
                                <div class="form-group-entryitem">
                                    <input name="data[Entryitem][0][cr_amount]" class="cr-item form-control" type="text" id="Entryitem0CrAmount" disabled="">
                                </div>
                            </td>
                            <td>
                                <button class="btn btn-success addrow" type="button"><i class="fa fa-plus"></i></button>
                                <button class="btn btn-danger deleterow" type="button"><i class="fa fa-trash-o"></i></button>
                            </td>
                            <td class="ledger-balance">
                                <div></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group-entryitem required">
                                    <select name="data[Entryitem][1][dc]" class="dc-dropdown form-control" id="Entryitem1Dc">
                                        <option value="D">Dr</option>
                                        <option value="C" selected="selected">Cr</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group-entryitem required">
                                <select name="data[Entryitem][1][ledger_id]" class="ledger-dropdown form-control selectLedger" id="Entryitem1LedgerId" style="width: 100%;">
                                <option value="0">(Please select..)</option>

                                    @foreach($parentGroupList as $key=>$parent)
                                        <optgroup label="{{$parent->name}}">
                                            @php $childGroupList=$parent->getChildGroup($parent->id); @endphp
                                            @foreach($childGroupList as $key=>$childGorup)
                                                <optgroup label=" {{$childGorup->name}}">
                                                    @php $ledgerList=$childGorup->getLedger($childGorup->id); @endphp
                                                    @foreach($ledgerList as $key=>$ledger)
                                                        <option value="">{{$ledger->name}}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </optgroup>

                                    @endforeach

                                </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group-entryitem">
                                    <input name="data[Entryitem][1][dr_amount]" class="dr-item form-control" type="text" id="Entryitem1DrAmount" disabled="">
                                </div>
                            </td>
                            <td>
                                <div class="form-group-entryitem">
                                    <input name="data[Entryitem][1][cr_amount]" class="cr-item form-control" type="text" id="Entryitem1CrAmount" disabled="">
                                </div>
                            </td>
                            <td>
                                <button class="btn btn-success addrow" type="button"><i class="fa fa-plus"></i></button>
                                <button class="btn btn-danger deleterow" type="button"><i class="fa fa-trash-o"></i></button>

                            </td>
                            <td class="ledger-balance">
                                <div></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group-entryitem required">
                                    <select name="data[Entryitem][2][dc]" class="dc-dropdown form-control" id="Entryitem2Dc">
                                        <option value="D" selected="selected">Dr</option>
                                        <option value="C">Cr</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group-entryitem required">
                                <select name="data[Entryitem][2][ledger_id]" class="ledger-dropdown form-control selectLedger" id="Entryitem2LedgerId" style="width: 100%;">
                                <option value="0">(Please select..)</option>

                                    @foreach($parentGroupList as $key=>$parent)
                                        <optgroup label="{{$parent->name}}">
                                            @php $childGroupList=$parent->getChildGroup($parent->id); @endphp
                                            @foreach($childGroupList as $key=>$childGorup)
                                                <optgroup label=" {{$childGorup->name}}">
                                                    @php $ledgerList=$childGorup->getLedger($childGorup->id); @endphp
                                                    @foreach($ledgerList as $key=>$ledger)
                                                        <option value="">{{$ledger->name}}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </optgroup>

                                    @endforeach

                                </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group-entryitem">
                                    <input name="data[Entryitem][2][dr_amount]" class="dr-item form-control" type="text" id="Entryitem2DrAmount" disabled="">
                                </div>
                            </td>
                            <td>
                                <div class="form-group-entryitem">
                                    <input name="data[Entryitem][2][cr_amount]" class="cr-item form-control" type="text" id="Entryitem2CrAmount" disabled="">
                                </div>
                            </td>
                            <td>
                                <button class="btn btn-success addrow" type="button"><i class="fa fa-plus"></i></button>
                                <button class="btn btn-danger deleterow" type="button"><i class="fa fa-trash-o"></i></button>

                            </td>
                            <td class="ledger-balance">
                                <div></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group-entryitem required">
                                    <select name="data[Entryitem][3][dc]" class="dc-dropdown form-control" id="Entryitem3Dc">
                                        <option value="D" selected="selected">Dr</option>
                                        <option value="C">Cr</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group-entryitem required">
                                <select name="data[Entryitem][3][ledger_id]" class="ledger-dropdown form-control selectLedger" id="Entryitem3LedgerId" style="width: 100%;">
                                <option value="0">(Please select..)</option>
                                    @foreach($parentGroupList as $key=>$parent)
                                        <optgroup label="{{$parent->name}}">
                                            @php $childGroupList=$parent->getChildGroup($parent->id); @endphp
                                            @foreach($childGroupList as $key=>$childGorup)
                                                <optgroup label=" {{$childGorup->name}}">
                                                    @php $ledgerList=$childGorup->getLedger($childGorup->id); @endphp
                                                    @foreach($ledgerList as $key=>$ledger)
                                                        <option value="">{{$ledger->name}}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </optgroup>

                                    @endforeach

                                </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group-entryitem">
                                    <input name="data[Entryitem][3][dr_amount]" class="dr-item form-control" type="text" id="Entryitem3DrAmount" disabled="">
                                </div>
                            </td>
                            <td>
                                <div class="form-group-entryitem">
                                    <input name="data[Entryitem][3][cr_amount]" class="cr-item form-control" type="text" id="Entryitem3CrAmount" disabled="">
                                </div>
                            </td>
                            <td>
                                <button class="btn btn-success addrow" type="button"><i class="fa fa-plus"></i></button>
                                <button class="btn btn-danger deleterow" type="button"><i class="fa fa-trash-o"></i></button>

                            </td>
                            <td class="ledger-balance">
                                <div></div>
                            </td>
                        </tr>
                        <tr class="bold-text">
                            <td>Total</td>
                            <td></td>
                            <td id="dr-total" style="background-color: rgb(255, 255, 153);">0</td>
                            <td id="cr-total" style="background-color: rgb(255, 255, 153);">0</td>
                            <td>
																			<span class="recalculate">
																				<i class="glyphicon glyphicon-refresh"></i>
																			</span>
                            </td>
                            <td></td>
                        </tr>
                        <tr class="bold-text">
                            <td>Difference</td>
                            <td></td>
                            <td id="dr-diff">-</td>
                            <td id="cr-diff"></td>
                            <td></td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                    <br>
                    <div class="form-group">
                        <label for="EntryNarration">Notes</label>
                        <textarea name="data[Entry][narration]" class="form-control" rows="3" style="width:450px;" cols="30" id="EntryNarration"></textarea>
                    </div>
                    <div class="form-group">
                        <input class="btn btn-primary" type="submit" value="Submit">
                        <span class="link-pad"></span>
                        <a href="/finance/entries" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>



        </div>
    </div>
@endsection
@section('page-script')

    <script type="text/javascript">

                $('#datePick').datepicker({format: 'dd-mm-yyyy',todayHighlight: true});

        $(document).ready(function() {

            $(".selectLedger").select2();

            /* javascript floating point operations */
            var jsFloatOps = function(param1, param2, op) {
                param1 = param1 * 100;
                param2 = param2 * 100;
                param1 = param1.toFixed(0);
                param2 = param2.toFixed(0);
                param1 = Math.floor(param1);
                param2 = Math.floor(param2);
                var result = 0;
                if (op == '+') {
                    result = param1 + param2;
                    result = result/100;
                    return result;
                }
                if (op == '-') {
                    result = param1 - param2;
                    result = result/100;
                    return result;
                }
                if (op == '!=') {
                    if (param1 != param2)
                        return true;
                    else
                        return false;
                }
                if (op == '==') {
                    if (param1 == param2)
                        return true;
                    else
                        return false;
                }
                if (op == '>') {
                    if (param1 > param2)
                        return true;
                    else
                        return false;
                }
                if (op == '<') {
                    if (param1 < param2)
                        return true;
                    else
                        return false;
                }
            }

            /* Calculating Dr and Cr total */
            $(document).on('change', '.dr-item', function() {
                var drTotal = 0;
                $("table tr .dr-item").each(function() {
                    var curDr = $(this).prop('value');
                    curDr = parseFloat(curDr);
                    if (isNaN(curDr))
                        curDr = 0;
                    drTotal = jsFloatOps(drTotal, curDr, '+');
                });
                $("table tr #dr-total").text(drTotal);
                var crTotal = 0;
                $("table tr .cr-item").each(function() {
                    var curCr = $(this).prop('value');
                    curCr = parseFloat(curCr);
                    if (isNaN(curCr))
                        curCr = 0;
                    crTotal = jsFloatOps(crTotal, curCr, '+');
                });
                $("table tr #cr-total").text(crTotal);

                if (jsFloatOps(drTotal, crTotal, '==')) {
                    $("table tr #dr-total").css("background-color", "#FFFF99");
                    $("table tr #cr-total").css("background-color", "#FFFF99");
                    $("table tr #dr-diff").text("-");
                    $("table tr #cr-diff").text("");
                } else {
                    $("table tr #dr-total").css("background-color", "#FFE9E8");
                    $("table tr #cr-total").css("background-color", "#FFE9E8");
                    if (jsFloatOps(drTotal, crTotal, '>')) {
                        $("table tr #dr-diff").text("");
                        $("table tr #cr-diff").text(jsFloatOps(drTotal, crTotal, '-'));
                    } else {
                        $("table tr #dr-diff").text(jsFloatOps(crTotal, drTotal, '-'));
                        $("table tr #cr-diff").text("");
                    }
                }
            });

            $(document).on('change', '.cr-item', function() {
                var drTotal = 0;
                $("table tr .dr-item").each(function() {
                    var curDr = $(this).prop('value')
                    curDr = parseFloat(curDr);
                    if (isNaN(curDr))
                        curDr = 0;
                    drTotal = jsFloatOps(drTotal, curDr, '+');
                });
                $("table tr #dr-total").text(drTotal);
                var crTotal = 0;
                $("table tr .cr-item").each(function() {
                    var curCr = $(this).prop('value')
                    curCr = parseFloat(curCr);
                    if (isNaN(curCr))
                        curCr = 0;
                    crTotal = jsFloatOps(crTotal, curCr, '+');
                });
                $("table tr #cr-total").text(crTotal);

                if (jsFloatOps(drTotal, crTotal, '==')) {
                    $("table tr #dr-total").css("background-color", "#FFFF99");
                    $("table tr #cr-total").css("background-color", "#FFFF99");
                    $("table tr #dr-diff").text("-");
                    $("table tr #cr-diff").text("");
                } else {
                    $("table tr #dr-total").css("background-color", "#FFE9E8");
                    $("table tr #cr-total").css("background-color", "#FFE9E8");
                    if (jsFloatOps(drTotal, crTotal, '>')) {
                        $("table tr #dr-diff").text("");
                        $("table tr #cr-diff").text(jsFloatOps(drTotal, crTotal, '-'));
                    } else {
                        $("table tr #dr-diff").text(jsFloatOps(crTotal, drTotal, '-'));
                        $("table tr #cr-diff").text("");
                    }
                }
            });

            /* Dr - Cr dropdown changed */
            $(document).on('change', '.dc-dropdown', function() {
                var drValue = $(this).parent().parent().next().next().children().children().prop('value');
                var crValue = $(this).parent().parent().next().next().next().children().children().prop('value');

                if ($(this).parent().parent().next().children().children().val() == "0") {
                    return;
                }

                drValue = parseFloat(drValue);
                if (isNaN(drValue))
                    drValue = 0;

                crValue = parseFloat(crValue);
                if (isNaN(crValue))
                    crValue = 0;

                if ($(this).prop('value') == "D") {
                    if (drValue == 0 && crValue != 0) {
                        $(this).parent().parent().next().next().children().children().prop('value', crValue);
                    }
                    $(this).parent().parent().next().next().next().children().children().prop('value', "");
                    $(this).parent().parent().next().next().next().children().children().prop('disabled', 'disabled');
                    $(this).parent().parent().next().next().children().children().prop('disabled', '');
                } else {
                    if (crValue == 0 && drValue != 0) {
                        $(this).parent().parent().next().next().next().children().prop('value', drValue);
                    }
                    $(this).parent().parent().next().next().children().children().prop('value', "");
                    $(this).parent().parent().next().next().children().children().prop('disabled', 'disabled');
                    $(this).parent().parent().next().next().next().children().children().prop('disabled', '');
                }
                /* Recalculate Total */
                $('.dr-item:first').trigger('change');
                $('.cr-item:first').trigger('change');
            });

            /* Ledger dropdown changed */
            $(document).on('change', '.ledger-dropdown', function() {
                if ($(this).val() == "0") {
                    /* Reset and diable dr and cr amount */
                    $(this).parent().parent().next().children().children().prop('value', "");
                    $(this).parent().parent().next().next().children().children().prop('value', "");
                    $(this).parent().parent().next().children().children().prop('disabled', 'disabled');
                    $(this).parent().parent().next().next().children().children().prop('disabled', 'disabled');
                } else {
                    /* Enable dr and cr amount and trigger Dr/Cr change */
                    $(this).parent().parent().next().children().children().prop('disabled', '');
                    $(this).parent().parent().next().next().children().children().prop('disabled', '');
                    $(this).parent().parent().prev().children().children().trigger('change');
                }
                /* Trigger dr and cr change */
                $(this).parent().parent().next().children().children().trigger('change');
                $(this).parent().parent().next().next().children().children().trigger('change');

                var ledgerid = $(this).val();
                var rowid = $(this);
                if (ledgerid > 0) {
                    $.ajax({
                        url: '/finance/ledgers/cl',
                        data: 'id=' + ledgerid,
                        dataType: 'json',
                        success: function(data)
                        {
                            var ledger_bal = parseFloat(data['cl']['balance']);
                            if (data['cl']['dc'] == 'D') {
                                rowid.parent().parent().next().next().next().next().children().text("Dr " + ledger_bal);
                            } else if (data['cl']['dc'] == 'C') {
                                rowid.parent().parent().next().next().next().next().children().text("Cr " + ledger_bal);
                            } else {
                                rowid.parent().parent().next().next().next().next().children().text("");
                            }
                        }
                    });
                } else {
                    rowid.parent().parent().next().next().next().next().children().text("");
                }
            });

            /* Recalculate Total */
            $(document).on('click', 'table td .recalculate', function() {
                /* Recalculate Total */
                $('.dr-item:first').trigger('change');
                $('.cr-item:first').trigger('change');
            });

            /* Delete ledger row */
            $(document).on('click', '.deleterow', function() {
                $(this).parent().parent().remove();
                /* Recalculate Total */
                $('.dr-item:first').trigger('change');
                $('.cr-item:first').trigger('change');
            });

            /* Add ledger row */
            $(document).on('click', '.addrow', function() {
                var cur_obj = this;
                $.ajax({
                    url: "{{URL::to('/finance/entries/addrow/all')}}",
                    success: function(data) {
                        $(cur_obj).parent().parent().after(data);
                        /* Trigger ledger item change */
                        $(cur_obj).parent().parent().next().children().first().next().children().children().children().trigger('change');
                    }
                });
            });





            /* On page load initiate all triggers */
            $('.dc-dropdown').trigger('change');
            $('.ledger-dropdown').trigger('change');
            $('.dr-item:first').trigger('change');
            $('.cr-item:first').trigger('change');

            /* Calculate date range in javascript */
            startDate = new Date(1546174800000  + (new Date().getTimezoneOffset() * 60 * 1000));
            endDate = new Date(1577797199000  + (new Date().getTimezoneOffset() * 60 * 1000));

            /* Setup jQuery datepicker ui */
            /*
             $('#EntryDate').datepicker({
             minDate: startDate,
             maxDate: endDate,
             dateFormat: '',
             numberOfMonths: 1,
             });
             */
        });

    </script>


@endsection