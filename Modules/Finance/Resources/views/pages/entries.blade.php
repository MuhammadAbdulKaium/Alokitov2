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

<!-- page content -->
@section('page-content')

    <div class="box box-body">
        <div style="padding:25px;">
            <script type="text/javascript">
                $(document).ready(function() {
                    $("#EntryShow").change(function() {
                        this.form.submit();
                    });

                    var entryId = 0;
                    $("button#send").click(function() {
                        $(".modal-body").hide();
                        $(".modal-footer").hide();
                        $(".modal-ajax").show();
                        $.ajax({
                            type: "POST",
                            url: '/finance/entries/email/' + entryId,
                            data: $('form#emailSubmit').serialize(),
                            success: function(response) {
                                msg = JSON.parse(response); console.log(msg);
                                if (msg['status'] == 'success') {
                                    $(".modal-error-msg").html("");
                                    $(".modal-error-msg").hide();
                                    $(".modal-body").show();
                                    $(".modal-footer").show();
                                    $(".modal-ajax").hide();
                                    $("#emailModal").modal('hide');
                                } else {
                                    $(".modal-error-msg").html(msg['msg']);
                                    $(".modal-error-msg").show();
                                    $(".modal-body").show();
                                    $(".modal-footer").show();
                                    $(".modal-ajax").hide();
                                }
                            },
                            error: function() {
                                $(".modal-error-msg").html("Error sending email.");
                                $(".error-msg").show();
                                $(".modal-body").show();
                                $(".modal-footer").show();
                                $(".modal-ajax").hide();
                            }
                        });
                    });

                    $('#emailModal').on('show.bs.modal', function(e) {
                        $(".modal-error-msg").html("");
                        $(".modal-ajax").hide();
                        $(".modal-error-msg").hide();
                        entryId = $(e.relatedTarget).data('id');
                        var entryType = $(e.relatedTarget).data('type');
                        var entryNumber = $(e.relatedTarget).data('number');
                        $("#emailModelType").html(entryType);
                        $("#emailModelNumber").html(entryNumber);
                    });
                });
            </script>
            <div class="row">

                <div class="col-md-3">
                  Search Option
                </div>
            </div>
            <br>
            <table class="table stripped">
                <tbody>
                <tr>
                    <th>
                        <a href="/finance/entries/index/sort:date/direction:asc" class="desc">Date</a>
                    </th>
                    <th>
                        <a href="/finance/entries/index/sort:number/direction:asc">Number</a>
                    </th>
                    <th>Ledger</th>
                    <th>
                        <a href="/finance/entries/index/sort:entrytype_id/direction:asc">Type</a>
                    </th>
                    <th>
                        <a href="/finance/entries/index/sort:tag_id/direction:asc">Tag</a>
                    </th>
                    <th>
                        <a href="/finance/entries/index/sort:dr_total/direction:asc">Debit Amount</a>
                    </th>
                    <th>
                        <a href="/finance/entries/index/sort:cr_total/direction:asc">Credit Amount</a>
                    </th>
                    <th>Actions</th>
                </tr>

                @foreach($entries as $entry)

                <tr>
                    <td>{{$entry->tran_date}}</td>
                    <td>{{$entry->tran_serial}}</td>
                    <td>Dr Bank Asia / Cr Fees</td>
                    <td>
                        @if($entry->voucher_type_id==1)
                            Receipt
                        @elseif($entry->voucher_type_id==2)

                            Payment

                            @endif
                    </td>
                    <td></td>
                    <td>{{$entry->sumCreditEntries()}}</td>
                    <td>{{$entry->sumDebitEntries()}}</td>
                    <td>
                        <a href="/finance/entries/view/{{$entry->id}}" class="no-hover">
                            <i class="glyphicon glyphicon-log-in"></i> View
                        </a>
                        <span class="link-pad"></span>
                        <a href="/finance/entries/edit/{{$entry->id}}" class="no-hover">
                            <i class="glyphicon glyphicon-edit"></i> Edit
                        </a>
                        <span class="link-pad"></span>
                        <form action="/finance/entries/delete/{{$entry->id}}" name="post_5c76387a96caf" id="post_5c76387a96caf" style="display:none;" method="post">
                            <input type="hidden" name="_method" value="POST">
                            <input type="hidden" name="data[_Token][key]" value="59c2ced2b302b5de4c38026baff2f8996ec4c5cb" id="Token1732986259">
                            <div style="display:none;">
                                <input type="hidden" name="data[_Token][fields]" value="2bcf0eee30b3444c4a80f9de69330e6a59f2898b%3A" id="TokenFields1671824108">
                                <input type="hidden" name="data[_Token][unlocked]" value="Entryitem%7CEntryitem" id="TokenUnlocked909563334">
                            </div>
                        </form>
                        <a href="#" class="no-hover" onclick="if (confirm('Are you sure you want to delete the entry ?')) { document.post_5c76387a96caf.submit(); } event.returnValue = false; return false;">
                            <i class="glyphicon glyphicon-trash"></i> Delete
                        </a>
                        {{--<span class="link-pad"></span>--}}
                        {{--<a href="#" data-toggle="modal" data-id="1" data-type="Receipt" data-number="CDL1" data-target="#emailModal">--}}
                            {{--<span class="glyphicon glyphicon-envelope"></span>--}}
                        {{--</a>--}}
                        {{--<span class="link-pad"></span>--}}
                        {{--<a href="/finance/entries/download/{{$entry->id}}" class="no-hover">--}}
                            {{--<span class="glyphicon glyphicon-download-alt"></span>--}}
                        {{--</a>--}}
                        {{--<span class="link-pad"></span>--}}
                        {{--<a href="/finance/entries" onclick="window.open('/finance/entries/printpreview/1', 'windowname','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=600,height=600'); return false;">--}}
                            {{--<span class="glyphicon glyphicon-print"></span>--}}
                        {{--</a>--}}
                    </td>
                </tr>
@endforeach





                </tbody>
            </table>
            <div class="text-center paginate">
                <ul class="pagination">
                    <li class="disabled">
                        <a>prev</a>
                    </li>
                    <li class="disabled">
                        <a>next</a>
                    </li>
                </ul>
            </div>
            <!-- email modal -->
            <div class="modal fade" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="emailSubmit" name="emailSubmit">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                                <h4 class="modal-title" id="myModalLabel">Email
                                    <span id="emailModelType"></span> Entry Number "
                                    <span id="emailModelNumber"></span>"
                                </h4>
                            </div>
                            <div class="modal-error-msg"></div>
                            <div class="modal-body">
                                <div>
                                    <label for="email">Email to</label>
                                    <div class="input email">
                                        <input name="data[email]" class="form-control" type="email" id="email">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" id="send">Send</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                            <div class="modal-ajax">Please wait, sending email...</div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page-script')
@endsection