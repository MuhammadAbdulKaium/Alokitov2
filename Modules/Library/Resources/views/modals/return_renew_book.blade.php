<div class="modal-dialog">
    <div class="modal-content">
        <form id="add" action="/library/library-borrow-transaction/return-book/update/{{$issueBookProfile->id}}" method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <input type="hidden" name="book_id" value="{{$issueBookProfile->book_id}}">
            <input type="hidden" name="asn_no" value="{{$bookStockProfile->asn_no}}">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <p>
                </p><h4>
                    <i class="fa fa-hand-o-left"></i> Return Book        </h4>
                <p></p>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group field-libraryborrowtransaction-lbt_renew_status">

                            <input name="book_status" value="0" type="hidden"><label><input id="libraryborrowtransaction-lbt_renew_status" name="book_status" value="1" type="checkbox"> Is Renewed</label>

                            <div class="help-block"></div>
                        </div>
                    </div>
                    <div class="payment-option">
                        <div class="container">
                            @php

                            $startDate = \Carbon\Carbon::parse($issueBookProfile->issue_date);
                              $endDate   = \Carbon\Carbon::parse($issueBookProfile->due_date);
                              $dateDiff = $current->diff($endDate)->format("%a");
                              $fine=($issueBookProfile->daily_fine)*($dateDiff);

                            @endphp
                            @if($endDate>=date('Y-m-d'))
                                <span class="label label-success" style="font-size:12px">{{$dateDiff}} day(s) left</span>
                            @else
                                <span class="label label-danger" style="font-size:12px">Overdue {{$dateDiff}} day(s) Fine {{$fine}} Taka</span>

                            @endif

                        </div>
                    </div>
                </div>
            </div><!--/ end modal-body-->

            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default pull-right custom-btn" type="button">Close</button>

                <div class="returnBook">
                    <button type="submit" class="btn btn-info pull-left">Return Book</button>	</div>
                <div id="renewBook" style="display: none;">
                    <button type="submit" class="btn btn-success pull-left">Renew Book</button>	</div>
            </div><!--/ end modal-footer-->

        </form>
        <div>
        </div>
        <div class="payment-option" style="padding: 10px">
                @if($endDate>=date('Y-m-d'))
                @else
                    <form id="fine" method="post" action="/library/library-borrow-transaction/return-book-with-fine/{{$issueBookProfile->id}}">
                        {{ csrf_field() }}
                        <input type="hidden" name="total_due" value="{{$fine}}">
                        <button type="submit" class="btn btn-primary">Paid & Return</button>
                        <a href="/library/library-borrow-transaction/return-book-with-fine-show/{{$issueBookProfile->id}}" class="btn btn-primary">Manual Pay</a>
                    </form>
                @endif
        </div>


        <script src="/js/all-dff093c83c582ace1d8c6d13bea54306.js?v=1491379972"></script>
        <script type="text/javascript">jQuery('#add').yiiActiveForm([{"id":"libraryborrowtransaction-lbt_renew_status","name":"lbt_renew_status","container":".field-libraryborrowtransaction-lbt_renew_status","input":"#libraryborrowtransaction-lbt_renew_status","validate":function (attribute, value, messages, deferred, $form) {yii.validation.number(value, messages, {"pattern":/^\s*[+-]?\d+\s*$/,"message":"Is Renewed must be an integer.","skipOnEmpty":1});}}], []);

            var a = $('#libraryborrowtransaction-lbt_renew_status').is(':checked')
            $('#renewBook').hide();
            if(a == true){
                $('#renewBook').show();
                $('.returnBook').css('display', 'none');
            }

            $('#libraryborrowtransaction-lbt_renew_status').change(function(){
                $('#renewBook').toggle();
                $('.returnBook').toggle();
            });
        </script></div>
</div>