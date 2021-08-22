@extends('finance::layouts.master')
@section('section-title')

    <h1>
        <i class="fa fa-search"></i>Finance
    </h1>
    <ul class="breadcrumb">
        <li>
            <a href="/">
                <i class="fa fa-home"></i>Home
            </a>
        </li>
        <li>
            <a href="/library/default/index">Finacne</a>
        </li>
        <li class="active">Manage Account</li>
    </ul>

@endsection
@section('page-content')
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">

            <!-- ./col -->
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Add Receipt Entry</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="entry edit form">
                            <form action="{{URL::to('finance/accounts/entries/update/store')}}" method="post" accept-charset="utf-8">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <input type="hidden" name="entry_id" value="{{$entry->id}}">
                                <input type="hidden" name="entry_type_id" value="{{$entry->entrytype_id}}">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="number">Number</label>
                                            <input type="text" name="number" value="2" id="number" beforeinput="" afterinput="" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="date">Date</label>
                                            <input type="text" name="date" value="19-May-2019" id="EntryDate" class="form-control hasDatepicker">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="tag_id">Tag</label>
                                            <select name="tag_id" class="form-control">
                                                <option value="0">NONE</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <table class="stripped extra">
                                    <tbody>
                                    <tr>
                                        <th>Dr/Cr</th>
                                        <th>Ledger</th>
                                        <th>Dr Amount (৳)</th>
                                        <th>Cr Amount (৳)</th>
                                        <th>Narration</th>
                                        <th>Current Balance (৳)</th>
                                        <th>Actions</th>
                                    </tr>

                                    @php

                                        $dc_options = array(
                                                'D' => 'Dr',
                                                'C' => 'Cr'
                                            );

                                    @endphp

                                    @foreach ($curEntryitems as $row => $entryitem)

                                        <tr>

                                            @if(empty($entryitem['dc']))
                                                <td>
                                                    <div class="form-group-entryitem">

                                                        {{ Form::select('Entryitem[' . $row . '][dc]', $dc_options, "", array('class' => 'dc-dropdown form-control')) }}

                                                    </div>
                                                </td>

                                            @else

                                                <td>
                                                    <div class="form-group-entryitem">
                                                        {{ Form::select('Entryitem[' . $row . '][dc]', $dc_options, $entryitem['dc'], array('class' => 'dc-dropdown form-control')) }}

                                                    </div>
                                                </td>


                                            @endif

                                            @if (empty($entryitem['ledger_id']))
                                                <td>
                                                    <div class="form-group-entryitem">
                                                        <select class="ledger-dropdown form-control" name="<?= 'Entryitem[' . $row . '][ledger_id]'; ?>">
                                                            @foreach ($ledger_options as $id => $ledger)
                                                                {{--<option value="{{$id}}" ($id < 0) ? 'disabled' : "" >{{ $ledger}}</option>--}}
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </td>
                                            @else
                                                <td>
                                                    <div class="form-group-entryitem">
                                                        <select class="ledger-dropdown form-control" name="<?= 'Entryitem[' . $row . '][ledger_id]'; ?>">
                                                            <?php foreach ($ledger_options as $id => $ledger): ?>
                                                                <option value="<?= $id; ?>" <?= ($entryitem['ledger_id'] == $id) ? 'selected' : "" ?> <?= ($id < 0) ? 'disabled' : "" ?> ><?= $ledger; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </td>

                                            @endif


                                            @if (empty($entryitem['dr_amount']))

                                                <td>
                                                    <div class="form-group-entryitem">
                                                        <input  type="text" name="Entryitem[{{$row}}][dr_amount]" value="" class="dr-item form-control" />
                                                    </div>
                                                </td>
                                            @else

                                                <td>
                                                    <div class="form-group-entryitem">
                                                        <input type="text" name="Entryitem[{{$row}}][dr_amount]" value="{{$entryitem['dr_amount']}}" class="dr-item form-control" />
                                                    </div>
                                                </td>

                                            @endif

                                            {{--credit amount--}}

                                            @if (empty($entryitem['cr_amount']))

                                                <td>
                                                    <div class="form-group-entryitem">
                                                        <input type="text" name="Entryitem[{{$row}}][cr_amount]" value="" class="cr-item form-control" />
                                                    </div>
                                                </td>
                                            @else

                                                <td>
                                                    <div class="form-group-entryitem">
                                                        <input type="text" name="Entryitem[{{$row}}][cr_amount]" value="{{$entryitem['cr_amount']}}" class="cr-item form-control" />
                                                    </div>
                                                </td>

                                            @endif




                                            <td>
                                                <div class='form-group-entryitem'>
                                                    <input type="text" name="Entryitem[{{$row}}][narration]" value="{{$entryitem['narration']}}" class="form-control" />
                                                </div>
                                            </td>
                                            <td class="ledger-balance">
                                                <div></div>
                                            </td>
                                            <td><span class="deleterow glyphicon glyphicon-trash" escape="false"></span></td>
                                        </tr>

                                    @endforeach
                                    <tr class="bold-text">
                                        <td>Total</td>
                                        <td></td>
                                        <td id="dr-total" style="background-color: rgb(255, 255, 153);">5000</td>
                                        <td id="cr-total" style="background-color: rgb(255, 255, 153);">5000</td>
                                        <td><span class="recalculate" escape="false"><i class="glyphicon glyphicon-refresh"></i></span></td>
                                        <td></td>
                                        <td><span class="addrow" escape="false" style="padding-left: 5px;"><i class="glyphicon glyphicon-plus"></i></span></td>
                                    </tr>
                                    <tr class="bold-text">
                                        <td>Difference</td>
                                        <td></td>
                                        <td id="dr-diff">-</td>
                                        <td id="cr-diff"></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    </tbody>
                                </table>
                                <br>
                                <div class="form-group">
                                    <label for="notes">Note</label>
                                    <textarea name="notes" cols="40" rows="10" class="form-control">Hello ROmesh </textarea>
                                </div>
                                <div class="form-group">
                                    <input type="submit" name="submit" value="Submit" class="btn btn-success">
                                    <span class="link-pad"></span><a href="http://localhost/accountant/entries/index" class="btn btn-default">Cancel</a>
                                    <a></a>
                                </div>
                            </form>
                            <a> </a>
                        </div>
                        <a>
                        </a>
                    </div>
                    <a>
                    </a>
                </div>
                <a>
                </a>
            </div>
            <a>
                <!-- /.row -->
            </a>
        </div>
    </section>

@endsection

@section('page-script')
    <script type = "text/javascript" >

        $(document).ready(function() {

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
                    result = result / 100;
                    return result;
                }
                if (op == '-') {
                    result = param1 - param2;
                    result = result / 100;
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
                        url: '{{URL::to('/finance/accounts/ledgers/cl')}}/'+ ledgerid,
                        data: 'id=' + ledgerid,
                        dataType: 'json',
                        success: function(data) {
                            var ledger_bal = parseFloat(data['cl']['amount']);

                            var prefix = '';
                            var suffix = '';
                            if (data['cl']['status'] == 'neg') {
                                prefix = '<span class="error-text">';
                                suffix = '</span>';
                            }

                            if (data['cl']['dc'] == 'D') {
                                rowid.parent().parent().next().next().next().next().children().html(prefix + "Dr " + ledger_bal + suffix);
                            } else if (data['cl']['dc'] == 'C') {
                                rowid.parent().parent().next().next().next().next().children().html(prefix + "Cr " + ledger_bal + suffix);
                            } else {
                                rowid.parent().parent().next().next().next().next().children().html("");
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
                    url: '{{URL::to('/finance/accounts/entries/addrow',$entrytype->restriction_bankcash)}}/',
                    success: function(data) {
                        $(cur_obj).parent().parent().before(data);
                        /* Trigger ledger item change */
                        $(cur_obj).parent().parent().next().children().first().next().children().children().children().trigger('change');
                        $("tr.ajax-add .ledger-dropdown").select2({
                            width: '100%'
                        });
                    }
                });
            });

            /* On page load initiate all triggers */
            $('.dc-dropdown').trigger('change');
            $('.ledger-dropdown').trigger('change');
            $('.dr-item:first').trigger('change');
            $('.cr-item:first').trigger('change');

            /* Calculate date range in javascript */
            startDate = new Date(1546300800000 + (new Date().getTimezoneOffset() * 60 * 1000));
            endDate = new Date(1577750400000 + (new Date().getTimezoneOffset() * 60 * 1000));

            /* Setup jQuery datepicker ui */
            $('#EntryDate').datepicker({
                minDate: startDate,
                maxDate: endDate,
                dateFormat: 'dd-M-yy',
                numberOfMonths: 1,
            });

            $(".ledger-dropdown").select2({
                width: '100%'
            });
        });


    </script>


@endsection


