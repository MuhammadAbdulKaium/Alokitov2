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
    <div class="box box-body">
        <div style="padding:25px;">
            <script type="text/javascript">
                $(document).ready(function() {

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
            <div>
                Number : {{$entriesProfile->tran_serial}}
                <br>
                <br>Date : {{$entriesProfile->tran_date}}
                <br>
                <br>
                <table class=" table stripped">
                    <tbody>
                    <tr>
                        <th>Dr/Cr</th>
                        <th>Ledger</th>
                        <th>Dr Amount</th>
                        <th>Cr Amount</th>
                    </tr>

                   @foreach($entriesProfile->entriesItem() as $entreItem)

                    <tr>
                        <td>{{$entreItem->dc}}r</td>
                        <td>{{$entreItem->ledger()->name}}</td>
                        <td>{{$entreItem->dr_amount}}</td>
                        <td>{{$entreItem->cr_amount}}</td>
                    </tr>
                   @endforeach
                    </tbody>
                </table>
                <br>Narration : Description Here
                <br>
                <br>Tag :
                <br>
                <br>
                <a href="/finance/entries/edit/{{$entriesProfile->id}}" class="btn btn-primary">Edit</a>
                <span class="link-pad"></span>
                <form action="/finance/entries/delete/{{$entriesProfile->id}}" name="post_5c7b7f9dbe2c4" id="post_5c7b7f9dbe2c4" style="display:none;" method="post">
                    <input type="hidden" name="_method" value="POST">
                    <input type="hidden" name="data[_Token][key]" value="799d781d5490f5269db5ee3103ec69c30bcb512d" id="Token1349657751">
                    <div style="display:none;">
                        <input type="hidden" name="data[_Token][fields]" value="e290fe33bba78cd23061a45d630cc7fb888a9235%3A" id="TokenFields1421725613">
                        <input type="hidden" name="data[_Token][unlocked]" value="Entryitem" id="TokenUnlocked2018957398">
                    </div>
                </form>
                <a href="#" class="btn btn-primary" onclick="if (confirm('Are you sure ?')) { document.post_5c7b7f9dbe2c4.submit(); } event.returnValue = false; return false;">Delete</a>
                <span class="link-pad"></span>
                <a href="#" data-toggle="modal" data-id="1" data-type="Receipt" data-number="CDL1" data-target="#emailModal">
                    <span class="glyphicon glyphicon-envelope"></span>
                </a>
                {{--<span class="link-pad"></span>--}}
                {{--<a href="/finance/entries/download/1" class="no-hover">--}}
                    {{--<span class="glyphicon glyphicon-download-alt"></span>--}}
                {{--</a>--}}
                {{--<span class="link-pad"></span>--}}
                {{--<a href="/finance/entries/view/receipt/1" onclick="window.open('/finance/entries/printpreview/1', 'windowname','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=600,height=600'); return false;">--}}
                    {{--<span class="glyphicon glyphicon-print"></span>--}}
                {{--</a>--}}
                {{--<span class="link-pad"></span>--}}
                {{--<a href="/finance/entries" class="btn btn-default">Cancel</a>--}}
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